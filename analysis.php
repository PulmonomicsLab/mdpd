<?php
    include('db.php');

    $diseaseSubgroupMap = array(
        "Acute Respiratory Distress Syndrome (ARDS)" => array(
            "ARDS"
        ),
        "Asthma" => array(
            "Asthma",
            "Asthma Exacerbation",
            "Asthma Recovery",
            "Atopic Asthma",
            "Asthma No Exacerbation",
            "Non Eosinophilic Asthma",
            "Eosinophilic Asthma"
        ),
        "Asthma-COPD Overlap (ACO)" => array(
            "Asthma-COPD Overlap (ACO)"
        ),
        "Bronchiectasis" => array(
            "Bronchiectasis",
            "Bronchiectasis Stable",
            "Bronchiectasis-Idiopathic",
            "Non Cystic Fibrosis Bronchiectasis",
            "Bronchiectasis Exacerbation-Before Treatment",
            "Bronchiectasis Exacerbation-After Treatment"
        ),
        "Bronchiolitis" => array(
            "Bronchiolitis Moderate",
            "Bronchiolitis Severe"
        ),
        "Bronchitis" => array(
            "Persistent Bacterial Bronchitis",
            "Protracted Bacterial Bronchitis"
        ),
        "Chronic Obstructive Pulmonary Disease (COPD)" => array(
            "COPD",
            "COPD Stable",
            "COPD Exacerbation",
            "COPD Infrequent Exacerberator",
            "COPD Frequent Exacerberator",
            "COPD Recovery",
            "COPD GOLD 1",
            "COPD GOLD 2",
            "COPD GOLD 3",
            "COPD GOLD 4"
        ),
        "COPD-Bronchiectasis Association (CBA)" => array(
            "COPD-Bronchiectasis Association (CBA)"
        ),
        "COVID-19" => array(
            "COVID-19",
            "COVID-19 Mild",
            "COVID-19 Severe",
            "COVID-19 Moderate",
            "COVID-19 SARS-CoV-2 Positive",
            "COVID-19 Recovery",
            "COVID-19 Omicron Variant",
            "COVID-19 Asymptomatic"
        ),
        "Cystic Fibrosis" => array(
            "Cystic Fibrosis",
            "Cystic Fibrosis Baseline",
            "Cystic Fibrosis Exacerbation (No Treatment)",
            "Cystic Fibrosis Recovery (within 1 Month After Stopping Antibiotics for Exacerbation)",
            "Cystic Fibrosis Exacerbation",
            "Cystic Fibrosis Exacerbation (Treatment with Antibiotics)",
            "Cystic Fibrosis Severe",
            "Cystic Fibrosis Mild",
            "Cystic Fibrosis No Exacerbation",
            "Cystic Fibrosis Moderate"
        ),
        "Healthy" => array(
            "Healthy",
            "Healthy Non Smoker",
            "Healthy Smoker",
            "Healthy Children",
            "Healthy Adult",
            "Healthy Former Smoker",
        ),
        "Idiopathic Pulmonary Fibrosis (IPF)" => array(
            "IPF",
        ),
        "Interstitial Lung Disease (ILD)" => array(
            "ILD",
            "ILD Exacerbation",
            "Connective Tissue Disease Associated ILD (CTD-ILD)"
        ),
        "Lung Cancer" => array(
            "Lung Cancer",
            "Non Small Cell Lung Cancer (NSCLC)",
            "Adenocarcinoma",
            "Squamous Cell Carcinoma",
            "Small Cell Lung Cancer (SCLC)"
        ),
        "Other Pulmonary Infections" => array(
            "Acute Respiratory Infection",
            "Lower Respiratory Tract Infections (LRTI)",
            "Influenza Virus Infection",
            "Bacterial Infection",
            "Fungal Infection",
            "Metapneumovirus Infection",
            "Respiratory Syncytial Virus (RSV) Infection",
            "Rhinovirus Infection"
        ),
        "Pneumonia" => array(
            "Pneumonia",
            "Ventilator Associated Pneumonia(VAP)",
            "Community Acquired Pneumonia (CAP)",
            "Hospital Acquired Pneumonia (HAP), ARDS",
            "Hospital Acquired Pneumonia (HAP)",
            "Pediatric Pneumonia"
        ),
        "Pneumonitis" => array(
            "Chronic Hypersensitivity Pneumonitis (CHP)",
            "Checkpoint Inhibitor Pneumonitis(CIP)"
        ),
        "Pulmonary Hypertension" => array(
            "Pulmonary Hypertension",
            "Pulmonary Arterial Hypertension (PAH)",
            "Chronic Thromboembolic Pulmonary Hypertension (CTEPH)"
        ),
        "Sarcoidosis" => array(
            "Sarcoidosis"
        ),
        "Tuberculosis" => array(
            "Tuberculosis",
            "Tuberculosis Latently Infected",
            "Mycobacterium tuberculosis",
            "Mycobacterium africanum",
            "Nontuberculous Mycobacteria (NTM)",
            "Tuberculosis Positive",
            "Drug Sensitive Tuberculosis",
            "Multi Drug Resistant Tuberculosis"
        )
    );

    $diseseIsolationSourceMapQuery = "select distinct(disease.Grp) as Grp, IsolationSource from run inner join disease on run.SubGroup=disease.SubGroup where IsolationSource in (select distinct(IsolationSource) from run group by IsolationSource having count(Run)>500) order by Grp, IsolationSource;";
    $conn = connect();
    $diseseIsolationSourceMapStmt = $conn->prepare($diseseIsolationSourceMapQuery);
//     $diseseIsolationSourceMapStmt->execute();
//     $diseseIsolationSourceMapResult = $diseseIsolationSourceMapStmt->get_result();
//     echo $diseseIsolationSourceMapResult->num_rows." ".$diseseIsolationSourceMapResult->field_count."<br/><br/>";
//     $diseseIsolationSourceMapRows = $diseseIsolationSourceMapResult->fetch_all(MYSQLI_ASSOC);
    $diseseIsolationSourceMapRows = execute_and_fetch_assoc($diseseIsolationSourceMapStmt);
    $diseseIsolationSourceMapStmt->close();
    closeConnection($conn);

    $diseseIsolationSourceMap = array();
    foreach($diseseIsolationSourceMapRows as $row) {
        if (array_key_exists($row["Grp"], $diseseIsolationSourceMap))
            array_push($diseseIsolationSourceMap[$row["Grp"]], $row["IsolationSource"]);
        else
            $diseseIsolationSourceMap[$row["Grp"]] = array($row["IsolationSource"]);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Analysis - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/analysis.js"></script>
        <script>
            initializeMaps(<?php echo "'".json_encode($diseaseSubgroupMap)."', '".json_encode($diseseIsolationSourceMap)."'"; ?>)
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
                    <td class="nav"><a href="#" class="active">Analysis</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="about.php" class="side_nav">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <div class = "section_left" id="section_left">
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-1" class="browse_side_nav">1. Taxonomic analysis</a></div>
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-2" class="browse_side_nav">2. Discriminant analysis</a></div>
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
            <div class="browse-heading" id="sec-1">
                1. Taxonomic analysis
            </div>
            <form name="taxonomic_form" method="post" action="dynamic_taxonomic_analysis.php" onsubmit="return validateTaxonomicForm()">
                <table class="browse-summary">
                    <tr>
                        <td class="row_heading" style="width:150px">Group</td>
                        <td class="odd">
                            <?php
                                foreach($diseaseSubgroupMap as $disease=>$subgroup) {
                                    echo "<div style=\"float:left;\">";
                                    echo "<input type=\"radio\" style=\"margin-top:10px;\" id=\"tp_ds_".$disease."\" name=\"taxonomic_ds\" value=\"".$disease."\" onclick=\"updateTPGroupOptions(this.value, 'browse-tp-sg-options', 'tp_bioproject_list')\">";
                                    echo "<label for=\"tp_ds_".$disease."\" style=\"margin:5px 10px 5px 0;\">".$disease."</label>";
                                    echo "</div>";
                                }
                            ?>
                        </td>
                    </tr>
                </table>
                <table class="browse-summary" id="browse-tp-sg-options"></table>
                <div id="browse-tp-other-options-div" style="display:none;">
                    <table class="browse-summary" id="browse-tp-other-options">
                        <tr>
                            <td class="row_heading" style="width:150px;">Assay Type</td>
                            <td class="even">
                                <input type="radio" style="margin-top:10px;float:left;" id="Amplicon-16S" name="taxonomic_at" value="Amplicon-16S" onclick="getTPBioProjects('tp_bioproject_list')" checked>
                                <label for="Amplicon-16S" style="margin:5px 10px 5px 0;float:left;">Amplicon-16S</label>
                                <input type="radio" style="margin-top:10px;float:left;" id="Amplicon-ITS" name="taxonomic_at" value="Amplicon-ITS" onclick="getTPBioProjects('tp_bioproject_list')">
                                <label for="Amplicon-ITS" style="margin:5px 10px 5px 0;float:left;">Amplicon-ITS</label>
                                <input type="radio" style="margin-top:10px;float:left;" id="WMS" name="taxonomic_at" value="WMS" onclick="getTPBioProjects('tp_bioproject_list')">
                                <label for="WMS" style="margin:5px 10px 5px 0;float:left;">WMS</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="row_heading" style="width:150px;">Library Layout</td>
                            <td class="odd">
                                <input type="checkbox" class="taxonomic_lib_class" style="margin-top:10px;float:left;" id="tp_lib_PAIRED" name="taxonomic_lib[]" value="PAIRED" onclick="getTPBioProjects('tp_bioproject_list')" checked>
                                <label for="tp_lib_PAIRED" style="margin:5px 10px 5px 0;float:left;">PAIRED</label>
                                <input type="checkbox" class="taxonomic_lib_class" style="margin-top:10px;float:left;" id="tp_lib_SINGLE" name="taxonomic_lib[]" value="SINGLE" onclick="getTPBioProjects('tp_bioproject_list')">
                                <label for="tp_lib_SINGLE" style="margin:5px 10px 5px 0;float:left;">SINGLE</label>
                            </td>
                        </tr>
                    </table>
                </div>
                <center><input type="submit" style="margin:10px;" name="Submit" value="Submit" /></center>
                <div class="browse-result" id="tp_bioproject_list">foo</div>
            </form>

            <div class="browse-heading" id="sec-2">
                2. Discriminant analysis
            </div>
            <form name="discriminant_form" method="post" action="dynamic_discriminant_analysis.php" onsubmit="return validateDiscriminantForm()">
                <table class="browse-summary">
                    <tr>
                        <td class="row_heading" style="width:150px">Group</td>
                        <td class="odd">
                            Group 1:
                            <select class="full" name="discriminant_ds_1" id="discriminant_ds_1" onchange="updateDAGroupOptions('da_bioproject_list')">
                                <option value="null">Select Group</option>
                                <?php
                                    foreach($diseaseSubgroupMap as $disease=>$subgroup) {
                                        echo "<option value=\"$disease\">".$disease."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td class="odd">
                            Group 2:
                            <select class="full" name="discriminant_ds_2" id="discriminant_ds_2" onchange="updateDAGroupOptions('da_bioproject_list')">
                                <option value="null">Select Group</option>
                                <?php
                                    foreach($diseaseSubgroupMap as $disease=>$subgroup) {
                                        echo "<option value=\"$disease\">".$disease."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div id="browse-da-sg-options-div" style="display:none;">
                    <table class="browse-summary" id="browse-da-sg-options"></table>
                </div>
                <div id="browse-da-other-options-div" style="display:none;">
                    <table class="browse-summary" id="browse-da-other-options_1">
                        <tr>
                            <td class="row_heading" style="width:150px;">Assay Type</td>
                            <td class="even">
                                <input type="radio" style="margin-top:10px;float:left;" id="da_at_Amplicon-16S" name="discriminant_at" value="Amplicon-16S" onclick="getDABioProjects('da_bioproject_list')" checked>
                                <label for="da_at_Amplicon-16S" style="margin:5px 10px 5px 0;float:left;">Amplicon-16S</label>
                                <input type="radio" style="margin-top:10px;float:left;" id="da_at_Amplicon-ITS" name="discriminant_at" value="Amplicon-ITS" onclick="getDABioProjects('da_bioproject_list')">
                                <label for="da_at_Amplicon-ITS" style="margin:5px 10px 5px 0;float:left;">Amplicon-ITS</label>
                                <input type="radio" style="margin-top:10px;float:left;" id="da_at_WMS" name="discriminant_at" value="WMS" onclick="getDABioProjects('da_bioproject_list')">
                                <label for="da_at_WMS" style="margin:5px 10px 5px 0;float:left;">WMS</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="row_heading" style="width:150px;">Library Layout</td>
                            <td class="odd">
                                <input type="checkbox" class="discriminant_lib_class" style="margin-top:10px;float:left;" id="da_lib_PAIRED" name="discriminant_lib[]" value="PAIRED" onclick="getDABioProjects('da_bioproject_list')" checked>
                                <label for="da_lib_PAIRED" style="margin:5px 10px 5px 0;float:left;">PAIRED</label>
                                <input type="checkbox" class="discriminant_lib_class" style="margin-top:10px;float:left;" id="da_lib_SINGLE" name="discriminant_lib[]" value="SINGLE" onclick="getDABioProjects('da_bioproject_list')">
                                <label for="da_lib_SINGLE" style="margin:5px 10px 5px 0;float:left;">SINGLE</label>
                            </td>
                        </tr>
                    </table>
                    <table class="browse-summary" id="browse-da-other-options_2">
                        <tr>
                            <td class="row_heading" style="width:150px;">Method</td>
                            <td class="even">
                                <select class="full" id="method" name="method" required>
                                    <option value="lefse_none" selected>LEfSe (without FDR p-value adjustment)</option>
                                    <option value="lefse_fdr">LEfSe (with FDR p-value adjustment)</option>
                                    <option value="edgeR_fdr">edgeR (with FDR p-value adjustment)</option>
                                </select>
                            </td>
                            <td class="row_heading">P-value <br/><font style="font-size:0.75em;">(only for <i>LEfSe</i>)</font></td>
                            <td class="even">
                                <select class="full" id="alpha" name="alpha" required>
                                    <option value="0.1" selected>0.1</option>
                                    <option value="0.05">0.05</option>
                                    <option value="0.01">0.01</option>
                                </select>
                            </td>
                            <td class="row_heading">Filter threshold</td>
                            <td class="even">
                                <select class="full" id="filter_thres" name="filter_thres" required>
                                    <option value="0.01" selected>0.010</option>
                                    <option value="0.025">0.025</option>
                                    <option value="0.05">0.050</option>
                                </select>
                            </td>
                            <td class="row_heading">Taxa level</td>
                            <td class="even">
                                <select class="full" id="taxa_level" name="taxa_level" required>
                                    <option value="Genus">Genus</option>
                                    <option value="Family">Family</option>
                                    <option value="Order">Order</option>
                                </select>
                            </td>
                            <td class="row_heading">Cut-off value</td>
                            <td class="even">
                                <input type="number" class="full" id="threshold" name="threshold" min="1" step="0.1" value="2" required />
                            </td>
                        </tr>
                    </table>
                </div>
                <center><input type="submit" style="margin:10px;" name="Submit" value="Submit" /></center>
                <div class="browse-result" id="da_bioproject_list">foo</div>
            </form>

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

