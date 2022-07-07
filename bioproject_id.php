<?php
    include('db.php');
    
    $bioprojectID = $_GET['key'];
    
    $bioprojectQuery = "select ".implode(",", array_keys($allBioProjectAttributes))." from bioproject where BioProject=?";
//     echo $bioprojectQuery."<br/>".$bioprojectID."<br/><br/>";
    
//     $runQuery = "select ".implode(",", $viewAttributes)." from run,bioproject where (run.BioProject=bioproject.BioProject)AND(run.BioProject=?);";
    $runQuery = "select ".implode(",", $viewAttributes)." from ((run inner join bioproject on run.BioProject=bioproject.BioProject) inner join disease on run.SubGroup=disease.SubGroup) where run.BioProject=?;";
//     echo $runQuery."<br/>".$bioprojectID."<br/><br/>";

    $conn = connect();
    
    $bioprojectStmt = $conn->prepare($bioprojectQuery);
    $bioprojectStmt->bind_param("s", $bioprojectID);
    $bioprojectStmt->execute();
    $bioprojectResult = $bioprojectStmt->get_result();
//     echo $bioprojectResult->num_rows." ".$bioprojectResult->field_count."<br/><br/>";
    $bioprojectStmt->close();
    
    $runStmt = $conn->prepare($runQuery);
    $runStmt->bind_param("s", $bioprojectID);
    $runStmt->execute();
    $runResult = $runStmt->get_result();
//     echo $runResult->num_rows." ".$runResult->field_count."<br/><br/>";
    $runRows = $runResult->fetch_all(MYSQLI_ASSOC);
    $runRowsJSON = json_encode($runRows);
    $runStmt->close();
    
    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
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
                    <td class="nav"><a href="advance_search.html" class="side_nav">Search</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <?php
                if ($bioprojectResult->num_rows < 1) {
                    echo "<center><p>Error !!! BioProject ID: ".$bioprojectID." does not exist in the database.</p></center>";
                } else {
                    echo "<center><h3>BioProject ID: ".$bioprojectID."</h3></center>";
                    echo "<table class=\"details\" border=\"1\">";
                    echo "<tr><th>Attribute</th><th>Value</th></tr>";
                    while ($row = $bioprojectResult->fetch_assoc()){
                        foreach ($allBioProjectAttributes as $name=>$fname) {
                            if ($name !== "BioProject") {
                                echo "<tr>";
                                echo "<td style=\"width:40%\">".$fname."</td>";
                                if ($name === "BioProject")
                                    echo "<td style=\"width:60%\"><a target=\"_blank\" href=\"bioproject_id.php?key=".$row[$name]."\">".$row[$name]."</a></td>";
                                elseif ($name === "SubGroup") {
                                    $subgroups = explode(";", $row[$name]);
                                    $conn = connect();
                                    $diseaseQuery = "select Grp from disease where SubGroup=?";
                                    $diseaseStmt = $conn->prepare($diseaseQuery);
                                    echo "<td style=\"width:60%\">";
                                    foreach($subgroups as $sg) {
                                        $diseaseStmt->bind_param("s", $sg);
                                        $diseaseStmt->execute();
                                        $disease = $diseaseStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                        echo $disease[0]["Grp"]." &rarr; ".$sg."<br/>";
                                    }
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
                    echo "<td><a target=\"_blank\" href=\"lda.php?key=".$bioprojectID."\"><button type=\"button\">Get LDA plot</button></a></td></tr>";
                    echo "<tr><td>Taxonomic profile (Krona Plot)</td>";
                    echo "<td>";
                    foreach($subgroups as $sg) {
                        echo "<a target=\"_blank\" href=\"krona.php?key=".urlencode($bioprojectID)."&sg=".urlencode($sg)."\"><button type=\"button\">".$sg."</button></a><br/>";
                    }
                    echo "</td></tr>";
                    echo "</table>";
            ?>
                    <!--<br/><center><a target="_blank" href="<?php //echo "krona.php?key=".$bioprojectID; ?>"><button type="button">Expand Krona</button></a></center><br/>
                    <iframe src="<?php //echo "input/Krona/".$bioprojectID.".html"; ?>" style="width:100%; height:800px;float:left;"></iframe>-->
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
        </div>
    </body>
    <script>
        displayFirstPage('result_display');
    </script>
</html>
