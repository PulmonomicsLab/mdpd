<?php
    include('db.php');

    $sgQuery = "select Grp, SubGroup from disease order by Grp, SubGroup;";
    $isQuery = "select distinct(IsolationSource), Biome from run order by Biome;";

    $conn = connect();

    $sgStmt = $conn->prepare($sgQuery);
//     $sgStmt->execute();
//     $result = $sgStmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/><br/>";
//     $sgRows = $result->fetch_all(MYSQLI_ASSOC);
    $sgRows = execute_and_fetch_assoc($sgStmt);
    $sgStmt->close();

    $isStmt = $conn->prepare($isQuery);
//     $isStmt->execute();
//     $result = $isStmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/><br/>";
//     $isRows = $result->fetch_all(MYSQLI_ASSOC);
    $isRows = execute_and_fetch_assoc($isStmt);
    $isStmt->close();

    closeConnection($conn);

    $diseaseSubGroupMap = array();
    foreach ($sgRows as $row) {
        if(array_key_exists($row["Grp"], $diseaseSubGroupMap))
            array_push($diseaseSubGroupMap[$row["Grp"]], $row["SubGroup"]);
        else
            $diseaseSubGroupMap[$row["Grp"]] = array($row["SubGroup"]);
    }
//     foreach ($diseaseSubGroupMap as $d=>$sgs)
//         echo $d."=>[".implode(",", $sgs)."]<br/><br/>";

    $biomeIsolationSourceMap = array();
    foreach ($isRows as $row) {
        if(array_key_exists($row["Biome"], $biomeIsolationSourceMap))
            array_push($biomeIsolationSourceMap[$row["Biome"]], $row["IsolationSource"]);
        else
            $biomeIsolationSourceMap[$row["Biome"]] = array($row["IsolationSource"]);
    }
//     foreach ($biomeIsolationSourceMap as $b=>$iss)
//         echo $b."=>[".implode(",", $iss)."]<br/><br/>";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Browse - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/browse.js"></script>
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
                    <td class="nav"><a href="#" class="active">Browse</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="about.php" class="side_nav">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <div class = "section_left" id="section_left">
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-1" class="browse_side_nav">1. Disease wise BioProjects</a></div>
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-2" class="browse_side_nav">2. Isolation source wise BioProjects</a></div>
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-2" class="browse_side_nav">3. Disease subgroup wise BioProjects</a></div>
        </div>

        <script>
            window.onscroll = function() {makeSticky()};
            var header = document.getElementById("section_left");
            var sticky = header.offsetTop;
            function makeSticky() {
                if (window.pageYOffset > sticky) {
                    header.classList.add("sticky");
                } else {
                    header.classList.remove("sticky");
                }
            }
        </script>

        <div class = "section_middle" style="width:72%;">
            <div class="browse-heading" id="sec-1">1. Disease wise BioProjects</div>
            <div class="button-group">
            <?php
                foreach(array_keys($diseaseSubGroupMap) as $disease)
                    echo "<button type=\"button\" onclick=\"getBioProjects('".$disease."', 'disease-wise-results')\">".$disease."</button>";
            ?>
            </div>
            <div class="browse-result" id="disease-wise-results">foo</div>

            <div class="browse-heading" id="sec-2">2. Isolation source wise BioProjects</div>
            <table class="browse-summary">
                <tr>
                    <th>Biome</th>
                    <th>Isolation source</th>
                </tr>
                <?php
                    $tableRowClass = "odd";
                    foreach($biomeIsolationSourceMap as $biome=>$isolationSources) {
                        $tableRowClass = ($tableRowClass == "even") ? "odd" : "even";
                        echo "<tr>";
                        echo "<td class=\"row_heading\">".$biome."</td>";
                        echo "<td class=\"".$tableRowClass."\">";
                        echo "<div class=\"button-group\">";
                        foreach($isolationSources as $is)
                            echo "<button type=\"button\" style=\"width:auto;float:left;\" onclick=\"getIsolationSourceBioProjects('".$is."', 'is-wise-results')\">".$is."</button>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            <div class="browse-result" id="is-wise-results">foo</div>

            <div class="browse-heading" id="sec-2">3. Disease subgroup wise BioProjects</div>
            <table class="browse-summary">
                <?php
                    $tableRowClass = "odd";
                    foreach($diseaseSubGroupMap as $disease=>$subgroups) {
                        $tableRowClass = ($tableRowClass == "even") ? "odd" : "even";
                        echo "<tr>";
                        echo "<td class=\"row_heading\" style=\"width:25%;\">".$disease."</td>";
                        echo "<td class=\"".$tableRowClass."\" style=\"text-align:left;\">";
                        for($i=0; $i<count($subgroups); ++$i)
                            if ($i == count($subgroups) - 1)
                                echo "<a style=\"color:#003325;\" href=\"#\" onclick=\"getSubgroupBioProjects('".$subgroups[$i]."', 'subgroup-wise-results')\">".$subgroups[$i]."</a>";
                            else
                                echo "<a style=\"color:#003325;\" href=\"#\" onclick=\"getSubgroupBioProjects('".$subgroups[$i]."', 'subgroup-wise-results')\">".$subgroups[$i]."</a>; ";
                        echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            <div class="browse-result" id="subgroup-wise-results">foo</div>
            <div id="subgroup-wise-results-dummy"></div>

        </div>
        <div style="clear:both">
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
