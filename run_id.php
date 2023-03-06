<?php
    include('db.php');
    
    $runID = $_GET['key'];
    
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
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Run - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
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
                    echo "<center><h3 style=\"margin-bottom:0;\">Run ID: ".$runID."</h3></center>";
                    echo "<center><h4 style=\"margin-top:0;\"><a style=\"color:#003325;\" target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/sra/?term=".$runID."\">https://www.ncbi.nlm.nih.gov/sra/?term=".$runID." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></h4>";
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
                }
            ?>
            
        <br/><hr/>
        <p style="font-size:0.9em;text-align:center;">
            &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
            (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
            <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
        </p>
    </body>
</html>
