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
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <div class = "section_left" id="section_left">
            <div style="width:100%;"><a href="#sec-1" class="browse_side_nav">1. Disease-wise BioProjects</a></div>
            <div style="width:100%;"><a href="#sec-2" class="browse_side_nav">2. Disease-wise taxonomic profile</a></div>
            <div style="width:100%;"><a href="#sec-3" class="browse_side_nav">3. Disease-wise similarity analysis</a></div>
            <div style="width:100%;"><a href="#sec-4" class="browse_side_nav">4. Differential analysis of disease pairs</a></div>
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
            <div class="browse-heading" id="sec-1">1. Disease-wise BioProjects</div>
            <div class="button-group">
                <button type="button" onclick="getBioProjects('Asthma', 'disease-wise-results')">Asthma</button>
                <button type="button" onclick="getBioProjects('COPD', 'disease-wise-results')">COPD</button>
                <button type="button" onclick="getBioProjects('COVID-19', 'disease-wise-results')">COVID-19</button>
                <button type="button" onclick="getBioProjects('Cystic Fibrosis', 'disease-wise-results')">Cystic Fibrosis</button>
                <button type="button" onclick="getBioProjects('Lung Cancer', 'disease-wise-results')">Lung Cancer</button>
                <button type="button" onclick="getBioProjects('Pneumonia', 'disease-wise-results')">Pneumonia</button>
                <button type="button" onclick="getBioProjects('Tuberculosis', 'disease-wise-results')">Tuberculosis</button>
            </div>
            <div class="browse-result" id="disease-wise-results">foo</div>

            <div class="browse-heading" id="sec-2">
                2. Disease-wise taxonomic profile (Krona plot) across isolation sources
            </div>
            <table class="browse-summary">
                <tr>
                    <th>Disease</th>
                    <th>Assay type</th>
                    <th>Isolation sources</th>
                    <th>Taxonomic profile (Krona plot)</th>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">Asthma</td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">BAL (22), Bronchial mucosa (24), Sputum (118), Stool (174)</td>
<!--                     <td class="odd">BAL (22), Bronchial brush (44), Bronchial mucosa (24), Sputum (118), Stool (174)</td> -->
                    <td class="odd">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Asthma")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">BAL</button></a>
<!--                         <a target="_blank" href="<?php //echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Asthma")."&is=".urlencode("Bronchial Brush");?>"><button type="button" style="margin:2px;">Bronchial brush</button></a> -->
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Asthma")."&is=".urlencode("Bronchial Mucosa");?>"><button type="button" style="margin:2px;">Bronchial mucosa</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Asthma")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Asthma")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td class="odd">WGS</td>
                    <td class="odd">Sputum (5), Stool (25)</td>
                    <td class="odd">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("Asthma")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("Asthma")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">COPD</td>
                    <td class="even">Amplicon</td>
                    <td class="even">BAL (4), Lung tissue (18), Sputum (1077), Stool (28)</td>
                    <td class="even">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("COPD")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">BAL</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("COPD")."&is=".urlencode("Lung Tissue");?>"><button type="button" style="margin:2px;">Lung tissue</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("COPD")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("COPD")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td class="even">WGS</td>
                    <td class="even">Sputum (15), Stool (44)</td>
                    <td class="even">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("COPD")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("COPD")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">COVID-19</td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">BAL (57), Stool (202)</td>
                    <td class="odd">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("COVID-19")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">BAL</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("COVID-19")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td class="odd">WGS</td>
                    <td class="odd">Stool (58)</td>
                    <td class="odd">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("COVID-19")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">Cystic Fibrosis</td>
                    <td class="even">Amplicon</td>
                    <td class="even">BAL (96), Colon mucus (13), Sputum (188), Stool (309)</td>
                    <td class="even">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Cystic Fibrosis")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">BAL</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Cystic Fibrosis")."&is=".urlencode("Colon Mucus");?>"><button type="button" style="margin:2px;">Colon mucus</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Cystic Fibrosis")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Cystic Fibrosis")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td class="even">WGS</td>
                    <td class="even">Sputum (124), Stool (19)</td>
                    <td class="even">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("Cystic Fibrosis")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("Cystic Fibrosis")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td rowspan="1" class="row_heading">Pneumonia</td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">BAL (42), Endotracheal Aspirate (150), Stool (413)</td>
                    <td class="odd">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Pneumonia")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">BAL</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Pneumonia")."&is=".urlencode("Endotracheal Aspirate");?>"><button type="button" style="margin:2px;">Endotracheal Aspirate</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Pneumonia")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">Tuberculosis</td>
                    <td class="even">Amplicon</td>
                    <td class="even">Sputum (58), Stool (149)</td>
                    <td class="even">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Tuberculosis")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Tuberculosis")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td class="even">WGS</td>
                    <td class="even">Stool (48)</td>
                    <td class="even">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("Tuberculosis")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">Healthy</td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">BAL (19), Colon mucus (12), Sputum (11), Stool (392)</td>
                    <td class="odd">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Healthy")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">BAL</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Healthy")."&is=".urlencode("Colon Mucus");?>"><button type="button" style="margin:2px;">Colon mucus</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Healthy")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=Amplicon&ds=".urlencode("Healthy")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
                <tr>
                    <td class="odd">WGS</td>
                    <td class="odd">Sputum (4), Stool (119)</td>
                    <td class="odd">
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("Healthy")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Sputum</button></a>
                        <a target="_blank" href="<?php echo "krona.php?type=DISEASE&at=WGS&ds=".urlencode("Healthy")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Stool</button></a>
                    </td>
                </tr>
            </table>
            <p class="section-footnote">N.B. - Large Krona files. May take longer time to load.</p>

            <!--<div class="browse-heading">
                3. Disease-wise differential analysis (LDA plot) of amplicon data
                across isolation sources
            </div>
            <table class="browse-summary">
                <tr>
                    <th>Disease</th>
                    <th>Biome</th>
                    <th>Isolation sources</th>
                    <th>Linear Discriminant Analysis (LDA) plot</th>
                </tr>
                <tr>
                    <td rowspan="1">Asthma</td>
                    <td>Lung</td>
                    <td>Sputum (118), BAL (22), Bronchial mucosa (24), Bronchial brush (44)</td>
                    <td><button type="button" style="margin:2px;" onclick="">LDA Asthma-Lung</button></td>
                </tr>
                <tr>
                    <td rowspan="1">COPD</td>
                    <td>Lung</td>
                    <td>Sputum (1077), BAL (4), Lung tissue (18)</td>
                    <td><button type="button" style="margin:2px;" onclick="">LDA COPD-Lung</button></td>
                </tr>
                <tr>
                    <td rowspan="2">Cystic Fibrosis</td>
                    <td>Lung</td>
                    <td>Sputum (188), BAL (96)</td>
                    <td><button type="button" style="margin:2px;" onclick="">LDA: Cystic Fibrosis-Lung</button></td>
                </tr>
                    <td>Gut</td>
                    <td>Stool (309), Colon mucus(13)</td>
                    <td><button type="button" style="margin:2px;" onclick="">LDA: Cystic Fibrosis-Gut</button></td>
                <tr>
                <tr>
                    <td rowspan="1">Pneumonia</td>
                    <td>Lung</td>
                    <td>BAL (42), Endotracheal Aspirate (18)</td>
                    <td><button type="button" style="margin:2px;" onclick="">LDA Pneumonia-Lung</button></td>
                </tr>
            </table>-->

            <div class="browse-heading" id="sec-3">
                3. Disease-wise similarity analysis across Gut-Lung biome
            </div>
            <table class="browse-summary">
                <tr>
                    <th>Disease</th>
                    <th>Assay Type</th>
                    <th>Lung sample type</th>
                    <th>Gut sample type</th>
                    <th>Similarity Analysis</th>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">Asthma</td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">Sputum (118)</td>
                    <td class="odd">Stool (172)</td>
                    <td class="odd"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Asthma")."&at=Amplicon&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td class="odd">WGS</td>
                    <td class="odd">Sputum (5)</td>
                    <td class="odd">Stool (25)</td>
                    <td class="odd"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Asthma")."&at=WGS&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">COPD</td>
                    <td class="even">Amplicon</td>
                    <td class="even">Sputum (1077)</td>
                    <td class="even">Stool (28)</td>
                    <td class="even"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("COPD")."&at=Amplicon&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td class="even">WGS</td>
                    <td class="even">Sputum (15)</td>
                    <td class="even">Stool (44)</td>
                    <td class="even"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("COPD")."&at=WGS&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td rowspan="1" class="row_heading">COVID-19 <sup>*</sup></td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">BAL (57)</td>
                    <td class="odd">Stool (202)</td>
                    <td class="odd"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("COVID-19")."&at=Amplicon&is=".urlencode("BAL_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">Cystic Fibrosis</td>
                    <td class="even">Amplicon</td>
                    <td class="even">Sputum (187)</td>
                    <td class="even">Stool (309)</td>
                    <td class="even"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Cystic Fibrosis")."&at=Amplicon&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td class="even">WGS</td>
                    <td class="even">Sputum (124)</td>
                    <td class="even">Stool (19)</td>
                    <td class="even"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Cystic Fibrosis")."&at=WGS&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td rowspan="1" class="row_heading">Pneumonia <sup>*</sup></td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">Endotracheal Aspirate (150)</td>
                    <td class="odd">Stool (413)</td>
                    <td class="odd"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Pneumonia")."&at=Amplicon&is=".urlencode("Endotracheal Aspirate_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td rowspan="1" class="row_heading">Tuberculosis <sup>*</sup></td>
                    <td class="even">Amplicon</td>
                    <td class="even">Sputum (58)</td>
                    <td class="even">Stool (149)</td>
                    <td class="even"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Tuberculosis")."&at=Amplicon&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td rowspan="2" class="row_heading">Healthy</td>
                    <td class="odd">Amplicon</td>
                    <td class="odd">BAL (19)</td>
                    <td class="odd">Stool (392)</td>
                    <td class="odd"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Healthy")."&at=Amplicon&is=".urlencode("BAL_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
                <tr>
                    <td class="odd">WGS</td>
                    <td class="odd">Sputum (4)</td>
                    <td class="odd">Stool (119)</td>
                    <td class="odd"><a target="_blank" href="<?php echo "similarity.php?ds=".urlencode("Cystic Fibrosis")."&at=WGS&is=".urlencode("Sputum_Stool"); ?>"><button type="button" style="margin:2px;">Get result</button></a></td>
                </tr>
            </table>
            <p class="section-footnote"><sup>*</sup> No WGS data available for COVID-19, Pneumonia and Tuberculosis lung microbiome.</p>

            <div class="browse-heading" id="sec-4">
                4. Differential analysis of disease pairs (LDA plot and heatmap)
            </div>
            <table class="browse-summary">
                <tr>
                    <th></th>
                    <th style="width:14%;">Asthma</th>
                    <th style="width:14%;">COPD</th>
                    <th style="width:14%;">COVID-19</th>
                    <th style="width:14%;">Cystic Fibrosis</th>
                    <th style="width:14%;">Pneumonia</th>
                    <th style="width:14%;">Tuberculosis</th>
                </tr>
                <tr>
                    <th>COPD</th>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'asthma-COPD-pair')">Show</button></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>COVID-19</th>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'asthma-COVID-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COPD-COVID-pair')">Show</button></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Cystic Fibrosis</th>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'asthma-cystic_fibrosis-pair')">Show</button></td>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COPD-cystic_fibrosis-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COVID-cystic_fibrosis-pair')">Show</button></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Pneumonia</th>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'asthma-pneumonia-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COPD-pneumonia-pair')">Show</button></td>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COVID-pneumonia-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'cystic_fibrosis-pneumonia-pair')">Show</button></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Tuberculosis</th>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'asthma-tuberculosis-pair')">Show</button></td>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COPD-tuberculosis-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COVID-tuberculosis-pair')">Show</button></td>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'cystic_fibrosis-tuberculosis-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'pneumonia-tuberculosis-pair')">Show</button></td>
                    <td></td>
                </tr>
                <tr>
                    <th>Healthy</th>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'asthma-healthy-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COPD-healthy-pair')">Show</button></td>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'COVID-healthy-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'cystic_fibrosis-healthy-pair')">Show</button></td>
                    <td class="odd"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'pneumonia-healthy-pair')">Show</button></td>
                    <td class="even"><button type="button" style="margin:2px;" onclick="showDiseasePairResults('disease-pair-results', 'tuberculosis-healthy-pair')">Show</button></td>
                </tr>
            </table>
            <div class="browse-result" id="disease-pair-results">foo</div>

            <div class="browse-result" id="asthma-COPD-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Asthma - COPD</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="5" class="row_heading">Asthma - COPD</td>
                        <td rowspan="3" class="odd">Amplicon</td><td rowspan="2" class="odd">Lung</td><td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_COPD")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_COPD")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">BAL</td>
                        <td colspan="2" class="odd">No plot !! SVD error.</td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma COPD")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_COPD")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="even">WGS</td><td class="even">Lung</td><td class="even">Sputum</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_COPD")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_COPD")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_COPD")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_COPD")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
            </div>
            <div class="browse-result" id="asthma-COVID-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Asthma - COVID-19</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="3" class="row_heading">Asthma - COVID-19</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_COVID-19")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_COVID-19")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_COVID-19")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_COVID-19")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_COVID-19")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_COVID-19")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for COVID-19.</p>
            </div>
            <div class="browse-result" id="asthma-cystic_fibrosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Asthma - Cystic Fibrosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="5" class="row_heading">Asthma - Cystic Fibrosis</td>
                        <td rowspan="3" class="odd">Amplicon</td><td class="odd" rowspan="2">Lung</td><td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="even">WGS</td><td class="even">Lung</td><td class="even">Sputum</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
            </div>
            <div class="browse-result" id="asthma-pneumonia-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Asthma - Pneumonia</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="2" class="row_heading">Asthma - Pneumonia</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Pneumonia")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Pneumonia")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No WGS data available for Pneumonia.</p>
            </div>
            <div class="browse-result" id="asthma-tuberculosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Asthma - Tuberculosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="3" class="row_heading">Asthma - Tuberculosis</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Tuberculosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Tuberculosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for Tuberculosis.</p>
            </div>
            <div class="browse-result" id="asthma-healthy-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Asthma - Healthy</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="5" class="row_heading">Asthma - Healthy</td>
                        <td rowspan="3" class="odd">Amplicon</td>
                        <td rowspan="2" class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="even">WGS</td><td class="even">Lung</td><td class="even">Sputum</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Healthy")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Healthy")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Asthma_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Asthma_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
            </div>
            <div class="browse-result" id="COPD-COVID-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COPD - COVID-19</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="3" class="row_heading">COPD - COVID-19</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd" colspan="2">No plot !! SVD error.</td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_COVID-19")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_COVID-19")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_COVID-19")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_COVID-19")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for COVID-19.</p>
            </div>
            <div class="browse-result" id="COPD-cystic_fibrosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COPD - Cystic Fibrosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="4" class="row_heading">COPD - Cystic Fibrosis</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="even">WGS</td><td class="even">Lung</td><td class="even">Sputum</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
            </div>
            <div class="browse-result" id="COPD-pneumonia-pair">
            <p class="browse-result-heading"><i>Disease pair:</i> COPD - Pneumonia</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="2" class="row_heading">COPD - Pneumonia</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd" colspan="2">No plot !! SVD error.</td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No WGS data available for Pneumonia.</p>
            </div>
            <div class="browse-result" id="COPD-tuberculosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COPD - Tuberculosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="3" class="row_heading">COPD - Tuberculosis</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Tuberculosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Tuberculosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?&dp=".urlencode("COPD_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for Tuberculosis.</p>
            </div>
            <div class="browse-result" id="COPD-healthy-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COPD - Healthy</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="5" class="row_heading">COPD - Healthy</td>
                        <td rowspan="3" class="odd">Amplicon</td>
                        <td rowspan="2" class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="even">WGS</td><td class="even">Lung</td><td class="even">Sputum</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Healthy")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Healthy")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COPD_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COPD_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
            </div>
            <div class="browse-result" id="COVID-cystic_fibrosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COVID-19 - Cystic Fibrosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="3" class="row_heading">COVID-19 - Cystic Fibrosis</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Cystic Fibrosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Cystic Fibrosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for COVID-19.</p>
            </div>
            <div class="browse-result" id="COVID-pneumonia-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COVID-19 - Pneumonia</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="2" class="row_heading">COVID-19 - Pneumonia</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Pneumonia")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Pneumonia")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">
                    N.B. - <br/>
                    1. No WGS data available for Pneumonia.<br/>
                    2. No Lung WGS data available for COVID-19.
                </p>
            </div>
            <div class="browse-result" id="COVID-tuberculosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COVID-19 - Tuberculosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="2" class="row_heading">COVID-19 - Tuberculosis</td>
                        <td rowspan="1" class="odd">Amplicon</td><td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">
                    N.B. - <br/>
                    1. No Lung Amplicon data available of same isolation sources for COVID-19 and Tuberculosis.<br/>
                    2. No Lung WGS data available for both COVID-19 and Tuberculosis.
                </p>
            </div>
            <div class="browse-result" id="COVID-healthy-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> COVID-19 - Healthy</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="4" class="row_heading">COVID-19 - Healthy</td>
                        <td rowspan="2" class="odd">Amplicon</td>
                        <td rowspan="1" class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("COVID-19_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("COVID-19_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for COVID-19.</p>
            </div>
            <div class="browse-result" id="cystic_fibrosis-pneumonia-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Cystic Fibrosis - Pneumonia</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="2" class="row_heading">Cystic Fibrosis - Pneumonia</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Pneumonia")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Pneumonia")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Pneumonia")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No WGS data available for Pneumonia.</p>
            </div>
            <div class="browse-result" id="cystic_fibrosis-tuberculosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Cystic Fibrosis - Tuberculosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="3" class="row_heading">Cystic Fibrosis - Tuberculosis</td>
                        <td rowspan="2" class="odd">Amplicon</td><td class="odd">Lung</td><td class="odd">Sputum</td>
                        <td class="odd" colspan="2">No plot !! SVD error.</td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Tuberculosis")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for Tuberculosis.</p>
            </div>
            <div class="browse-result" id="cystic_fibrosis-healthy-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Cystic Fibrosis - Healthy</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="6" class="row_heading">Cystic Fibrosis - Healthy</td>
                        <td rowspan="4" class="odd">Amplicon</td>
                        <td rowspan="2" class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="odd">Gut</td><td class="odd">Colon mucus</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Colon Mucus");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Colon Mucus");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="2" class="even">WGS</td><td class="even">Lung</td><td class="even">Sputum</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Healthy")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Healthy")."&at=WGS&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Cystic Fibrosis_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Cystic Fibrosis_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
            </div>
            <div class="browse-result" id="pneumonia-tuberculosis-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Pneumonia - Tuberculosis</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="1" class="row_heading">Pneumonia - Tuberculosis</td>
                        <td rowspan="1" class="odd">Amplicon</td><td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Pneumonia_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Pneumonia_Tuberculosis")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">
                    N.B. -<br/>
                    1. No WGS data available for both Pneumonia<br/>
                    2. No Lung WGS data available for Tuberculosis.<br/>
                    3. No Lung Amplicon data available of same isolation sources for Pneumonia and Tuberculosis.
                </p>
            </div>
            <div class="browse-result" id="pneumonia-healthy-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Pneumonia - Healthy</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="2" class="row_heading">Pneumonia - Healthy</td>
                        <td rowspan="2" class="odd">Amplicon</td>
                        <td rowspan="1" class="odd">Lung</td><td class="odd">BAL</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Pneumonia_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Pneumonia_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("BAL");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Pneumonia_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Pneumonia_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No WGS data available for Pneumonia.</p>
            </div>
            <div class="browse-result" id="tuberculosis-healthy-pair">
                <p class="browse-result-heading"><i>Disease pair:</i> Tuberculosis - Healthy</p>
                <table class="browse-result-summary">
                    <tr><th>Disease-pair</th><th>Assay type</th><th>Biome</th><th>Isolation source</th><th>LDA plot</th><th>Heatmap</th></tr>
                    <tr>
                        <td rowspan="3" class="row_heading">Tuberculosis - Healthy</td>
                        <td rowspan="2" class="odd">Amplicon</td>
                        <td rowspan="1" class="odd">Lung</td><td class="odd">Sputum</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Tuberculosis_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Tuberculosis_Healthy")."&at=Amplicon&biome=".urlencode("Lung")."&is=".urlencode("Sputum");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td class="odd">Gut</td><td class="odd">Stool</td>
                        <td class="odd"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Tuberculosis_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="odd"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Tuberculosis_Healthy")."&at=Amplicon&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                    <tr>
                        <td rowspan="1" class="even">WGS</td><td class="even">Gut</td><td class="even">Stool</td>
                        <td class="even"><a target="_blank" href="<?php echo "lda.php?type=DISEASE&dp=".urlencode("Tuberculosis_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get LDA plot</button></a></td>
                        <td class="even"><a target="_blank" href="<?php echo "heatmap.php?dp=".urlencode("Tuberculosis_Healthy")."&at=WGS&biome=".urlencode("Gut")."&is=".urlencode("Stool");?>"><button type="button" style="margin:2px;">Get heatmap</button></td>
                    </tr>
                </table>
                <p class="section-footnote">N.B. - No Lung WGS data available for Tuberculosis.</p>
            </div>

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
