<?php
    include('db.php');

    $disease = urldecode($_GET['key']);

    $query = "select ".implode(",", $viewBioProjectAttributes)." from bioproject where Grp like ? order by BioProject;";

    $conn = connect();

    $stmt = $conn->prepare($query);
    $param = "%".$disease."%";
    $stmt->bind_param("s", $param);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/><br/>";
//     $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($stmt);
    $stmt->close();
    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Disease - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/advance_search_result.js"></script>
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
                if (count($rows) < 1)
                    echo "<br/><center>No entries found in the database for Disease = \"".$disease."\".</center>";
                else {
                    echo "<center><h3>Disease - ".$disease."</h3></center>";
                    echo "<center><p>Number of BioProjects found in database = <b>".count($rows)."</b></p></center>";
            ?>
                    <div id="result_display">
            <?php
                        echo "<table class=\"browse-result-summary\" border=\"1\"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Assay Type</th><th>Library Layout</th></tr>";
                        for ($i=0; $i<count($rows); ++$i) {
                            echo "<tr>";
                            echo "<td><a style=\"color:#003325;\" target=\"_blank\" href=\"bioproject_id.php?key=".$rows[$i]["BioProject"]."\">".$rows[$i]["BioProject"]." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></td>";
                            echo "<td>".$rows[$i]["Grp"]."</td>";
                            echo "<td>".$rows[$i]["SubGroup"]."</td>";
                            echo "<td>".$rows[$i]["TotalRuns"]."</td>";
                            echo "<td>".$rows[$i]["ProcessedRuns"]."</td>";
                            echo "<td>".$rows[$i]["Biome"]."</td>";
                            echo "<td>".$rows[$i]["AssayType"]."</td>";
                            echo "<td>".$rows[$i]["LibraryLayout"]."</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
            ?>
                    </div>
            <?php
                }
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
