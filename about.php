<?php
    include('db.php');

    $bioprojectQuery = "select ".implode(",", $viewBioProjectAttributes)." from bioproject order by Grp, Biome desc, AssayType, IsolationSource, BioProject;";
//     echo $bioprojectQuery." ".$disease;

    $conn = connect();

    $bioprojectStmt = $conn->prepare($bioprojectQuery);
//     $bioprojectStmt->execute();
//     $bioprojectResult = $bioprojectStmt->get_result();
//     echo $bioprojectResult->num_rows." ".$bioprojectResult->field_count."<br/><br/>";
//     $rows = $bioprojectResult->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($bioprojectStmt);
    $bioprojectStmt->close();
    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>About - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <style>
            .intro{
                width:80%;
                height:auto;
                margin:0 10% 0 10%;
                font-size: 1.2em;
            }
        </style>
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
                    <td class="nav"><a href="#" class="active">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->

        <div class = "section_middle">
            <br/>
            <div class="intro" style="font-size:1.5em;" id="sec-1"><b>1. Data</b></div><br/>
            <p class="intro">
                Microbiome Database of Pulmonary Diseases (MDPD) contains a total of
                5970 runs compiled from 64 BioProjects. The <b><i>R script</i></b> for
                performing the <b><i>computational analysis pipeline</i></b> is available
                <a style="color:#003325;" href="#sec-2"><b>here</b></a>. A brief summary
                of the BioProjects along with their external <b><i>hyperlinks to the NCBI
                BioProject</i></b> is given as follows:
            </p><br/>

            <?php
                echo "<table class=\"details\" border=\"1\">";
                echo "<tr><th>BioProject ID</th><th>Run Count</th><th>Group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th><th>NCBI BioProject hyperlinks</th></tr>";
                foreach($rows as $row){
                    echo "<tr>";
                    echo "<td><a style=\"color:#003325;\" target=\"_blank\" href=\"bioproject_id.php?key=".$row["BioProject"]."\">".$row["BioProject"]." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></td>";
                    echo "<td>".$row["RunCount"]."</td>";
                    echo "<td>".str_replace(";", "; ", $row["Grp"])."</td>";
                    echo "<td>".str_replace(";", "; ", $row["IsolationSource"])."</td>";
                    echo "<td>".str_replace(";", "; ", $row["Biome"])."</td>";
                    echo "<td>".str_replace(";", "; ", $row["AssayType"])."</td>";
                    echo "<td><a style=\"color:#003325;\" target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/bioproject/?term=".$row["BioProject"]."\">https://www.ncbi.nlm.nih.gov/bioproject/?term=".$row["BioProject"]." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            ?>
            <br/>
            <div class="intro" style="font-size:1.5em;" id="sec-2"><b>2. R codes of computational analysis pipeline</b></div><br/>
            <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;">
                <a href="R/computational_analysis_pipeline.R"><button type="button" style="margin:2px;">Download R script</button></a>
            </div>
            <div class="intro" style="overflow:auto;border:3px dashed black;background-color:#ecf8ec;padding:10px;">
                <pre>
                    <?php
                        $fname = "R/computational_analysis_pipeline.R";
                        $f = fopen($fname, "r");
                        echo fread($f, filesize($fname));
                        fclose($f);
                    ?>
                </pre>
            </div>
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
