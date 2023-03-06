<?php
    include('db.php');
    
    $bioprojectID = $_GET['key'];
    
    $kronaMappings = json_decode(file_get_contents("input/Krona/bioproject_metadata.json"), true);
//     echo implode($kronaMappings, "<br/>");
    $ldaMappings = json_decode(file_get_contents("input/LDA/bioproject_metadata.json"), true);
//     echo implode($ldaMappings, "<br/>");
    
    
    $bioprojectQuery = "select ".implode(",", array_keys($allBioProjectAttributes))." from bioproject where BioProject=?";
//     echo $bioprojectQuery."<br/>".$bioprojectID."<br/><br/>";
    
//     $runQuery = "select ".implode(",", $viewAttributes)." from run,bioproject where (run.BioProject=bioproject.BioProject)AND(run.BioProject=?);";
    $runQuery = "select ".implode(",", $viewAttributes)." from ((run inner join bioproject on run.BioProject=bioproject.BioProject) inner join disease on run.SubGroup=disease.SubGroup) where run.BioProject=?;";
//     echo $runQuery."<br/>".$bioprojectID."<br/><br/>";

    $conn = connect();
    
    $bioprojectStmt = $conn->prepare($bioprojectQuery);
    $bioprojectStmt->bind_param("s", $bioprojectID);
//     $bioprojectStmt->execute();
//     $bioprojectResult = $bioprojectStmt->get_result();
//     echo $bioprojectResult->num_rows." ".$bioprojectResult->field_count."<br/><br/>";
    $bioprojectRows = execute_and_fetch_assoc($bioprojectStmt);
    $bioprojectStmt->close();
    
    $runStmt = $conn->prepare($runQuery);
    $runStmt->bind_param("s", $bioprojectID);
//     $runStmt->execute();
//     $runResult = $runStmt->get_result();
//     echo $runResult->num_rows." ".$runResult->field_count."<br/><br/>";
//     $runRows = $runResult->fetch_all(MYSQLI_ASSOC);
    $runRows = execute_and_fetch_assoc($runStmt);
    $runRowsJSON = json_encode($runRows);
    $runStmt->close();
    
    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BioProject - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/advance_search_result.js"></script>
        <script>
            var dataJSON = '<?php echo $runRowsJSON; ?>';
            initializeData(dataJSON);
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
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <?php
                if (count($bioprojectRows) < 1) {
                    echo "<center><p>Error !!! BioProject ID: ".$bioprojectID." does not exist in the database.</p></center>";
                } else {
                    echo "<center><h3 style=\"margin-bottom:0;\">BioProject ID: ".$bioprojectID."</h3></center>";
                    echo "<center><h4 style=\"margin-top:0;\"><a style=\"color:#003325;\" target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/bioproject/?term=".$bioprojectID."\">https://www.ncbi.nlm.nih.gov/bioproject/?term=".$bioprojectID." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></h4>";
                    echo "<table class=\"details\" border=\"1\">";
                    echo "<tr><th>Attribute</th><th>Value</th></tr>";
                    foreach($bioprojectRows as $row){
                        foreach ($allBioProjectAttributes as $name=>$fname) {
                            if ($name !== "BioProject") {
                                echo "<tr>";
                                echo "<td style=\"width:40%\">".$fname."</td>";
                                if ($name === "PMID") {
                                    if ($row[$name] === null)
                                        echo "<td style=\"width:60%\"></td>";
                                    else
                                        echo "<td style=\"width:60%\"><a style=\"color:#003325;\" target=\"_blank\" href=\"https://pubmed.ncbi.nlm.nih.gov/".$row[$name]."\">".$row[$name]." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></td>";
                                }
                                elseif ($name === "SubGroup") {
                                    $subgroups = explode(";", $row[$name]);
                                    $conn = connect();
                                    $diseaseQuery = "select Grp from disease where SubGroup=?";
                                    $diseaseStmt = $conn->prepare($diseaseQuery);
                                    echo "<td style=\"width:60%\">";
                                    $groups = array();
                                    foreach($subgroups as $sg) {
                                        $diseaseStmt->bind_param("s", $sg);
//                                         $diseaseStmt->execute();
//                                         $disease = $diseaseStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                        $disease = execute_and_fetch_assoc($diseaseStmt)[0]["Grp"];
                                        array_push($groups, $disease);
                                        echo $disease." &rarr; ".$sg."<br/>";
                                    }
                                    $groups = array_unique($groups);
                                    echo "</td>";
                                    $diseaseStmt->close();
                                    closeConnection($conn);
//                                     echo "<td style=\"width:60%\">".implode("<br/>", $subgroups)."</td>";
                                }
                                else
                                    echo "<td style=\"width:60%\">".$row[$name]."</td>";
                                
//                                 if ($name === "SubGroup")
//                                     $subgroups = explode(";", $row[$name]);
                                echo "</tr>";
                            }
                        }
                    }
                    echo "<tr><td>Linear Discriminant Analysis (LDA)</td>";
                    echo "<td>";
                    $isolationSources = array_keys($ldaMappings[$bioprojectID]);
                    foreach($isolationSources as $is) {
                        $assayTypes = $ldaMappings[$bioprojectID][$is];
                        foreach($assayTypes as $at) {
                            echo "<a target=\"_blank\" href=\"lda.php?type=BIOPROJECT&bioproject=".urlencode($bioprojectID)."&at=".urlencode($at)."&is=".urlencode($is)."\"><button type=\"button\" style=\"margin:3px;\">".$at."-".$is."</button></a>";
                        }
                    }
                    echo "</td></tr>";
                    echo "<tr><td>Taxonomic profile (Krona Plot)</td>";
                    echo "<td>";
                    $diseaseGroups = array_keys($kronaMappings[$bioprojectID]);
                    foreach($diseaseGroups as $sg) {
                        $assayTypes = $kronaMappings[$bioprojectID][$sg];
                        foreach($assayTypes as $at)
                            echo "<a target=\"_blank\" href=\"krona.php?type=BIOPROJECT&bioproject=".urlencode($bioprojectID)."&ds=".urlencode($sg)."&at=".urlencode($at)."\"><button type=\"button\" style=\"margin:3px;\">".$sg."-".$at."</button></a>";
                    }
                    echo "</td></tr>";
                    echo "</table>";
            ?>
                    <p><br/>Total number of runs found in the database for this BioProject ID = <?php echo count($runRows);?></p>
                    <table border="0" style="width:100%; border: 4px solid #392d37;">
                        <tr>
                            <td style="width:25%;">
                                Go to Page:&nbsp;
                                <input id="page_no_top" type="number" size="2" min="1" style="width:50px;" />&nbsp;
                                <button type="button" class="round" onclick="goto_page('result_display', 'page_no_top')" />Go</button>
                            </td>
                            <td style="width:15%;text-align:right;">
                                <button type="button" class="round" onclick="displayFirstPage('result_display')">First</button>&nbsp;&nbsp;
                                <button type="button" class="round" onclick="displayPrevPage('result_display')">Prev</button>&nbsp;&nbsp;
                            </td>
                            <td style="width:20%;">
                                <p id="pages_top" style="text-align:center;"></p>
                            </td>
                            <td style="width:15%;">
                                &nbsp;&nbsp;<button type="button" class="round" onclick="displayNextPage('result_display')">Next</button>&nbsp;&nbsp;
                                <button type="button" class="round" onclick="displayLastPage('result_display')">Last</button>
                            </td>
                            <td style="width:25%;"></td>
                        </tr>
                    </table>
                    <div id="result_display">
                    
                    </div>
                    <table border="0" style="width:100%; border: 4px solid #392d37;">
                        <tr>
                            <td style="width:25%;">
                                Go to Page:&nbsp;
                                <input id="page_no_bottom" type="number" size="2" min="1" style="width:50px;" />&nbsp;
                                <button type="button" class="round" onclick="goto_page('result_display', 'page_no_bottom')" />Go</button>
                            </td>
                            <td style="width:15%;text-align:right;">
                                <button type="button" class="round" onclick="displayFirstPage('result_display')">First</button>&nbsp;&nbsp;
                                <button type="button" class="round" onclick="displayPrevPage('result_display')">Prev</button>&nbsp;&nbsp;
                            </td>
                            <td style="width:20%;">
                                <p id="pages_bottom" style="text-align:center;"></p>
                            </td>
                            <td style="width:15%;">
                                &nbsp;&nbsp;<button type="button" class="round" onclick="displayNextPage('result_display')">Next</button>&nbsp;&nbsp;
                                <button type="button" class="round" onclick="displayLastPage('result_display')">Last</button>
                            </td>
                            <td style="width:25%;"></td>
                        </tr>
                    </table>
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
    <script>
        displayFirstPage('result_display');
    </script>
</html>
