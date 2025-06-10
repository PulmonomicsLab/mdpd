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
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
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
                    echo "<center><p>Error !!! Taxon: ".$taxa." does not exist in the database.</p></center>";
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

                    echo "<h3 style=\"margin:0; text-align:center;\">Taxon: ".$taxaRows[0]["Taxa"]."</h3>";
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
                    <tr>
                    <td colspan="2" style="font-size:0.8em; text-align:left; background-color:#ffffff;">
                        <b>Abbreviations of evidences:</b>
                        <i>exp</i> - Experiment,
                        <i>igc</i> - Inferred from genomic context,
                        <i>tas</i> - Traceable author statement,
                        <i>nas</i> - Non-traceable author statement,
                        <i>ibd</i> - Inferred from biological aspect of descendant,
                        <i>asr</i> - Ancestral state reconstruction.
                    </td>
                    </tr>
                    </table>

                    <p style="margin-bottom:0; font-size:1.2em; font-weight:bold;">A. Distribution of taxon across body sites</p>
                    <div id="download_div_taxa_distribution_biome" style="width:100%; text-align:center; display:none;">
                        <a id="download_button_taxa_distribution_biome" download="taxa_distribution_across_biome_figure_data.csv">
                            <button type="button" style="margin:2px;">Download figure data</button>
                        </a>
                    </div>
                    <div id="biome_box_plot_div" style="width:100%;"></div>
                    <p style="font-size: 0.9em; margin-top:5px;">
                        Interactive <b>box plot</b> shows the abundance distribution
                        of the taxon across 10 human body sites. <b>Hover mouse</b>
                        on a box to highlight the mean, median, maximum, minimum and
                        inter quartile range. The plot can be downloaded as SVG by
                        clicking on the <b>"
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                        </svg>
                        "</b> button in the figure menu bar at the top right corner.
                        The data can be downloaded using the <b>"Download figure data"</b>
                        button.
                    </p>

                    <p style="margin-bottom:0; font-size:1.2em; font-weight:bold;">B. Distribution of taxon across subgroups</p>
                    <div id="download_div_taxa_distribution_subgroup" style="width:100%; text-align:center; display:none;">
                        <a id="download_button_taxa_distribution_subgroup" download="taxa_distribution_across_subgroup_figure_data.csv">
                            <button type="button" style="margin:2px;">Download figure data</button>
                        </a>
                    </div>
                    <div id="subgroup_box_plot_div" style="width:100%;"></div>
                    <p style="font-size: 0.9em; margin-top:5px;">
                        Interactive <b>box plot</b> shows the abundance distribution
                        of the taxon across different subgroups. <b>Hover mouse</b>
                        on a box to highlight the mean, median, maximum, minimum and
                        inter quartile range. The plot can be downloaded as SVG by
                        clicking on the <b>"
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                        </svg>
                        "</b> button in the figure menu bar at the top right corner.
                        The data can be downloaded using the <b>"Download figure data"</b>
                        button.
                    </p>
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
