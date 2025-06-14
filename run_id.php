<?php
    include('db.php');
    
    $runID = (isset($_GET['key'])) ? $_GET['key'] : "";
    
//     $query = "select ".implode(",", array_keys($allRunAttributes))." from (run inner join disease on run.SubGroup=disease.SubGroup) where Run=?;";
    $query = "select * from (run inner join disease on run.SubGroup=disease.SubGroup) where Run=?;";
//     echo $query."<br/>".$runID."<br/>";

    $conn = connect();
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $runID);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/>";
//     $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($stmt);
    $stmt->close();
    closeConnection($conn);

    $dataJSON = json_encode(
        array(
            "run" => $runID,
            "bioproject" => $rows[0]["BioProject"],
            "at" => $rows[0]["AssayType"]
        )
    );
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Run - MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_krona.js"></script>
        <script type = "text/javascript" src = "js/plot_top_taxa_bar.js"></script>
        <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script>
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
            <?php
                if (count($rows) < 1) {
                    echo "<center><p>Error !!! Run ID: ".$runID." does not exist in the database.</p></center>";
                } else {
                    echo "<h3 style=\"margin:0; text-align:center;\">Run ID: ".$rows[0]["Run"]."</h3>";
                    echo "<h4 style=\"margin:0 0 5px 0; text-align:center;\"><a style=\"color:#003325;\" target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/sra/?term=".$runID."\">https://www.ncbi.nlm.nih.gov/sra/?term=".$runID." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></h4>";
//                     echo "<hr/><br/>";
                    echo "<table class=\"details\" border=\"1\">";
                    echo "<tr><th>Attribute</th><th>Value</th></tr>";
                    foreach($rows as $row){
                        foreach ($allRunAttributes as $name=>$fname) {
                            if ($name !== "Run") {
                                echo "<tr>";
                                echo "<td style=\"width:40%;\">".$fname."</td>";
                                if ($name === "BioProject")
                                    echo "<td style=\"width:60%;\"><a style=\"color:#003325;\" target=\"_blank\" href=\"bioproject_id.php?key=".$row[$name]."\">".$row[$name]." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></td>";
                                else
                                    echo "<td style=\"width:60%;\">".$row[$name]."</td>";
                                echo "</tr>";
                            }
                        }
                    }
                    echo "</table>";
            ?>
                    <div style="width:45%; margin:10px 2% 10px 2%; float:left;">
                        <p style="margin-top:0; font-weight:bold;">A. Taxonomic composition of the run</p>
                        <?php echo "<iframe id=\"krona_frame\" style=\"width:100%; height:600px; border:1px;\" onload=\"selectRun('".$runID."')\"></iframe>"; ?>
                        <p style="font-size:0.9em; margin-top:0;">
                            Interactive <b>Krona plot</b> shows the hierarchical distribution
                            of microbes, where the colors denote the abundances (for example
                            the most abundant taxa is red). The depth can be modified using
                            the <b>"Max depth"</b> button on the upper left corner. The
                            current view of the Krona plot can be downloaded as SVG by clicking
                            on the <b>"Snapshot"</b> button at the upper left corner. The data
                            can be downloaded using the <b>"Download Krona data"</b> button.
                        <p>
                    </div>
                    <div style="width:45%; margin:10px 2% 10px 2%; float:right;">
                        <p style="margin-top:0; font-weight:bold;">B. Top 10 abundant taxa of the run</p>
                        <div id="bar_plot_div" style="width:100%;">
                            <center><img style="height:300px;" src="resource/loading.gif" /></center>
                        </div>
                        <p id="taxa_button_group_heading" style="margin:3px; font-weight:bold; display:none;">Top taxa details</p>
                        <div id="taxa_button_group" style="width:100%; background-color:#fff9e6; border:1px dashed #004d99; display:none;"></div>
                        <p id="bar_legend" style="font-size:0.9em; margin-top:0; display:none;">
                            Top 10 abundant taxa of the run. <b>Click on the buttons</b> below
                            the plot to get the information of the respective taxa. The plot
                            can be downloaded as SVG by clicking on the <b>"
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                            </svg>
                            "</b> button in the figure menu bar at the top right corner.
                        <p>
                    </div>
                    <div style="clear:both"></div>
            <?php
                }
            ?>
            
            <hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "getKronaData('".$row['BioProject']."','".$row['AssayType']."','".$row['IsolationSource']."','runwise');"; ?>
        <?php echo "getTopTaxaData('bar_plot_div', '".$dataJSON."');"; ?>
    </script>
</html>
