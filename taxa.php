<?php
    include('db.php');

    $taxa = $_GET['key'];

    $taxaQuery = "select ".implode(",", array_keys($allTaxaAttributes))." from taxa where Taxa=?;";
//     echo $taxaQuery."<br/>".$taxa."<br/>";
    $abundanceQuery = "select SubGroup, BioProject, Abundance from abundance where Taxa=? and abundance.SubGroup in (select SubGroup from disease where Grp <> 'Control');";
//     echo $abundanceQuery."<br/>".$taxa."<br/>";

    $conn = connect();

    $taxaStmt = $conn->prepare($taxaQuery);
    $taxaStmt->bind_param("s", $taxa);
//     $taxaStmt->execute();
//     $taxaResult = $taxaStmt->get_result();
//     echo $taxaResult->num_rows." ".$taxaResult->field_count."<br/>";
//     $taxaRows = $taxaResult->fetch_all(MYSQLI_ASSOC);
    $taxaRows = execute_and_fetch_assoc($taxaStmt);
    $taxaStmt->close();

    $abundanceStmt = $conn->prepare($abundanceQuery);
    $abundanceStmt->bind_param("s", $taxa);
//     $abundanceStmt->execute();
//     $abundanceResult = $abundanceStmt->get_result();
//     echo $abundanceResult->num_rows." ".$abundanceResult->field_count."<br/>";
//     $abundanceRows = $abundanceResult->fetch_all(MYSQLI_ASSOC);
    $abundanceRows = execute_and_fetch_assoc($abundanceStmt);
    $abundanceStmt->close();

    $abundanceData = array(
        "subgroup" => array(),
        "bioproject" => array(),
        "abundances" => array()
    );
    foreach ($abundanceRows as $row) {
        array_push($abundanceData["subgroup"], $row["SubGroup"]);
        array_push($abundanceData["bioproject"], $row["BioProject"]);
        array_push($abundanceData["abundances"], $row["Abundance"]);
    }
    $abundanceDataJSON = json_encode($abundanceData);

    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Taxa - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_taxa_box.js"></script>
        <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script>
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
                if (count($taxaRows) < 1) {
                    echo "<center><p>Error !!! Taxa: ".$taxa." does not exist in the database.</p></center>";
                } else {
                    echo "<h3 style=\"margin:0; text-align:center;\">Taxa: ".$taxa."</h3>";
            ?>
                    <table class="details" border="1">
                    <tr><th>Attribute</th><th>Value</th></tr>
            <?php
                        foreach($taxaRows as $row){
                            foreach ($allTaxaAttributes as $name=>$fname) {
                                if ($name !== "Taxa") {
                                    echo "<tr>";
                                    echo "<td style=\"width:40%;\">".$fname."</td>";
                                    if ($name === "NCBITaxaID" && $row[$name] != "NA")
                                        echo "<td style=\"width:60%;\"><a style=\"color:#003325;\" target=\"_blank\" href=\"https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?mode=Info&id=".$row[$name]."\">".$row[$name]." <img src=\"resource/redirect-icon.png\" height=\"14pt\" width=\"auto\" /></a></td>";
                                    else
                                        echo "<td style=\"width:60%;\">".$row[$name]."</td>";
                                    echo "</tr>";
                                }
                            }
                        }
            ?>
                    </table>
                    <p style="margin-bottom:0; font-weight:bold;">Distribution of taxa across subgroups</p>
                    <div id="download_div_taxa_distribution" style="width:100%; text-align:center; display:none;">
                        <a id="download_button_taxa_distribution" download="taxa_distribution_across_subgroup_figure_data.csv">
                            <button type="button" style="margin:2px;">Download figure data</button>
                        </a>
                    </div>
                    <div id="box_plot_div" style="width:100%;"></div>
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
        <?php echo "plotBox('box_plot_div', '".$abundanceDataJSON."');"; ?>
    </script>
</html>
