<?php
    include('db.php');

    $taxa = (isset($_GET['key'])) ? $_GET['key'] : "";

    $taxaQuery = "select * from taxa where Taxa=?;";
//     echo $taxaQuery."<br/>".$taxa."<br/>";
    $abundanceSubGroupQuery = "select SubGroup, BioProject, Abundance from abundance_subgroup where Taxa=? and abundance_subgroup.SubGroup in (select SubGroup from disease where Grp <> 'Control');";
    $abundanceBiomeQuery = "select Biome, BioProject, Abundance from abundance_biome where Taxa=? order by Biome;";
//     echo $abundanceSubGroupQuery."<br/>".$abundanceBiomeQuery."<br/>".$taxa."<br/>";

    $conn = connect();

    $taxaStmt = $conn->prepare($taxaQuery);
    $taxaStmt->bind_param("s", $taxa);
//     $taxaStmt->execute();
//     $taxaResult = $taxaStmt->get_result();
//     echo $taxaResult->num_rows." ".$taxaResult->field_count."<br/>";
//     $taxaRows = $taxaResult->fetch_all(MYSQLI_ASSOC);
    $taxaRows = execute_and_fetch_assoc($taxaStmt);
    $taxaStmt->close();

    $abundanceSubGroupStmt = $conn->prepare($abundanceSubGroupQuery);
    $abundanceSubGroupStmt->bind_param("s", $taxa);
    $abundanceSubGroupRows = execute_and_fetch_assoc($abundanceSubGroupStmt);
    $abundanceSubGroupStmt->close();
    $abundanceSubGroupData = array(
        "subgroup" => array(),
        "bioproject" => array(),
        "abundances" => array()
    );
    foreach ($abundanceSubGroupRows as $row) {
        array_push($abundanceSubGroupData["subgroup"], $row["SubGroup"]);
        array_push($abundanceSubGroupData["bioproject"], $row["BioProject"]);
        array_push($abundanceSubGroupData["abundances"], $row["Abundance"]);
    }
    $abundanceSubGroupDataJSON = json_encode($abundanceSubGroupData);

    $abundanceBiomeStmt = $conn->prepare($abundanceBiomeQuery);
    $abundanceBiomeStmt->bind_param("s", $taxa);
    $abundanceBiomeRows = execute_and_fetch_assoc($abundanceBiomeStmt);
    $abundanceBiomeStmt->close();
    $abundanceBiomeData = array(
        "biome" => array(),
        "bioproject" => array(),
        "abundances" => array()
    );
    foreach ($abundanceBiomeRows as $row) {
        array_push($abundanceBiomeData["biome"], $row["Biome"]);
        array_push($abundanceBiomeData["bioproject"], $row["BioProject"]);
        array_push($abundanceBiomeData["abundances"], $row["Abundance"]);
    }
    $abundanceBiomeDataJSON = json_encode($abundanceBiomeData);

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
                    if($taxaRows[0]["Domain"] == "Bacteria")
                        $attributes = $bacteriaTaxaAttributes;
                    elseif ($taxaRows[0]["Domain"] == "Eukaryota")
                        $attributes = $eukaryotaTaxaAttributes;
                    elseif ($taxaRows[0]["Domain"] == "Viruses")
                        $attributes = $virusTaxaAttributes;
                    elseif ($taxaRows[0]["Domain"] == "Archaea")
                        $attributes = $archaeaTaxaAttributes;
                    else
                        $attributes = array();

                    echo "<h3 style=\"margin:0; text-align:center;\">Taxa: ".$taxaRows[0]["Taxa"]."</h3>";
            ?>
                    <table class="details" border="1">
                    <tr><th>Attribute</th><th>Value</th></tr>
            <?php
                        foreach($taxaRows as $row){
                            foreach ($attributes as $name=>$fname) {
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

                    <p style="margin-bottom:0; font-size:1.2em; font-weight:bold;">Distribution of taxa across body sites</p>
                    <div id="download_div_taxa_distribution_biome" style="width:100%; text-align:center; display:none;">
                        <a id="download_button_taxa_distribution_biome" download="taxa_distribution_across_biome_figure_data.csv">
                            <button type="button" style="margin:2px;">Download figure data</button>
                        </a>
                    </div>
                    <div id="biome_box_plot_div" style="width:100%;"></div>

                    <p style="margin-bottom:0; font-size:1.2em; font-weight:bold;">Distribution of taxa across subgroups</p>
                    <div id="download_div_taxa_distribution_subgroup" style="width:100%; text-align:center; display:none;">
                        <a id="download_button_taxa_distribution_subgroup" download="taxa_distribution_across_subgroup_figure_data.csv">
                            <button type="button" style="margin:2px;">Download figure data</button>
                        </a>
                    </div>
                    <div id="subgroup_box_plot_div" style="width:100%;"></div>
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
        <?php echo "plotBoxBiome('biome_box_plot_div', '".$abundanceBiomeDataJSON."');"; ?>
        <?php echo "plotBoxSubGroup('subgroup_box_plot_div', '".$abundanceSubGroupDataJSON."');"; ?>
    </script>
</html>
