<?php
    include('db.php');

    $query = "select * from data_submission;";
//     echo $query."<br/>";

    $conn = connect();

    $stmt = $conn->prepare($query);
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
        <title>MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
<!--         <script type = "text/javascript" src = "js/analysis.js"></script> -->
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
                    <td class="nav"><a href="submission.php" class="side_nav">Submit data</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left" id="section_left">
        </div>-->

        <div class = "section_middle" style="font-size:1.2em;">
            <br/>
            <?php
                if (count($rows) > 0) {
            ?>
                    <table class="summary">
                        <tr>
                            <th>Submission ID</th>
                            <th>BioProject ID</th>
                            <th>Disease</th>
                            <th>PMID/DOI</th>
                            <th>Country</th>
                            <th>Email</th>
                            <th>IS/BS</th>
                            <th>Link</th>
                        </tr>
            <?php
                    foreach ($rows as $row) {
                        echo "<tr><td>".$row["TKey"]."</td><td>".$row["BioProject"]."</td><td>".$row["Grp"]."</td><td>".$row["PMID"]."</td><td>".$row["Country"]."</td><td>".$row["Email"]."</td><td>".$row["IsolationSourceBiome"]."</td><td>".$row["Link"]."</td></tr>";
                    }
            ?>
                    </table>
            <?php
                }
            ?>
        </div>
        <div style="clear:both">
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
