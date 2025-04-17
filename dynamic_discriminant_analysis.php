<?php
    include 'db.php';

    function getSGPlaceholder($subgroups) {
        return str_repeat("?,", count($subgroups) - 1) . "?";
    }

    function getISPlaceholder($isolationSources) {
        return str_repeat("?,", count($isolationSources) - 1) . "?";
    }

    function getLibPlaceholder($libs) {
        return str_repeat("?,", count($libs) - 1) . "?";
    }

    function getBPPlaceholder($bioprojects) {
        return str_repeat("?,", count($bioprojects) - 1) . "?";
    }

    function getParamString($subgroups, $isolationSources, $libs, $bioprojects) {
        return "s" . str_repeat("s", count($subgroups)) . str_repeat("s", count($isolationSources)) . str_repeat("s", count($libs)) . str_repeat("s", count($bioprojects));
    }

    function getValues($at, $libs, $subgroups, $isolationSources, $bioprojects) {
        $values = array($at);
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

    $rows1 = array();
    $rows2 = array();
    $runs = array();
    $bioprojects = array();
    $disease1 = (isset($_POST["discriminant_ds_1"])) ? $_POST["discriminant_ds_1"] : "";
    $disease2 = (isset($_POST["discriminant_ds_2"])) ? $_POST["discriminant_ds_2"] : "";
    $at = (isset($_POST["discriminant_at"])) ? $_POST["discriminant_at"] : "";
    $libs = (isset($_POST["discriminant_lib"])) ? $_POST["discriminant_lib"] : array();
    $method_joined = (isset($_POST["method"])) ? $_POST["method"] : "";
    $method_split = explode("_", $method_joined);
    $p_adjust_method = (count($method_split) == 2) ? $method_split[1] : "";
    $method = (count($method_split) == 2) ? $method_split[0] : "";
    $alpha = (isset($_POST["alpha"])) ? $_POST["alpha"] : "";
    $filter_thres = (isset($_POST["filter_thres"])) ? $_POST["filter_thres"] : "";
    $taxa_level = (isset($_POST["taxa_level"])) ? $_POST["taxa_level"] : "";
    $threshold = (isset($_POST["threshold"])) ? $_POST["threshold"] : "";

    if (isset($_POST["discriminant_sg_1"]) && isset($_POST["discriminant_bp_1"])) {
        $subgroups1 = $_POST['discriminant_sg_1'];
        $isolationSources1 = $_POST['discriminant_is_1'];
        $bioprojects1 = $_POST['discriminant_bp_1'];
        $bioprojects = array_merge($bioprojects, $bioprojects1);

//         echo implode(', ', $subgroups1)."<br/>";
//         echo implode(', ', $isolationSources1)."<br/>";
//         echo $at."<br/>";
//         echo implode(', ', $libs)."<br/>";
//         echo implode(', ', $bioprojects1)."<br/>";

        $query = "select Run from run inner join disease on run.SubGroup = disease.SubGroup where AssayType=? and run.SubGroup in (".getSGPlaceholder($subgroups1).") and IsolationSource in (".getISPlaceholder($isolationSources1).") and LibraryLayout in (".getLibPlaceholder($libs).") and BioProject in (".getBPPlaceholder($bioprojects1).");";
        $paramString = getParamString($subgroups1, $isolationSources1, $libs, $bioprojects1);
        $values = getValues($at, $libs, $subgroups1, $isolationSources1, $bioprojects1);

        $conn = connect();
        $stmt = $conn->prepare($query);

        array_unshift($values, $paramString);
        call_user_func_array(
            array($stmt, "bind_param"),
            refValues($values)
        );
        $stmt->execute();
        $rows1 = execute_and_fetch_assoc($stmt);

        $stmt->close();
        closeConnection($conn);

        foreach ($rows1 as $row)
            array_push($runs, $row["Run"]);
//         echo count($rows1)."<br/>";

        $heading1 = "<b>Group 1:</b> ".$disease1;
        $heading2 = "<b>Isolation sources:</b> [".implode(", ", $isolationSources1)."]";
        $heading3 = "<b>Subgroups:</b> [".implode(", ", $subgroups1)."]";
        $heading4 = "<b>BioProjects:</b> [".implode(", ", $bioprojects1)."]";
    }

    if (isset($_POST["discriminant_sg_2"]) && isset($_POST["discriminant_bp_2"])) {
        $subgroups2 = $_POST['discriminant_sg_2'];
        $isolationSources2 = $_POST['discriminant_is_2'];
        $bioprojects2 = $_POST['discriminant_bp_2'];
        $bioprojects = array_merge($bioprojects, $bioprojects2);

//         echo implode(', ', $subgroups2)."<br/>";
//         echo implode(', ', $isolationSources2)."<br/>";
//         echo $at."<br/>";
//         echo implode(', ', $libs)."<br/>";
//         echo implode(', ', $bioprojects2)."<br/>";

        $query = "select Run from run inner join disease on run.SubGroup = disease.SubGroup where AssayType=? and run.SubGroup in (".getSGPlaceholder($subgroups2).") and IsolationSource in (".getISPlaceholder($isolationSources2).") and LibraryLayout in (".getLibPlaceholder($libs).") and BioProject in (".getBPPlaceholder($bioprojects2).");";
        $paramString = getParamString($subgroups2, $isolationSources2, $libs, $bioprojects2);
        $values = getValues($at, $libs, $subgroups2, $isolationSources2, $bioprojects2);

        $conn = connect();
        $stmt = $conn->prepare($query);

        array_unshift($values, $paramString);
        call_user_func_array(
            array($stmt, "bind_param"),
            refValues($values)
        );
        $stmt->execute();
        $rows2 = execute_and_fetch_assoc($stmt);

        $stmt->close();
        closeConnection($conn);

        foreach ($rows2 as $row)
            array_push($runs, $row["Run"]);
//         echo count($rows2)."<br/>";

        $heading5 = "<b>Group 2:</b> ".$disease2;
        $heading6 = "<b>Isolation sources:</b> [".implode(", ", $isolationSources2)."]";
        $heading7 = "<b>Subgroups:</b> [".implode(", ", $subgroups2)."]";
        $heading8 = "<b>BioProjects:</b> [".implode(", ", $bioprojects2)."]";
    }

    $heading9 = "<b>Assay type:</b> ".$at;
    $heading10 = "<b>Library layouts:</b> [".implode(", ", $libs)."]";
    $heading11 = "Number of runs found in database: ".count($runs);

    $dataJSON = json_encode(
        array(
            "at" => $at,
            "bioprojects" => $bioprojects,
            "runs" => $runs,
            "method" => $method,
            "alpha" => $alpha,
            "p_adjust_method" => $p_adjust_method,
            "filter_thres" => $filter_thres,
            "taxa_level" => $taxa_level,
            "threshold" => $threshold
        )
    );
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Discriminant analysis - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/plot_dynamic_discriminant.js"></script>
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
            <p style="margin:0; font-size:1.2em; font-weight:bold; text-align:center;">Discriminant analysis result</p>
            <center><button type="button" style="margin:5px;" onclick="showDiv('query_display_div')">Show query</button></center>
            <div class="browse-result" id="query_display_div" style="margin:10px 0 10px 0; font-size:1em; display:none;">
                <center><button type="button" class="round" style="margin:3px;" onclick="hideDiv('query_display_div')">&#10005;</button></center>
                <?php
                    if (isset($_POST["discriminant_sg_1"]) && isset($_POST["discriminant_bp_1"])) {
                ?>
                        <p style="margin:0 5px;"><?php echo $heading1."<br/>"; ?></p>
                        <ul style="margin-top:0;">
                            <li><?php echo $heading2."<br/>"; ?></li>
                            <li><?php echo $heading3."<br/>"; ?></li>
                            <li><?php echo $heading4."<br/>"; ?></li>
                        </ul>
                <?php
                    } if (isset($_POST["discriminant_sg_2"]) && isset($_POST["discriminant_bp_2"])) {
                ?>
                        <p style="margin:0 5px;"><?php echo $heading5."<br/>"; ?></p>
                        <ul style="margin-top:0;">
                            <li><?php echo $heading6."<br/>"; ?></li>
                            <li><?php echo $heading7."<br/>"; ?></li>
                            <li><?php echo $heading8."<br/>"; ?></li>
                        </ul>
                <?php
                    }
                ?>
                <p style="margin:0 5px;"><?php echo $heading9."<br/>"; ?></p>
                <p style="margin:0 5px;"><?php echo $heading10."<br/>"; ?></p>
            </div>
            <p style="margin:10px 0 10px 0; font-size:1em; font-weight:bold; text-align:center;"><?php echo $heading11; ?></p>

            <div id="lda_download_div" style="width:100%; text-align:center; display:none;">
                <a id="lda_download_button" download="discriminant_analysis_figure_1_data.csv">
                    <button type="button" style="margin:2px;">Download figure data</button>
                </a>
            </div>
            <div id="lda_plot_div" style="width:90%; margin:0 5% 0 5%;">
                <center><img style="height:300px;" src="resource/loading.gif" /></center>
            </div>

            <div id="merged_lda_download_div" style="width:100%; text-align:center; display:none;">
                <a id="merged_lda_download_button" download="discriminant_analysis_figure_2_data.csv">
                    <button type="button" style="margin:2px;">Download figure data</button>
                </a>
            </div>
            <div id="merged_lda_plot_div" style="width:90%; margin:0 5% 0 5%;"></div>
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
        <?php echo "getHeatmapData('lda_plot_div', 'merged_lda_plot_div', '".$dataJSON."');"; ?>
    </script>
</html>

