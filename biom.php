<?php
    include("db.php");

    function getISPlaceholder($isolationSources) {
        return str_repeat("?,", count($isolationSources) - 1) . "?";
    }

    function getParamString($isolationSources) {
        return "s" . str_repeat("s", count($isolationSources));
    }

    function getValues($biom, $isolationSources) {
        $values = array($biom);
        foreach ($isolationSources as $is)
            array_push($values, $is);
        return $values;
    }

    function refValues($arr){
        $refs = array();
        for($i=0; $i<count($arr); ++$i)
            $refs[$i] = &$arr[$i];
        return $refs;
    }

    $attributes = array_merge($viewBioProjectAttributes, array("IsolationSource"));

    $conn = connect();

    if(isset($_POST["is"])) {
        $biom = urldecode($_POST["key"]);
        $selectedIsolationSources = json_decode($_POST["is"]);
        $bioprojectQuery = "select ".implode(",", $attributes)." from bioproject where BioProject in (select BioProject from run where Biome=? and IsolationSource in (".getISPlaceholder($selectedIsolationSources).") order by BioProject);";
        $paramString = getParamString($selectedIsolationSources);
        $values = getValues($biom, $selectedIsolationSources);
        $stmt = $conn->prepare($bioprojectQuery);
        array_unshift($values, $paramString);
        call_user_func_array(
            array($stmt, "bind_param"),
            refValues($values)
        );
        $stmt->execute();
        $rows = execute_and_fetch_assoc($stmt);
        $rowsJSON = json_encode($rows);
        $stmt->close();
        echo $rowsJSON;
        exit();
    }

    $biom = urldecode($_GET["key"]);
    $bioprojectQuery = "select ".implode(",", $attributes)." from bioproject where Biome like ?;";
    $stmt = $conn->prepare($bioprojectQuery);
    $param = "%".$biom."%";
    $stmt->bind_param("s", $param);
    $rows = execute_and_fetch_assoc($stmt);
    $stmt->close();

    $isQuery = "select distinct(IsolationSource) from run where Biome=? order by IsolationSource;";
    $stmt = $conn->prepare($isQuery);
    $stmt->bind_param("s", $biom);
    $isRows = execute_and_fetch_assoc($stmt);
    $isolationSources = array();
    foreach($isRows as $row)
        array_push($isolationSources, $row["IsolationSource"]);
    $stmt->close();

    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Biom - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/browse.js"></script>
        <script>
            function reload_data(div_id) {
                var isolationSources = new Array();
                for (var isElement of document.querySelectorAll('.filter_is:checked'))
                    isolationSources.push(isElement.value);
                var httpReq = new XMLHttpRequest();
                httpReq.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var rows = JSON.parse(this.responseText);
                        var s = '<center><p>Selected isolation sources: [' + isolationSources.toString() + ']</p></center>';
                        s += '<center><p>Number of BioProjects found in database = <b>' + rows.length + '</b></p></center>';
                        s += '<table class="browse-result-summary" border="1"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Isolation Source</th><th>Assay Type</th><th>Library Layout</th></tr>';
                        for (var i=0; i<rows.length; ++i) {
                            s += '<tr>';
                            s += '<td><a style="color:#003325;" target="_blank" href="bioproject_id.php?key=' + rows[i].BioProject + '">' + rows[i].BioProject + ' <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>';
                            s += '<td>' + rows[i].Grp + '</td>';
                            s += '<td>' + rows[i].SubGroup + '</td>';
                            s += '<td>' + rows[i].TotalRuns + '</td>';
                            s += '<td>' + rows[i].ProcessedRuns + '</td>';
                            s += '<td>' + rows[i].Biome + '</td>';
                            s += '<td>' + rows[i].IsolationSource + '</td>';
                            s += '<td>' + rows[i].AssayType + '</td>';
                            s += '<td>' + rows[i].LibraryLayout + '</td>';
                            s += '</tr>';
                        }
                        s += '</table>';
                        document.getElementById(div_id).innerHTML = s;
                    }
                };
                httpReq.open('POST', 'biom.php', true);
                httpReq.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                httpReq.send(
                    'key=' + encodeURIComponent('<?php echo $biom; ?>') +
                    '&' + 'is=' + encodeURIComponent(JSON.stringify(isolationSources))
                );
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
            <?php
                if (count($rows) < 1)
                    echo "<br/><center>No entries found in the database for Biome = \"".$biom."\".</center>";
                else {
                    echo "<center><h3>Biome - ".$biom."</h3></center>";
                    echo "<div style=\"width:100%;\">";
                    echo "<table style=\"width:94%; margin:10px 3% 10px 3%; background-color:#ffe799; text-align:center; border-collapse:collapse;\"><tr><td style=\"border:1px solid black;\">";
                    foreach($isolationSources as $is) {
                        echo "<input type=\"checkbox\" class=\"filter_is\" id=\"filter_cb_".str_replace(" ", "_", $is)."\" value=\"".$is."\" onclick=\"reload_data('result_display')\" checked />";
                        echo "<label for=\"filter_cb_".str_replace(" ", "_", $is)."\" style=\"margin-right:3px;\">".$is."</label>";
                    }
                    echo "</td></tr></table>";
                    echo "</div>";
            ?>
                    <div id="result_display">
            <?php
                        echo "<center><p>Selected isolation sources: [".implode(",", $isolationSources)."]</p></center>";
                        echo "<center><p>Number of BioProjects found in database = <b>".count($rows)."</b></p></center>";
                        echo "<table class=\"browse-result-summary\" border=\"1\"><tr><th>BioProject ID</th><th>Disease</th><th>Sub-group</th><th>Total Runs</th><th>Processed Runs</th><th>Biome</th><th>Isolation Source</th><th>Assay Type</th><th>Library Layout</th></tr>";
                        for ($i=0; $i<count($rows); ++$i) {
                            echo "<tr>";
                            echo "<td><a style=\"color:#003325;\" target=\"_blank\" href=\"bioproject_id.php?key=".$rows[$i]["BioProject"]."\">".$rows[$i]["BioProject"]." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></td>";
                            echo "<td>".$rows[$i]["Grp"]."</td>";
                            echo "<td>".$rows[$i]["SubGroup"]."</td>";
                            echo "<td>".$rows[$i]["TotalRuns"]."</td>";
                            echo "<td>".$rows[$i]["ProcessedRuns"]."</td>";
                            echo "<td>".$rows[$i]["Biome"]."</td>";
                            echo "<td>".$rows[$i]["IsolationSource"]."</td>";
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

