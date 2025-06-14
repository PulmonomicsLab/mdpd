<?php
    include('db.php');
    
    $bioprojectID = (isset($_GET['key'])) ? $_GET['key'] : "";
    
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

    $file = fopen("input/bioproject_page_buttons.tsv","r");
    $taxonomicButtons = array();
    $discriminantButtons = array();
    $multivariateButtons = array();
    $networkButtons = array();
    $analysesErrorMessages = array();
    while (($row = fgetcsv($file, 500, "\t")) !== FALSE) {
        if($row[0] == $bioprojectID) {
            if($row[3] == "Yes")
                array_push($taxonomicButtons, array($row[2], $row[1], ""));
            else
                array_push($taxonomicButtons, array($row[2], $row[1], "disabled"));
            if($row[4] == "Yes")
                array_push($discriminantButtons, array($row[2], $row[1], ""));
            else
                array_push($discriminantButtons, array($row[2], $row[1], "disabled"));
            if($row[5] == "Yes")
                array_push($multivariateButtons, array($row[2], $row[1], ""));
            else
                array_push($multivariateButtons, array($row[2], $row[1], "disabled"));
            if($row[6] == "Yes")
                array_push($networkButtons, array($row[2], $row[1], ""));
            else
                array_push($networkButtons, array($row[2], $row[1], "disabled"));
            if($row[7] != "")
                array_push($analysesErrorMessages, $row[7]);
        }
    }
//     echo implode(",", $taxonomicButtons)."<br/>";
//     echo implode(",", $discriminantButtons)."<br/>";
    fclose($file);
    $confounder_json = json_decode(file_get_contents("input/bioproject_confounder_list.json"), true);
    $covariate_possible = array_key_exists($bioprojectID, $confounder_json["confounders"]);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>BioProject - MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
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
                if (count($bioprojectRows) < 1) {
                    echo "<p style=\"text-align:center;\">Error !!! BioProject ID: ".$bioprojectID." does not exist in the database.</p>";
                } else {
                    echo "<h3 style=\"margin:0; text-align:center;\">BioProject ID: ".$bioprojectRows[0]["BioProject"]."</h3>";
                    echo "<h4 style=\"margin:0 0 5px 0; text-align:center;\"><a style=\"color:#003325;\" target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/bioproject/?term=".$bioprojectID."\">https://www.ncbi.nlm.nih.gov/bioproject/?term=".$bioprojectID." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></h4>";
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
                                    echo "<td style=\"width:60%\">".str_replace(";", "; ", $row[$name])."</td>";
                                echo "</tr>";
                            }
                        }
                    }
                    echo "</table>";

                    echo "<h3 style=\"margin-bottom:5px; text-align:center;\">Analyses</h3>";
                    echo "<table class=\"details\" border=\"1\">";

                    echo "<tr><th style=\"width:30%;\">Taxonomic profile</th>";
                    echo "<td>";
                    foreach($taxonomicButtons as $button){
                        $is = $button[0];
                        $at = $button[1];
                        $disable = $button[2];
                        if($disable == "disabled")
                            echo "<button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button>";
                        else
                            echo "<a target=\"_blank\" href=\"bioproject_taxonomic_profile.php?key=".urlencode($bioprojectID)."&at=".urlencode($at)."&is=".urlencode($is)."\"><button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button></a>";
                    }
                    echo "</td></tr>";

                    echo "<tr><th style=\"width:30%;\">Discriminant analysis</th>";
                    echo "<td>";
                    foreach($discriminantButtons as $button){
                        $is = $button[0];
                        $at = $button[1];
                        $disable = $button[2];
                        if($disable == "disabled")
                            echo "<button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button>";
                        else
                            echo "<a target=\"_blank\" href=\"lda.php?key=".urlencode($bioprojectID)."&at=".urlencode($at)."&is=".urlencode($is)."\"><button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button></a>";
                    }
                    echo "</td></tr>";

                    if($covariate_possible) {
                        echo "<tr><th style=\"width:30%;\">Multivariate association analysis</th>";
                        echo "<td>";
                        foreach($multivariateButtons as $button){
                            $is = $button[0];
                            $at = $button[1];
                            $disable = $button[2];
                            if($disable == "disabled")
                                echo "<button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button>";
                            else
                                echo "<a target=\"_blank\" href=\"bioproject_covariate_analysis.php?key=".urlencode($bioprojectID)."&at=".urlencode($at)."&is=".urlencode($is)."\"><button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button></a>";
                        }
                        echo "</td></tr>";
                    }

                    echo "<tr><th style=\"width:30%;\">Microbial co-occurrence analysis</th>";
                    echo "<td>";
                    foreach($networkButtons as $button){
                        $is = $button[0];
                        $at = $button[1];
                        $disable = $button[2];
                        if($disable == "disabled")
                            echo "<button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button>";
                        else
                            echo "<a target=\"_blank\" href=\"bioproject_network_analysis.php?key=".urlencode($bioprojectID)."&at=".urlencode($at)."&is=".urlencode($is)."\"><button type=\"button\" style=\"margin:3px;\" ".$disable.">".$is." - ".$at."</button></a>";
                    }
                    echo "</td></tr>";

                    echo "</table>";

                    echo "<div style=\"width:80%; margin:0 10% 0 10%; font-size:0.9em;\">".(count($analysesErrorMessages)>0 ? "N.B.- " : "").implode(" ", $analysesErrorMessages)."</div>";

                    echo "<h3 style=\"margin-bottom:5px; text-align:center;\">Download</h3>";
                    echo "<table class=\"details\" border=\"1\">";
                    echo "<tr><th style=\"width:30%;\">Biom file</th><td>";
                    $assayTypes = explode(";", $bioprojectRows[0]["AssayType"]);
                    foreach($assayTypes as $at)
                        echo "<a target=\"_blank\" href=\"resource/public/biom/".$bioprojectID."_".$at."_ps_object.rds\"><button type=\"button\" style=\"margin:3px;\">Download - ".$at."</button></a>";
                    echo "</td></tr>";
                    echo "</table>";
            ?>
                    <h3 style="margin-bottom:0px; text-align:center;">Summary of runs</h3>
                    <p style="margin-top:2px;margin-bottom:5px;">Total number of processed runs found in the database for this BioProject ID = <?php echo count($runRows);?></p>
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
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        displayFirstPage('result_display');
    </script>
</html>
