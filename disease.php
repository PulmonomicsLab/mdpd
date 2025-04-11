<?php
    include('db.php');

    $disease = (isset($_GET['key'])) ? urldecode($_GET['key']) : "";

    $query = "select ".implode(",", $viewBioProjectAttributes)." from bioproject where BioProject in (select BioProject from run where Grp=?) order by BioProject;";

    $conn = connect();

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $disease);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/><br/>";
//     $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($stmt);
    $rowsJSON = json_encode($rows);
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
        <script>
            <?php
                echo "dataJSON = '".$rowsJSON."';";
                echo "disease = '".$disease."';";
            ?>
            rows = null;
            healthyControlFilterRows = null;
            assayTypeFilterRows = null;
            libLayoutFilterRows = null;
            
            function initializeDiseaseWiseData() {
                rows = [];
                healthyControlFilterRows = new Set();
                assayTypeFilterRows = new Map();
                assayTypeFilterRows.set('Amplicon-16S', new Set());
                assayTypeFilterRows.set('Amplicon-ITS', new Set());
                assayTypeFilterRows.set('WMS', new Set());
                libLayoutFilterRows = new Map();
                libLayoutFilterRows.set('PAIRED', new Set());
                libLayoutFilterRows.set('SINGLE', new Set());
                if (dataJSON.length > 0) {
                    rows = JSON.parse(dataJSON);
                    for(var i=0; i<rows.length; ++i) {
                        var groups = rows[i].Grp.split(';');
                        for(var j=0; j<groups.length; ++j)
                            if(groups[j] == 'Healthy' || groups[j] == 'Control')
                                healthyControlFilterRows.add(i);
                        var assayTypes = rows[i].AssayType.split(';');
                        for(var j=0; j<assayTypes.length; ++j)
                            assayTypeFilterRows.get(assayTypes[j]).add(i);
                        var libLayouts = rows[i].LibraryLayout.split(';');
                        for(var j=0; j<libLayouts.length; ++j)
                            libLayoutFilterRows.get(libLayouts[j]).add(i);
                    }
                }
            }
            
            function getDiseaseWiseResultHTML(filterRows) {
                var s = '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Processed Runs</th><th>Body site</th><th>Assay Type</th><th>Library Layout</th></tr>';
                for(i of filterRows.values()) {
                    s += '<tr>';
                    s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">' + rows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
                    s += '<td>' + rows[i].Grp.replace(/;/g, '; ') + '</td>';
                    s += '<td>' + rows[i].SubGroup.replace(/;/g, '; ') + '</td>';
                    s += '<td>' + rows[i].ProcessedRuns + '</td>';
                    s += '<td>' + rows[i].Biome.replace(/;/g, '; ') + '</td>';
                    s += '<td>' + rows[i].AssayType.replace(/;/g, '; ') + '</td>';
                    s += '<td>' + rows[i].LibraryLayout.replace(/;/g, '; ') + '</td>';
                    s += '</tr>';
                }
                s += '</table>';

                return s;
            }
            
            function getFilterRows(healthyFilter, assayTypeFilter, libLayoutFilter) {
                var filterRows = new Set([...Array(rows.length).keys()]);
                if(healthyFilter)
                    for(var i=0; i<rows.length; ++i)
                        if(!healthyControlFilterRows.has(i))
                            filterRows.delete(i);
                if(assayTypeFilter != 'Any')
                    for(var i=0; i<rows.length; ++i)
                        if (!assayTypeFilterRows.get(assayTypeFilter).has(i))
                            filterRows.delete(i);
                if(libLayoutFilter != 'Any')
                    for(var i=0; i<rows.length; ++i)
                        if(!libLayoutFilterRows.get(libLayoutFilter).has(i))
                            filterRows.delete(i);
                return filterRows;
            }

            function filter_diseases(resultDivId) {
                var resultElement = document.getElementById(resultDivId);
                var healthyFilter = (document.getElementById('filter_cb') != null && document.getElementById('filter_cb').checked == true);
                var assayTypeFilter = (document.getElementById('filter_at_Any') != null) ? document.querySelector('input[name="filter_at"]:checked').value : 'Any';
                var libLayoutFilter = (document.getElementById('filter_ll_Any') != null) ? document.querySelector('input[name="filter_ll"]:checked').value : 'Any';
                var filterRows = getFilterRows(healthyFilter, assayTypeFilter, libLayoutFilter);
                var diseaseResultHTML = getDiseaseWiseResultHTML(filterRows);
                var resultCountString = '<p style="margin:2px;text-align:center;">Total number of BioProjects found in the database for Group = "' + disease + '" with provided filters : ' + filterRows.size + '</p>';
                if(filterRows.size <= 0)
                    document.getElementById(resultDivId).innerHTML = resultCountString;
                else
                    document.getElementById(resultDivId).innerHTML = resultCountString + diseaseResultHTML;
                document.getElementById('filter_cb').checked = (healthyFilter) ? true : false;
                document.getElementById('filter_at_'+assayTypeFilter).checked = true;
                document.getElementById('filter_ll_'+libLayoutFilter).checked = true;
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
            <center><h3>Group - <?php echo $disease; ?></h3></center>
            <?php
                if (count($rows) > 0) {
            ?>
                    <table style="width:96%; margin:10px 2% 10px 2%; background-color:#ffe799; border-collapse:collapse;">
                        <tr>
                            <td style="border:1px solid black; padding-left:10px;">
                                Assay Types: 
                                <input type="radio" style="margin-left:5px;" id="filter_at_Any" name="filter_at" value="Any" onclick="filter_diseases('result_display')" checked />
                                <label for="filter_at_Any"><b>Any</b></label>
                                <input type="radio" style="margin-left:5px;" id="filter_at_Amplicon-16S" name="filter_at" value="Amplicon-16S" onclick="filter_diseases('result_display')" />
                                <label for="filter_at_Amplicon-16S"><b>Amplicon-16S</b></label>
                                <input type="radio" style="margin-left:5px;" id="filter_at_Amplicon-ITS" name="filter_at" value="Amplicon-ITS" onclick="filter_diseases('result_display')" />
                                <label for="filter_at_Amplicon-ITS"><b>Amplicon-ITS</b></label>
                                <input type="radio" style="margin-left:5px;" id="filter_at_WMS" name="filter_at" value="WMS" onclick="filter_diseases('result_display')" />
                                <label for="filter_at_WMS"><b>WMS</b></label>
                            </td>
                            <td style="border:1px solid black; padding-left:10px;">
                                Library Layouts:
                                <input type="radio" style="margin-left:5px;" id="filter_ll_Any" name="filter_ll" value="Any" onclick="filter_diseases('result_display')" checked />
                                <label for="filter_ll_Any"><b>Any</b></label>
                                <input type="radio" style="margin-left:5px;" id="filter_ll_PAIRED" name="filter_ll" value="PAIRED" onclick="filter_diseases('result_display')" />
                                <label for="filter_ll_PAIRED"><b>PAIRED</b></label>
                                <input type="radio" style="margin-left:5px;" id="filter_ll_SINGLE" name="filter_ll" value="SINGLE" onclick="filter_diseases('result_display')" />
                                <label for="filter_ll_SINGLE"><b>SINGLE</b></label>
                            </td>
                            <td style="border:1px solid black; padding-left:10px;">
                                <input type="checkbox" id="filter_cb" onclick="filter_diseases('result_display')" />
                                <label for="filter_cb">&nbsp;<b>BioProjects with Healthy/Control</b></label>
                            </td>
                        </tr>
                    </table>
            <?php
                }
            ?>
            <div id="result_display"></div>
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        initializeDiseaseWiseData();
        filter_diseases('result_display');
    </script>
</html>
