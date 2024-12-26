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
                <button type="button" onclick="getBioProjects('Acute Respiratory Distress Syndrome (ARDS)', 'disease-wise-results')">Acute Respiratory Distress Syndrome (ARDS)</button>
                <button type="button" onclick="getBioProjects('Asthma', 'disease-wise-results')">Asthma</button>
                <button type="button" onclick="getBioProjects('Asthma-COPD Overlap (ACO)', 'disease-wise-results')">Asthma-COPD Overlap (ACO)</button>
                <button type="button" onclick="getBioProjects('Bronchiectasis', 'disease-wise-results')">Bronchiectasis</button>
                <button type="button" onclick="getBioProjects('Bronchiolitis', 'disease-wise-results')">Bronchiolitis</button>
                <button type="button" onclick="getBioProjects('Bronchitis', 'disease-wise-results')">Bronchitis</button>
                <button type="button" onclick="getBioProjects('Chronic Obstructive Pulmonary Disease (COPD)', 'disease-wise-results')">Chronic Obstructive Pulmonary Disease (COPD)</button>
                <button type="button" onclick="getBioProjects('COPD-Bronchiectasis Association (CBA)', 'disease-wise-results')">COPD-Bronchiectasis Association (CBA)</button>
                <button type="button" onclick="getBioProjects('COVID-19', 'disease-wise-results')">COVID-19</button>
                <button type="button" onclick="getBioProjects('Cystic Fibrosis', 'disease-wise-results')">Cystic Fibrosis</button>
                <button type="button" onclick="getBioProjects('Idiopathic Pulmonary Fibrosis (IPF)', 'disease-wise-results')">Idiopathic Pulmonary Fibrosis (IPF)</button>
                <button type="button" onclick="getBioProjects('Interstitial Lung Disease (ILD)', 'disease-wise-results')">Interstitial Lung Disease (ILD)</button>
                <button type="button" onclick="getBioProjects('Lung Cancer', 'disease-wise-results')">Lung Cancer</button>
                <button type="button" onclick="getBioProjects('Other Pulmonary Infections', 'disease-wise-results')">Other Pulmonary Infections</button>
                <button type="button" onclick="getBioProjects('Pneumonia', 'disease-wise-results')">Pneumonia</button>
                <button type="button" onclick="getBioProjects('Pneumonitis', 'disease-wise-results')">Pneumonitis</button>
                <button type="button" onclick="getBioProjects('Pulmonary Hypertension', 'disease-wise-results')">Pulmonary Hypertension</button>
                <button type="button" onclick="getBioProjects('Sarcoidosis', 'disease-wise-results')">Sarcoidosis</button>
                <button type="button" onclick="getBioProjects('Tuberculosis', 'disease-wise-results')">Tuberculosis</button>
            </div>
            <div class="browse-result" id="disease-wise-results">foo</div>

            <div class="browse-heading" id="sec-2">2. Isolation source wise BioProjects</div>
            <table class="browse-summary">
                <tr>
                    <th>Biome</th>
                    <th>Isolation source</th>
                </tr>
                <tr>
                    <td class="row_heading">Anus</td>
                    <td class="odd">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Anal Swab', 'is-wise-results')">Anal Swab</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Gut</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Stool', 'is-wise-results')">Stool</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Large Intestine</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Colon Mucus', 'is-wise-results')">Colon Mucus</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Colonoscopic Sample', 'is-wise-results')">Colonoscopic Sample</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Lower Respiratory Tract</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('BALF', 'is-wise-results')">BALF</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Bronchial Aspirate', 'is-wise-results')">Bronchial Aspirate</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Bronchial Brush', 'is-wise-results')">Bronchial Brush</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Bronchial Sample', 'is-wise-results')">Bronchial Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Bronchial Wash', 'is-wise-results')">Bronchial Wash</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Endotracheal Aspirate', 'is-wise-results')">Endotracheal Aspirate</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Endotracheal Secretion', 'is-wise-results')">Endotracheal Secretion</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Endotracheal Tube Biofilms', 'is-wise-results')">Endotracheal Tube Biofilms</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Pleural Effusion', 'is-wise-results')">Pleural Effusion</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Protected Brush', 'is-wise-results')">Protected Brush</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Pulmonary Biopsy', 'is-wise-results')">Pulmonary Biopsy</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Scope Wash', 'is-wise-results')">Scope Wash</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Sputum', 'is-wise-results')">Sputum</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Tracheal Aspirate', 'is-wise-results')">Tracheal Aspirate</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Tracheal Secretion', 'is-wise-results')">Tracheal Secretion</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Lung</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Lung Tissue', 'is-wise-results')">Lung Tissue</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Tumor Tissue', 'is-wise-results')">Tumor Tissue</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Nasal</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Maxillary Sinus Lavage', 'is-wise-results')">Maxillary Sinus Lavage</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasal Lavage', 'is-wise-results')">Nasal Lavage</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasal Mucosa', 'is-wise-results')">Nasal Mucosa</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasal Sample', 'is-wise-results')">Nasal Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasal Swab', 'is-wise-results')">Nasal Swab</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasal Wash', 'is-wise-results')">Nasal Wash</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Polyp', 'is-wise-results')">Polyp</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Sinus Mucus', 'is-wise-results')">Sinus Mucus</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Sinus Sample', 'is-wise-results')">Sinus Sample</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Oral</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Buccal Sample', 'is-wise-results')">Buccal Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Gum Sample', 'is-wise-results')">Gum Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Oral Mucosa', 'is-wise-results')">Oral Mucosa</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Oral Rinse', 'is-wise-results')">Oral Rinse</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Oral Sample', 'is-wise-results')">Oral Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Oral Swab', 'is-wise-results')">Oral Swab</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Oral Wash', 'is-wise-results')">Oral Wash</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Saliva', 'is-wise-results')">Saliva</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Subgingival Plaque', 'is-wise-results')">Subgingival Plaque</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Supra Plaque', 'is-wise-results')">Supra Plaque</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Tongue Coating Sample', 'is-wise-results')">Tongue Coating Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Tongue Sample', 'is-wise-results')">Tongue Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Tongue Swab', 'is-wise-results')">Tongue Swab</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Rectum</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Rectal Swab', 'is-wise-results')">Rectal Swab</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Stomach</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Duodenal Strings', 'is-wise-results')">Duodenal Strings</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Esophageal Sample', 'is-wise-results')">Esophageal Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Gastric Strings', 'is-wise-results')">Gastric Strings</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Upper Respiratory Tract</td>
                    <td class="even">
                        <div class="button-group">
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Airway Aspirate', 'is-wise-results')">Airway Aspirate</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Cough Swab', 'is-wise-results')">Cough Swab</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Hypopharyngeal Aspirate', 'is-wise-results')">Hypopharyngeal Aspirate</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasopharyngeal Aspirate', 'is-wise-results')">Nasopharyngeal Aspirate</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasopharyngeal Sample', 'is-wise-results')">Nasopharyngeal Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasopharyngeal Smear', 'is-wise-results')">Nasopharyngeal Smear</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasopharyngeal Swab', 'is-wise-results')">Nasopharyngeal Swab</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Nasopharyngeal Swab and Throat Swabs (NPSTSs)', 'is-wise-results')">Nasopharyngeal Swab and Throat Swabs (NPSTSs)</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('OropharyngealSwab', 'is-wise-results')">OropharyngealSwab</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Supraglottic Swab', 'is-wise-results')">Supraglottic Swab</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Throat Aspirate', 'is-wise-results')">Throat Aspirate</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Throat Sample', 'is-wise-results')">Throat Sample</button>
                            <button type="button" style="width:auto;float:left;" onclick="getIsolationSourceBioProjects('Throat Swab', 'is-wise-results')">Throat Swab</button>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="browse-result" id="is-wise-results">foo</div>

            <div class="browse-heading" id="sec-2">3. Disease subgroup wise BioProjects</div>
            <table class="browse-summary">
                <tr>
                    <td class="row_heading" style="width:25%;">Acute Respiratory Distress Syndrome (ARDS)</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('ARDS', 'subgroup-wise-results')">ARDS</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('ARDS Hospital Death', 'subgroup-wise-results')">ARDS Hospital Death</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Asthma</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma', 'subgroup-wise-results')">Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma Baseline', 'subgroup-wise-results')">Asthma Baseline</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma Exacerbation', 'subgroup-wise-results')">Asthma Exacerbation</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma ICU', 'subgroup-wise-results')">Asthma ICU</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma MCU', 'subgroup-wise-results')">Asthma MCU</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma No Exacerbation', 'subgroup-wise-results')">Asthma No Exacerbation</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma Recovery', 'subgroup-wise-results')">Asthma Recovery</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Atopic Asthma', 'subgroup-wise-results')">Atopic Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bacterial Eosinophilic Asthma', 'subgroup-wise-results')">Bacterial Eosinophilic Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bacterial Non Eosinophilic Asthma', 'subgroup-wise-results')">Bacterial Non Eosinophilic Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Eosinophilic Asthma', 'subgroup-wise-results')">Eosinophilic Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Fungi Eosinophilic Asthma', 'subgroup-wise-results')">Fungi Eosinophilic Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Fungi Non Eosinophilic Asthma', 'subgroup-wise-results')">Fungi Non Eosinophilic Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Mild/Moderate School-age Asthma', 'subgroup-wise-results')">Mild/Moderate School-age Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Eosinophilic Asthma', 'subgroup-wise-results')">Non Eosinophilic Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('School Asthma', 'subgroup-wise-results')">School Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Severe Asthma', 'subgroup-wise-results')">Severe Asthma</a>;
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Severe School-age Asthma', 'subgroup-wise-results')">Severe School-age Asthma</a>;
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Asthma-COPD Overlap (ACO)</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Asthma-COPD Overlap (ACO)', 'subgroup-wise-results')">Asthma-COPD Overlap (ACO)</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Bronchiectasis</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis', 'subgroup-wise-results')">Bronchiectasis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis Exacerbation-After Treatment', 'subgroup-wise-results')">Bronchiectasis Exacerbation-After Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis Exacerbation-Before Treatment', 'subgroup-wise-results')">Bronchiectasis Exacerbation-Before Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis Exacerbation-End of Treatment', 'subgroup-wise-results')">Bronchiectasis Exacerbation-End of Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis Exacerbation-Post Treatment When Stable', 'subgroup-wise-results')">Bronchiectasis Exacerbation-Post Treatment When Stable</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis Exacerbation-Start of Treatment', 'subgroup-wise-results')">Bronchiectasis Exacerbation-Start of Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis Stable', 'subgroup-wise-results')">Bronchiectasis Stable</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis-Allergic Bronchopulmonary Aspergillosis (ABPA)', 'subgroup-wise-results')">Bronchiectasis-Allergic Bronchopulmonary Aspergillosis (ABPA)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis-Idiopathic', 'subgroup-wise-results')">Bronchiectasis-Idiopathic</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis-Immunodeficiency', 'subgroup-wise-results')">Bronchiectasis-Immunodeficiency</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis-Other', 'subgroup-wise-results')">Bronchiectasis-Other</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis-Post Infection', 'subgroup-wise-results')">Bronchiectasis-Post Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiectasis-Primary Ciliary Dyskinesia (PCD)', 'subgroup-wise-results')">Bronchiectasis-Primary Ciliary Dyskinesia (PCD)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Cystic Fibrosis Bronchiectasis', 'subgroup-wise-results')">Non Cystic Fibrosis Bronchiectasis</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Bronchiolitis</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiolitis Moderate', 'subgroup-wise-results')">Bronchiolitis Moderate</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchiolitis Severe', 'subgroup-wise-results')">Bronchiolitis Severe</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Bronchitis</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Persistent Bacterial Bronchitis', 'subgroup-wise-results')">Persistent Bacterial Bronchitis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Protracted Bacterial Bronchitis', 'subgroup-wise-results')">Protracted Bacterial Bronchitis</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Chronic Obstructive Pulmonary Disease (COPD)</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bacterial Eosinophilic COPD', 'subgroup-wise-results')">Bacterial Eosinophilic COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bacterial Non Eosinophilic COPD', 'subgroup-wise-results')">Bacterial Non Eosinophilic COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Biomass Exoposure COPD', 'subgroup-wise-results')">Biomass Exoposure COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD', 'subgroup-wise-results')">COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD 0 Months', 'subgroup-wise-results')">COPD 0 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD 11 Months', 'subgroup-wise-results')">COPD 11 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD 3 Months', 'subgroup-wise-results')">COPD 3 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD 5 Months', 'subgroup-wise-results')">COPD 5 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD 6 Months', 'subgroup-wise-results')">COPD 6 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD 7 Months', 'subgroup-wise-results')">COPD 7 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD 9 Months', 'subgroup-wise-results')">COPD 9 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Day 1 Sample', 'subgroup-wise-results')">COPD Day 1 Sample</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Day 14 Sample', 'subgroup-wise-results')">COPD Day 14 Sample</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Day 7 Sample', 'subgroup-wise-results')">COPD Day 7 Sample</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD End of Exacerbation', 'subgroup-wise-results')">COPD End of Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Exacerbation', 'subgroup-wise-results')">COPD Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Follow Up', 'subgroup-wise-results')">COPD Follow Up</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Frequent Exacerberator', 'subgroup-wise-results')">COPD Frequent Exacerberator</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD GOLD 1', 'subgroup-wise-results')">COPD GOLD 1</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD GOLD 2', 'subgroup-wise-results')">COPD GOLD 2</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD GOLD 3', 'subgroup-wise-results')">COPD GOLD 3</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD GOLD 4', 'subgroup-wise-results')">COPD GOLD 4</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Infrequent Exacerberator', 'subgroup-wise-results')">COPD Infrequent Exacerberator</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Long Term Inhaled Corticosteroids', 'subgroup-wise-results')">COPD Long Term Inhaled Corticosteroids</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Non Survivor', 'subgroup-wise-results')">COPD Non Survivor</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Patient 1', 'subgroup-wise-results')">COPD Patient 1</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Patient 10', 'subgroup-wise-results')">COPD Patient 10</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Patient 12', 'subgroup-wise-results')">COPD Patient 12</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Patient 3', 'subgroup-wise-results')">COPD Patient 3</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Patient 5', 'subgroup-wise-results')">COPD Patient 5</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Patient 6', 'subgroup-wise-results')">COPD Patient 6</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Patient 7', 'subgroup-wise-results')">COPD Patient 7</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Post Therapy', 'subgroup-wise-results')">COPD Post Therapy</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Pulmonary Intervention', 'subgroup-wise-results')">COPD Pulmonary Intervention</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Recovery', 'subgroup-wise-results')">COPD Recovery</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Severe', 'subgroup-wise-results')">COPD Severe</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Short Term Inhaled Corticosteroids', 'subgroup-wise-results')">COPD Short Term Inhaled Corticosteroids</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Six Months from First Stable Visit', 'subgroup-wise-results')">COPD Six Months from First Stable Visit</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Six Week Post Exacerbation', 'subgroup-wise-results')">COPD Six Week Post Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Stable', 'subgroup-wise-results')">COPD Stable</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Start of Exacerbation', 'subgroup-wise-results')">COPD Start of Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Survivor', 'subgroup-wise-results')">COPD Survivor</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD Two Week Post Exacerbation', 'subgroup-wise-results')">COPD Two Week Post Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD with Bronchitis', 'subgroup-wise-results')">COPD with Bronchitis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD with Periodontitis', 'subgroup-wise-results')">COPD with Periodontitis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Eosinophilic COPD', 'subgroup-wise-results')">Eosinophilic COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Fungi Eosinophilic COPD', 'subgroup-wise-results')">Fungi Eosinophilic COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Fungi Non Eosinophilic COPD', 'subgroup-wise-results')">Fungi Non Eosinophilic COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Eosinophilic COPD', 'subgroup-wise-results')">Non Eosinophilic COPD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Tobacco Smoke Exopsure COPD', 'subgroup-wise-results')">Tobacco Smoke Exopsure COPD</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">COPD-Bronchiectasis Association (CBA)</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COPD-Bronchiectasis Association (CBA)', 'subgroup-wise-results')">COPD-Bronchiectasis Association (CBA)</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">COVID-19</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19', 'subgroup-wise-results')">COVID-19</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 0 Months', 'subgroup-wise-results')">COVID-19 0 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 1 Months', 'subgroup-wise-results')">COVID-19 1 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 4 Weeks After SARS-CoV-2 Infection', 'subgroup-wise-results')">COVID-19 4 Weeks After SARS-CoV-2 Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 6 Months', 'subgroup-wise-results')">COVID-19 6 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 8 Weeks After SARS-CoV-2 Infection', 'subgroup-wise-results')">COVID-19 8 Weeks After SARS-CoV-2 Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 9 Months', 'subgroup-wise-results')">COVID-19 9 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Acute', 'subgroup-wise-results')">COVID-19 Acute</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Ambulatory', 'subgroup-wise-results')">COVID-19 Ambulatory</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Asymptomatic', 'subgroup-wise-results')">COVID-19 Asymptomatic</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Asymptomatic Adult', 'subgroup-wise-results')">COVID-19 Asymptomatic Adult</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Asymptomatic Early Phase', 'subgroup-wise-results')">COVID-19 Asymptomatic Early Phase</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Asymptomatic Recovery Phase', 'subgroup-wise-results')">COVID-19 Asymptomatic Recovery Phase</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Asymptomic/Mild', 'subgroup-wise-results')">COVID-19 Asymptomic/Mild</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Children', 'subgroup-wise-results')">COVID-19 Children</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Convalescence', 'subgroup-wise-results')">COVID-19 Convalescence</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Coronary Care Unit (CCU) Workers', 'subgroup-wise-results')">COVID-19 Coronary Care Unit (CCU) Workers</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Critical', 'subgroup-wise-results')">COVID-19 Critical</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Deceased', 'subgroup-wise-results')">COVID-19 Deceased</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Discharged', 'subgroup-wise-results')">COVID-19 Discharged</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Early Infection (1st-2nd Trimester)', 'subgroup-wise-results')">COVID-19 Early Infection (1st-2nd Trimester)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 High Intensity', 'subgroup-wise-results')">COVID-19 High Intensity</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Indeterminate', 'subgroup-wise-results')">COVID-19 Indeterminate</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Inpatient', 'subgroup-wise-results')">COVID-19 Inpatient</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Inpatient No Antibiotics', 'subgroup-wise-results')">COVID-19 Inpatient No Antibiotics</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Intensive Care Unit (ICU) Workers', 'subgroup-wise-results')">COVID-19 Intensive Care Unit (ICU) Workers</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Late Infection (3rd Trimester)', 'subgroup-wise-results')">COVID-19 Late Infection (3rd Trimester)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Low Intensity', 'subgroup-wise-results')">COVID-19 Low Intensity</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Medium', 'subgroup-wise-results')">COVID-19 Medium</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Mild', 'subgroup-wise-results')">COVID-19 Mild</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Moderate', 'subgroup-wise-results')">COVID-19 Moderate</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Non Critical', 'subgroup-wise-results')">COVID-19 Non Critical</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Non Severe', 'subgroup-wise-results')">COVID-19 Non Severe</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Omicron Variant', 'subgroup-wise-results')">COVID-19 Omicron Variant</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Operating Room (OR) Workers', 'subgroup-wise-results')">COVID-19 Operating Room (OR) Workers</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Original Strain', 'subgroup-wise-results')">COVID-19 Original Strain</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Outpatient', 'subgroup-wise-results')">COVID-19 Outpatient</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Paucisymptomatic', 'subgroup-wise-results')">COVID-19 Paucisymptomatic</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Postconvalescence', 'subgroup-wise-results')">COVID-19 Postconvalescence</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Recovery', 'subgroup-wise-results')">COVID-19 Recovery</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Respiratory Medicine Workers', 'subgroup-wise-results')">COVID-19 Respiratory Medicine Workers</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 SARS-CoV-2 Positive', 'subgroup-wise-results')">COVID-19 SARS-CoV-2 Positive</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Severe', 'subgroup-wise-results')">COVID-19 Severe</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Severe with Admission to ICU', 'subgroup-wise-results')">COVID-19 Severe with Admission to ICU</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Severe with Hospitalization', 'subgroup-wise-results')">COVID-19 Severe with Hospitalization</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Severe/Critical', 'subgroup-wise-results')">COVID-19 Severe/Critical</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Severely Symptomatic', 'subgroup-wise-results')">COVID-19 Severely Symptomatic</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Symptomatic', 'subgroup-wise-results')">COVID-19 Symptomatic</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 with Breast Cancer', 'subgroup-wise-results')">COVID-19 with Breast Cancer</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Long COVID-19', 'subgroup-wise-results')">Long COVID-19</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Mechanically Ventilated COVID-19', 'subgroup-wise-results')">Mechanically Ventilated COVID-19</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Cystic Fibrosis</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis', 'subgroup-wise-results')">Cystic Fibrosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Additional Symptoms of Airway Complications', 'subgroup-wise-results')">Cystic Fibrosis Additional Symptoms of Airway Complications</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis After Antibiotics', 'subgroup-wise-results')">Cystic Fibrosis After Antibiotics</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Baseline', 'subgroup-wise-results')">Cystic Fibrosis Baseline</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Before Antibiotics', 'subgroup-wise-results')">Cystic Fibrosis Before Antibiotics</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Exacerbation', 'subgroup-wise-results')">Cystic Fibrosis Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Exacerbation (No Treatment)', 'subgroup-wise-results')">Cystic Fibrosis Exacerbation (No Treatment)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Exacerbation (Treatment with Antibiotics)', 'subgroup-wise-results')">Cystic Fibrosis Exacerbation (Treatment with Antibiotics)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Follow Up', 'subgroup-wise-results')">Cystic Fibrosis Follow Up</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Inter Antibacterial Therapy', 'subgroup-wise-results')">Cystic Fibrosis Inter Antibacterial Therapy</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis LowCF', 'subgroup-wise-results')">Cystic Fibrosis LowCF</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis MedCF', 'subgroup-wise-results')">Cystic Fibrosis MedCF</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Mild', 'subgroup-wise-results')">Cystic Fibrosis Mild</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis MiPro', 'subgroup-wise-results')">Cystic Fibrosis MiPro</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Moderate', 'subgroup-wise-results')">Cystic Fibrosis Moderate</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis No Exacerbation', 'subgroup-wise-results')">Cystic Fibrosis No Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis On Antibiotics No Exacerbation', 'subgroup-wise-results')">Cystic Fibrosis On Antibiotics No Exacerbation</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Post Antibacterial Therapy', 'subgroup-wise-results')">Cystic Fibrosis Post Antibacterial Therapy</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Pre Antibacterial Therapy', 'subgroup-wise-results')">Cystic Fibrosis Pre Antibacterial Therapy</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Recovery (within 1 Month After Stopping Antibiotics for Exacerbation)', 'subgroup-wise-results')">Cystic Fibrosis Recovery (within 1 Month After Stopping Antibiotics for Exacerbation)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Severe', 'subgroup-wise-results')">Cystic Fibrosis Severe</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cystic Fibrosis Treatment', 'subgroup-wise-results')">Cystic Fibrosis Treatment</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Idiopathic Pulmonary Fibrosis (IPF)</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('IPF', 'subgroup-wise-results')">IPF</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('IPF After 6 Months', 'subgroup-wise-results')">IPF After 6 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('IPF Baseline', 'subgroup-wise-results')">IPF Baseline</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Interstitial Lung Disease (ILD)</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Connective Tissue Disease Associated ILD (CTD-ILD)', 'subgroup-wise-results')">Connective Tissue Disease Associated ILD (CTD-ILD)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('ILD', 'subgroup-wise-results')">ILD</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('ILD Exacerbation', 'subgroup-wise-results')">ILD Exacerbation</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Lung Cancer</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma', 'subgroup-wise-results')">Adenocarcinoma</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma I', 'subgroup-wise-results')">Adenocarcinoma I</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma II', 'subgroup-wise-results')">Adenocarcinoma II</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma III', 'subgroup-wise-results')">Adenocarcinoma III</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma IV', 'subgroup-wise-results')">Adenocarcinoma IV</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma, Squamous Celll Carcinoma II', 'subgroup-wise-results')">Adenocarcinoma, Squamous Celll Carcinoma II</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma: BAC features', 'subgroup-wise-results')">Adenocarcinoma: BAC features</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenocarcinoma: Mixed Features', 'subgroup-wise-results')">Adenocarcinoma: Mixed Features</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenosquamous Carcinoma', 'subgroup-wise-results')">Adenosquamous Carcinoma</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Benign Lung Tumor Tissue', 'subgroup-wise-results')">Benign Lung Tumor Tissue</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bronchioalveolar Carcinoma', 'subgroup-wise-results')">Bronchioalveolar Carcinoma</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Inflammation Nodule Lung Tissue', 'subgroup-wise-results')">Inflammation Nodule Lung Tissue</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Large Cell Carcinoma', 'subgroup-wise-results')">Large Cell Carcinoma</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Large Cell Carcinoma III', 'subgroup-wise-results')">Large Cell Carcinoma III</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Large Cell Carcinoma IV', 'subgroup-wise-results')">Large Cell Carcinoma IV</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Lung Cancer', 'subgroup-wise-results')">Lung Cancer</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Lung Cancer JK5G Group After 4 Treatment Cycles', 'subgroup-wise-results')">Lung Cancer JK5G Group After 4 Treatment Cycles</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Lung Cancer JK5G Group Before Treatment', 'subgroup-wise-results')">Lung Cancer JK5G Group Before Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Lung Tumor Tissue', 'subgroup-wise-results')">Lung Tumor Tissue</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Mixed', 'subgroup-wise-results')">Mixed</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC)', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) I', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) I</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) III', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) III</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) IV', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) IV</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) Non Responsive Pre Treatment', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) Non Responsive Pre Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) Non Resposive Post Treatment', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) Non Resposive Post Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) Progression Free Survival Above 6 Months', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) Progression Free Survival Above 6 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) Progression Free Survival Below 6 Months', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) Progression Free Survival Below 6 Months</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) Responsive Post Treatment', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) Responsive Post Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Non Small Cell Lung Cancer (NSCLC) Responsive Pre Treatment', 'subgroup-wise-results')">Non Small Cell Lung Cancer (NSCLC) Responsive Pre Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Poorly Differentiated Carcinoma IV', 'subgroup-wise-results')">Poorly Differentiated Carcinoma IV</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Small Cell Carcinoma', 'subgroup-wise-results')">Small Cell Carcinoma</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Small Cell Carcinoma III', 'subgroup-wise-results')">Small Cell Carcinoma III</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Small Cell Lung Cancer (SCLC)', 'subgroup-wise-results')">Small Cell Lung Cancer (SCLC)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Squamous Cell Carcinoma', 'subgroup-wise-results')">Squamous Cell Carcinoma</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Squamous Cell Carcinoma I', 'subgroup-wise-results')">Squamous Cell Carcinoma I</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Squamous Cell Carcinoma II', 'subgroup-wise-results')">Squamous Cell Carcinoma II</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Squamous Cell carcinoma III', 'subgroup-wise-results')">Squamous Cell carcinoma III</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Squamous Cell Carcinoma IV', 'subgroup-wise-results')">Squamous Cell Carcinoma IV</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Tumor Adjacent Tissue', 'subgroup-wise-results')">Tumor Adjacent Tissue</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Tumor Side BALF', 'subgroup-wise-results')">Tumor Side BALF</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Other Pulmonary Infections</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Acute Respiratory Infection', 'subgroup-wise-results')">Acute Respiratory Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Bacterial Infection', 'subgroup-wise-results')">Bacterial Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('COVID-19 Associated Pulmonary Aspergillosis', 'subgroup-wise-results')">COVID-19 Associated Pulmonary Aspergillosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Fungal Infection', 'subgroup-wise-results')">Fungal Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Influenza Associated Pulmonary Aspergillosis', 'subgroup-wise-results')">Influenza Associated Pulmonary Aspergillosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Influenza Virus Infection', 'subgroup-wise-results')">Influenza Virus Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Lower Respiratory Tract Infections (LRTI)', 'subgroup-wise-results')">Lower Respiratory Tract Infections (LRTI)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Metapneumovirus Infection', 'subgroup-wise-results')">Metapneumovirus Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Respiratory Infections', 'subgroup-wise-results')">Respiratory Infections</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Respiratory Syncytial Virus (RSV) Infection', 'subgroup-wise-results')">Respiratory Syncytial Virus (RSV) Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Rhinovirus Infection', 'subgroup-wise-results')">Rhinovirus Infection</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Unknown Lower Respiratory Tract Infections (LRTI)', 'subgroup-wise-results')">Unknown Lower Respiratory Tract Infections (LRTI)</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Pneumonia</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Adenoviral Pneumonia', 'subgroup-wise-results')">Adenoviral Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Aspiration Pneumonia', 'subgroup-wise-results')">Aspiration Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('C. psittaci Pneumonia', 'subgroup-wise-results')">C. psittaci Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Community Acquired Pneumonia (CAP)', 'subgroup-wise-results')">Community Acquired Pneumonia (CAP)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Culture Negative Pneumonia', 'subgroup-wise-results')">Culture Negative Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Culture Positive Pneumonia', 'subgroup-wise-results')">Culture Positive Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Hospital Acquired Pneumonia (HAP)', 'subgroup-wise-results')">Hospital Acquired Pneumonia (HAP)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Hospital Acquired Pneumonia (HAP), ARDS', 'subgroup-wise-results')">Hospital Acquired Pneumonia (HAP), ARDS</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('M. pneumoniaePneumonia', 'subgroup-wise-results')">M. pneumoniaePneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Pediatric Pneumonia', 'subgroup-wise-results')">Pediatric Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Pneumocystis Pneumonia', 'subgroup-wise-results')">Pneumocystis Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Pneumonia', 'subgroup-wise-results')">Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Pneumonia Indeterminate', 'subgroup-wise-results')">Pneumonia Indeterminate</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Post Treatment M. pneumoniae Pneumonia', 'subgroup-wise-results')">Post Treatment M. pneumoniae Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Pre Treatment M. pneumoniae Pneumonia', 'subgroup-wise-results')">Pre Treatment M. pneumoniae Pneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('S. PneumoniaePneumonia', 'subgroup-wise-results')">S. PneumoniaePneumonia</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Ventilator Associated Pneumonia(VAP)', 'subgroup-wise-results')">Ventilator Associated Pneumonia(VAP)</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Pneumonitis</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Checkpoint Inhibitor Pneumonitis(CIP)', 'subgroup-wise-results')">Checkpoint Inhibitor Pneumonitis(CIP)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Chronic Hypersensitivity Pneumonitis (CHP)', 'subgroup-wise-results')">Chronic Hypersensitivity Pneumonitis (CHP)</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Pulmonary Hypertension</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Chronic Thromboembolic Pulmonary Hypertension (CTEPH)', 'subgroup-wise-results')">Chronic Thromboembolic Pulmonary Hypertension (CTEPH)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Pulmonary Arterial Hypertension (PAH)', 'subgroup-wise-results')">Pulmonary Arterial Hypertension (PAH)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Pulmonary Hypertension', 'subgroup-wise-results')">Pulmonary Hypertension</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Sarcoidosis</td>
                    <td class="even" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Sarcoidosis', 'subgroup-wise-results')">Sarcoidosis</a>
                    </td>
                </tr>
                <tr>
                    <td class="row_heading">Tuberculosis</td>
                    <td class="odd" style="text-align:left;">
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Cured Tuberculosis Group', 'subgroup-wise-results')">Cured Tuberculosis Group</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Drug Sensitive Tuberculosis', 'subgroup-wise-results')">Drug Sensitive Tuberculosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Drug Sensitive Tuberculosis After 6 Month Treatment', 'subgroup-wise-results')">Drug Sensitive Tuberculosis After 6 Month Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Month Treatment Tuberculosis', 'subgroup-wise-results')">Month Treatment Tuberculosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Multi Drug Resistant Tuberculosis', 'subgroup-wise-results')">Multi Drug Resistant Tuberculosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Multidrug Resistant Tuberculosis After 6 Month Treatment', 'subgroup-wise-results')">Multidrug Resistant Tuberculosis After 6 Month Treatment</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Mycobacterium africanum', 'subgroup-wise-results')">Mycobacterium africanum</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Mycobacterium tuberculosis', 'subgroup-wise-results')">Mycobacterium tuberculosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Nontuberculous Mycobacteria (NTM)', 'subgroup-wise-results')">Nontuberculous Mycobacteria (NTM)</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Treated Tuberculosis Group', 'subgroup-wise-results')">Treated Tuberculosis Group</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Tuberculosis', 'subgroup-wise-results')">Tuberculosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Tuberculosis Latently Infected', 'subgroup-wise-results')">Tuberculosis Latently Infected</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Tuberculosis Positive', 'subgroup-wise-results')">Tuberculosis Positive</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Untreated Tuberculosis Group', 'subgroup-wise-results')">Untreated Tuberculosis Group</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Week Treatment Tuberculosis', 'subgroup-wise-results')">Week Treatment Tuberculosis</a>
                        <a style="color:#003325;" href="#subgroup-wise-results-dummy" onclick="getSubgroupBioProjects('Zero Day Tuberculosis', 'subgroup-wise-results')">Zero Day Tuberculosis</a>
                    </td>
                </tr>
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
