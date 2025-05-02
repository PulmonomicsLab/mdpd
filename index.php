<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
        <script type = "text/javascript"  src='js/jquery-svg3dtagcloud-plugin/jquery.svg3dtagcloud.min.js'></script>
        <script type = "text/javascript" src = "js/advance_search_input.js"></script>
        <script>
            function plot3DInteractiveSVG() {
                var entries = [
                    {image: 'resource/text_icons/ARDS.png', height:20, width:120, url: 'disease.php?key=Acute Respiratory Distress Syndrome (ARDS)', target: '_blank', tooltip: 'Group: Acute Respiratory Distress Syndrome (ARDS)'},
                    {image: 'resource/text_icons/Asthma.png', height:20, width:120, url: 'disease.php?key=Asthma', target: '_blank', tooltip: 'Group: Asthma'},
                    {image: 'resource/text_icons/ACO.png', height:20, width:120, url: 'disease.php?key=Asthma-COPD Overlap (ACO)', target: '_blank', tooltip: 'Group: Asthma-COPD Overlap'},
                    {image: 'resource/text_icons/Bronchiectasis.png', height:20, width:120, url: 'disease.php?key=Bronchiectasis', target: '_blank', tooltip: 'Group: Bronchiectasis'},
                    {image: 'resource/text_icons/Bronchiolitis.png', height:20, width:120, url: 'disease.php?key=Bronchiolitis', target: '_blank', tooltip: 'Group: Bronchiolitis'},
                    {image: 'resource/text_icons/Bronchitis.png', height:20, width:120, url: 'disease.php?key=Bronchitis', target: '_blank', tooltip: 'Group: Bronchitis'},
                    {image: 'resource/text_icons/COPD.png', height:20, width:120, url: 'disease.php?key=Chronic Obstructive Pulmonary Disease (COPD)', target: '_blank', tooltip: 'Group: Chronic Obstructive Pulmonary Disease (COPD)'},
                    {image: 'resource/text_icons/CBA.png', height:20, width:120, url: 'disease.php?key=COPD-Bronchiectasis Association (CBA)', target: '_blank', tooltip: 'Group: COPD-Bronchiectasis Association (CBA)'},
                    {image: 'resource/text_icons/COVID-19.png', height:20, width:120, url: 'disease.php?key=COVID-19', target: '_blank', tooltip: 'Group: COVID-19'},
                    {image: 'resource/text_icons/Cystic_Fibrosis.png', height:20, width:120, url: 'disease.php?key=Cystic Fibrosis', target: '_blank', tooltip: 'Group: Cystic Fibrosis'},
                    {image: 'resource/text_icons/Healthy.png', height:20, width:120, url: 'disease.php?key=Healthy', target: '_blank', tooltip: 'Group: Healthy'},
                    {image: 'resource/text_icons/IPF.png', height:20, width:120, url: 'disease.php?key=Idiopathic Pulmonary Fibrosis (IPF)', target: '_blank', tooltip: 'Group: Idiopathic Pulmonary Fibrosis (IPF)'},
                    {image: 'resource/text_icons/ILD.png', height:20, width:120, url: 'disease.php?key=Interstitial Lung Disease (ILD)', target: '_blank', tooltip: 'Group: Interstitial Lung Disease (ILD)'},
                    {image: 'resource/text_icons/Lung_Cancer.png', height:20, width:120, url: 'disease.php?key=Lung Cancer', target: '_blank', tooltip: 'Group: Lung Cancer'},
                    {image: 'resource/text_icons/OPI.png', height:20, width:120, url: 'disease.php?key=Other Pulmonary Infections', target: '_blank', tooltip: 'Group: Other Pulmonary Infections'},
                    {image: 'resource/text_icons/Pneumonia.png', height:20, width:120, url: 'disease.php?key=Pneumonia', target: '_blank', tooltip: 'Group: Pneumonia'},
                    {image: 'resource/text_icons/Pneumonitis.png', height:20, width:120, url: 'disease.php?key=Pneumonitis', target: '_blank', tooltip: 'Group: Pneumonitis'},
                    {image: 'resource/text_icons/Pulmonary_Hypertension.png', height:20, width:200, url: 'disease.php?key=Pulmonary Hypertension', target: '_blank', tooltip: 'Group: Pulmonary Hypertension'},
                    {image: 'resource/text_icons/Sarcoidosis.png', height:20, width:120, url: 'disease.php?key=Sarcoidosis', target: '_blank', tooltip: 'Group: Sarcoidosis'},
                    {image: 'resource/text_icons/Tuberculosis.png', height:20, width:120, url: 'disease.php?key=Tuberculosis', target: '_blank', tooltip: 'Group: Tuberculosis'},
                    {image: 'resource/text_icons/Anus.png', height:20, width:120, url: 'biom.php?key=Anus', target: '_blank', tooltip: 'Biome: Anus'},
                    {image: 'resource/text_icons/Gut.png', height:20, width:120, url: 'biom.php?key=Gut', target: '_blank', tooltip: 'Biome: Gut'},
                    {image: 'resource/text_icons/Large_Intestine.png', height:20, width:120, url: 'biom.php?key=Large Intestine', target: '_blank', tooltip: 'Biome: Large Intestine'},
                    {image: 'resource/text_icons/LRT.png', height:20, width:120, url: 'biom.php?key=Lower Respiratory Tract', target: '_blank', tooltip: 'Biome: Lower Respiratory Tract'},
                    {image: 'resource/text_icons/Lung.png', height:20, width:120, url: 'biom.php?key=Lung', target: '_blank', tooltip: 'Biome: Lung'},
                    {image: 'resource/text_icons/Nasal.png', height:20, width:120, url: 'biom.php?key=Nasal', target: '_blank', tooltip: 'Biome: Nasal'},
                    {image: 'resource/text_icons/Oral.png', height:20, width:120, url: 'biom.php?key=Oral', target: '_blank', tooltip: 'Biome: Oral'},
                    {image: 'resource/text_icons/Rectum.png', height:20, width:120, url: 'biom.php?key=Rectum', target: '_blank', tooltip: 'Biome: Rectum'},
                    {image: 'resource/text_icons/Stomach.png', height:20, width:120, url: 'biom.php?key=Stomach', target: '_blank', tooltip: 'Biome: Stomach'},
                    {image: 'resource/text_icons/URT.png', height:20, width:120, url: 'biom.php?key=Upper Respiratory Tract', target: '_blank', tooltip: 'Biome: Upper Respiratory Tract'},
                    {image: 'resource/text_icons/Bacteria.png', height:20, width:120, url: 'taxa_domain.php?key=Bacteria', target: '_blank', tooltip: 'Domain: Bacteria'},
                    {image: 'resource/text_icons/Viruses.png', height:20, width:120, url: 'taxa_domain.php?key=Viruses', target: '_blank', tooltip: 'Domain: Viruses'},
                    {image: 'resource/text_icons/Eukaryota.png', height:20, width:120, url: 'taxa_domain.php?key=Eukaryota', target: '_blank', tooltip: 'Domain: Eukaryota'},
                    {image: 'resource/text_icons/Archaea.png', height:20, width:120, url: 'taxa_domain.php?key=Archaea', target: '_blank', tooltip: 'Domain: Archaea'}
                ];
                var settings = {
                    entries: entries,
                    width: 500,
                    height: 500,
                    radius: '75%',
//                     radiusMin: 70,
                    bgDraw: false,
                    opacityOver: 1.00,
                    opacityOut: 0.2,
                    opacitySpeed: 10,
                    fov: 800,
                    speed: 0.2,
                    tooltipFontFamily: 'Nunito Sans, Arial, sans-serif',
                    tooltipFontSize: '12',
                    tooltipFontColor: '#111',
                    tooltipFontWeight: 'normal',
                    tooltipFontStyle: 'normal',
                    tooltipFontStretch: 'normal',
                    tooltipFontToUpperCase: false,
                    tooltipTextAnchor: 'left',
                    tooltipDiffX: 10,
                    tooltipDiffY: 20,
                    animatingSpeed: 0.1,
                    animatingRadiusLimit: 1.3
                };
                var svg3DTagCloud = new SVG3DTagCloud(document.getElementById('interactive_plot_div'), settings);
            }
        </script>
        <style>
            body{
                background-color: #ffffff;
            }
            .intro{
                width:48%;
                margin: 0 1% 0 1%;
                float: left;
            }
        </style>
    </head>
    <body>
        <div class = "section_header">
            <center><p class="title">MDPD - Microbiome Database of Pulmonary Diseases</p></center>
        </div>

        <div class = "section_menu">
            <center>
            <table cellpadding="3px">
                <tr class="nav">
                    <td class="nav"><a href="#" class="active">Home</a></td>
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
<!--             <br/> -->
            <div class="intro">
                <p style="margin-top:10px; font-size:1.05em">
                    <b>Microbiome Database for Pulmonary Diseases (MDPD)</b> is a comprehensive
                    resource for analyzing human microbiome data related to various pulmonary
                    diseases. MDPD is developed using thorough <b>manual curation of sample
                    metadata</b><!-- from <b>461 BioProjects</b> to obtain <b>74,737 runs</b>-->. MDPD
                    contains data of <b>19 diseases and a healthy group</b> from <b>10 human
                    body sites</b>. MDPD includes - (i) Microbial <b>taxonomic profile</b>
                    analysis, (ii) <b>Discriminant</b> analysis, (iii) <b>Multivariate association</b>
                    analysis, (iv) <b>Microbial co-occurrence</b> network analysis, (v) Disease
                    <b>subtypes comparison</b> or between <b>disease subtype vs healthy groups</b>,
                    and (vi) Graphical representation of <b>each taxa profile across disease
                    subtypes and human body sites</b>.
                </p>

                <center><h3 style="margin-bottom:5px;">Search MDPD</h3></center>
                <form method="post" action="advance_search_result.php">
                    <table class="form" id="form_input_table" border="0" align="center">
                        <tr class="input_row">
                            <td style="width:10%;"></td>
                            <td style="width:10%;"><input type="hidden" name="lo0" value="" /></td>
                            <td style="width:30%;">
                                <select class="full" id="k0" name="k0" onchange="updateKeyChoice(this)">
                                    <option value="Disease" selected>Disease</option>
                                    <option value="AssayType">Assay Type</option>
                                    <option value="Biome">Body site</option>
                                    <option value="LibraryLayout">Library Layout</option>
                                    <option value="Country">Country</option>
                                    <option value="Year">Year</option>
                                </select>
                            </td>
                            <td style="width:10%;">
                               <!--<select class="full" id="op0" name="op0">
                                   <option value="=" selected>=</option>
                                   <option value="<">&lt;</option>
                                   <option value="<=">&lt;=</option>
                                   <option value=">">&gt;</option>
                                   <option value=">=">&gt;=</option>
                               </select>-->
                            </td>
                            <td style="width:40%;">
<!--                                <input class="full" type="text" id="v0" name="v0" placeholder="Enter search keyword"> -->
                            </td>
                        </tr>
                        <script>updateKeyChoice(document.getElementById('k0'));</script>
                    </table>
                    <input type="hidden" id="total_count" name="total_count" value="1" />
                    <script>document.getElementById('total_count').value = 1;</script>
                    <table border="0" align="center" style="margin-top:10px;">
                        <tr>
                            <td style="text-align:right;"><button type="button" style="width:180px;margin:0px;" onclick="addRow()">Add search predicate</button></td>
                            <td style="text-align:left;"><button type="button" style="width:180px;margin:0px;" onclick="deleteRow()">Delete search predicate</button></td>
                        </tr>
                        <tr>
                            <td style="text-align:right;//padding-right:100px;"><input type="submit" style="width:100px;margin:5px;" name="Submit" value="Submit" /></td>
                            <td style="text-align:left;//padding-left:100px;"><button type="reset" style="width:100px;margin:5px;" value="Reset">Reset</button></td>
                        </tr>
                    </table>
                </form>

                <center><h3 style="margin-bottom:5px;">Data summary</h3></center>
                <img src="resource/home_figures/home_icons.svg" style="width:100%; max-height:120px; max-width:100%;" />
            </div>
            <div class="intro">
                <center>
                    <div id="interactive_plot_div" style="width:100%;"></div>
                    <p style="margin:0;">Interactive plot showing the different groups, body sites, and taxa domains in MDPD.</p>
                    <p style="margin:0; font-size:0.9em;"><i>(Please click on the text in plot to get details)</i></p>
                </center>
            </div>
            <div style="clear:both;"></div>

            <hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        plot3DInteractiveSVG();
    </script>
    <style>
        @media screen and (max-width: 1250px) {
            .intro {
                width: 80%;
                margin: 30px 10% 0 10%;
            }
        }
    </style>
</html> 
