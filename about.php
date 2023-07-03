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
        <style>
            .intro{
                width:80%;
                height:auto;
                margin:0 10% 0 10%;
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
            <p class="intro">
                Microbiome Database of Pulmonary Diseases (MDPD) contains a
                total of 5970 runs compiled from 64 BioProjects. A brief
                summary of these BioProjects along with their external links
                to the NCBI BioProject is given as follows:
            </p><br/>

            <?php
                echo "<table class=\"details\" border=\"1\">";
                echo "<tr><th>BioProject ID</th><th>Run Count</th><th>Group</th><th>Isolation Source</th><th>Biome</th><th>Assay Type</th><th>NCBI BioProject Links</th></tr>";
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
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
