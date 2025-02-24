<?php
    include 'db.php';

    $disease = $_POST['taxonomic_ds'];
    $subgroups = $_POST['taxonomic_sg'];
    $isolationSources = $_POST['taxonomic_is'];
    $at = $_POST['taxonomic_at'];
    $libs = $_POST['taxonomic_lib'];
    $bioprojects = $_POST['taxonomic_bp'];

//     echo $disease."<br/>";
//     echo implode(', ', $subgroups)."<br/>";
//     echo implode(', ', $isolationSources)."<br/>";
//     echo $at."<br/>";
//     echo implode(', ', $libs)."<br/>";
//     echo implode(', ', $bioprojects)."<br/>";

    function getSGPlaceholder() {
        global $subgroups;
        return str_repeat("?,", count($subgroups) - 1) . "?";
    }

    function getISPlaceholder() {
        global $isolationSources;
        return str_repeat("?,", count($isolationSources) - 1) . "?";
    }

    function getLibPlaceholder() {
        global $libs;
        return str_repeat("?,", count($libs) - 1) . "?";
    }

    function getBPPlaceholder() {
        global $bioprojects;
        return str_repeat("?,", count($bioprojects) - 1) . "?";
    }

    function getParamString() {
        global $subgroups;
        global $isolationSources;
        global $libs;
        global $bioprojects;
        return "ss" . str_repeat("s", count($subgroups)) . str_repeat("s", count($isolationSources)) . str_repeat("s", count($libs)) . str_repeat("s", count($bioprojects));
    }

    function getValues() {
        global $disease;
        global $at;
        global $libs;
        global $subgroups;
        global $isolationSources;
        global $bioprojects;
        $values = array($disease, $at);
        foreach ($subgroups as $sg)
            array_push($values, $sg);
        foreach ($isolationSources as $is)
            array_push($values, $is);
        foreach ($libs as $lib)
            array_push($values, $lib);
        foreach ($bioprojects as $bp)
            array_push($values, $bp);
        return $values;
    }

    function refValues($arr){
        $refs = array();
        for($i=0; $i<count($arr); ++$i)
            $refs[$i] = &$arr[$i];
        return $refs;
    }

    $query = "select Run from run inner join disease on run.SubGroup = disease.SubGroup where disease.Grp=? and AssayType=? and run.SubGroup in (".getSGPlaceholder().") and IsolationSource in (".getISPlaceholder().") and LibraryLayout in (".getLibPlaceholder().") and BioProject in (".getBPPlaceholder().");";
    $paramString = getParamString();
    $values = getValues();

    $conn = connect();
    $stmt = $conn->prepare($query);

    array_unshift($values, $paramString);
    call_user_func_array(
        array($stmt, "bind_param"),
        refValues($values)
    );
    $stmt->execute();
    $rows = execute_and_fetch_assoc($stmt);
//     echo count($rows)."<br/>";

    $stmt->close();
    closeConnection($conn);

    $runs = array();
    foreach ($rows as $row)
        array_push($runs, $row["Run"]);

    $heading1 = "<b>Group:</b> ".$disease;
    $heading2 = "<b>Assay type:</b> ".$at;
    $heading3 = "<b>Isolation sources:</b> [".implode(", ", $isolationSources)."]";
    $heading4 = "<b>Subgroups:</b> [".implode(", ", $subgroups)."]";
    $heading5 = "<b>BioProjects:</b> [".implode(", ", $bioprojects)."]";
    $heading6 = "<b>Library layouts:</b> [".implode(", ", $libs)."]";
    $heading7 = "Number of runs found in database: ".count($runs);

    $dataJSON = json_encode(
        array(
            "at" => $at,
            "bioprojects" => $bioprojects,
            "runs" => $runs
        )
    );
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Taxonomic analysis - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/plot_dynamic_taxonomic.js"></script>
        <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script>
        <script>
            function showDiv(divId){
                document.getElementById(divId).style.display = 'block';
            }
            function hideDiv(divId){
                document.getElementById(divId).style.display = 'none';
            }
        </script>
    </head>
    <body>
        <div class = "section_header">
            <center><p class="title">MDPD - Microbiome Database of Pulmonary Diseases</p></center>
        </div>

        <div class = "section_menu">
            <center>
            <table cellpadding="3px">
                <tr class="nav">
                    <td class="nav"><a href="index.php" class="side_nav">Home</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="analysis.php" class="side_nav">Analysis</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="about.php" class="side_nav">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->

        <div class = "section_middle">
            <p style="margin:0; font-size:1.2em; font-weight:bold; text-align:center;">Taxonomic analysis result</p>

            <center><button type="button" style="margin:5px;" onclick="showDiv('query_display_div')">Show query</button></center>
            <div class="browse-result" id="query_display_div" style="margin:10px 0 10px 0; font-size:1em; display:none;">
                <center><button type="button" class="round" style="margin:3px;" onclick="hideDiv('query_display_div')">&#10005;</button></center>
                <p style="margin:0 5px;">
                    <?php
                        echo $heading1."<br/>";
                        echo $heading2."<br/>";
                        echo $heading3."<br/>";
                        echo $heading4."<br/>";
                        echo $heading5."<br/>";
                        echo $heading6;
                    ?>
                </p>
            </div>
            <p style="margin:10px 0 10px 0; font-size:1em; font-weight:bold; text-align:center;"><?php echo $heading7; ?></p>

            <div id="download_div" style="width:100%; text-align:center; display:none;">
                <a id="download_button" download="taxonomic_analysis_figure_data.csv">
                    <button type="button" style="margin:2px;">Download figure data</button>
                </a>
            </div>

            <div id="analysis_plot_div" style="width:90%; margin:0 5% 0 5%;">
                <center><img style="height:300px;" src="resource/loading.gif" /></center>
            </div>
            <p id="taxa_button_group_heading" style="margin:3px; font-weight:bold; display:none;">Taxa details</p>
            <div id="taxa_button_group" style="width:100%; background-color:#fff9e6; border:1px dashed #004d99; display:none;"></div>

            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "getHeatmapData('analysis_plot_div', '".$dataJSON."');"; ?>
    </script>
</html>
