<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>About - MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <style>
            .intro{
                width:80%;
                height:auto;
                margin:0 10% 0 10%;
/*                 font-size: 1.2em; */
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
                    <td class="nav"><a href="index.php" class="side_nav">Home</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="analysis.php" class="side_nav">Analysis</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="#" class="active">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->

        <div class = "section_middle">
            <!--<p class="intro">
                MDPD is a manually curated comprehensive database of human microbiomes of
                pulmonary diseases.
            </p><br/>-->

            <div class="intro" id="sec-1"><h3 style="margin:10px 0 5px 0;">1. Data Summary</h3></div>
            <ul class="intro">
                <li><b>Amplicon-16S</b>, <b>Amplicon-ITS</b>, and <b>whole metagenome sequencing</b>.</li>
                <li><b>430</b> BioProjects and <b>59,362</b> runs/samples.</li>
                <li><b>19</b> different pulmonary diseases and a <b>healthy</b> group.</li>
                <li><b>278 subgroups</b> different diseases and healthy.</li>
                <li><b>10</b> <b>human</b> microbiome <b>body sites</b>.</li>
                <li>
                    Microbial information of <b>Bacteria</b> <i>(n = 2296)</i>, <b>Eukaryota</b>
                    <i>(n = 219)</i>, <b>Virus</b> <i>(n = 193)</i>, and <b>Archaea</b> <i>(n = 13)</i>.
                </li>
            </ul>

            <br/>
            <div class="intro" id="sec-2"><h3 style="margin:10px 0 5px 0;">2. Features of MDPD</h3></div>
            <p class="intro" style="margin-bottom:0;">
                MDPD captures the dynamics of the microbes in different human body sites,
                including their,
            </p>
            <ul class="intro">
                <li><b>Composition</b> and <b>abundance</b>,</li>
                <li><b>Association</b> of the <b>microbes</b> with various <b>covariates</b> (age, gender, smoking status),</li>
                <li><b>Microbial markers</b> for different groups (<b>diseases/healthy</b>) and their <b>subgroups</b>,</li>
                <li>Cross-disease or healthy <b>subgroup comparisons</b>, and</li>
                <li><b>Microbial community</b> structure.</li>
            </ul>

            <br/>
            <div class="intro" id="sec-3"><h3 style="margin:10px 0 5px 0;">3. Enhancing data quality</h3></div>
            <ul class="intro">
                <li>
                    Systematically extracted and curated relevant metadata of the groups from public
                    databases (NCBI, ENA, PubMed) and related research articles.
                </li>
                <li>
                    <b>Re-analyses of the raw data</b> using state-of-the-art methods.
                </li>
                <li>
                    Implemented <b>rigorous quality control (QC)</b> methods and <b>stringent criteria</b> to ensure
                    only <b>high-quality data</b> was included.
                </li>
            </ul>

            <br/>
            <div class="intro" id="sec-4"><h3 style="margin:10px 0 5px 0;">4. Ensuring re-usability</h3></div>
            <ul class="intro">
                <li><b>BIOM files</b> (<code>.rds</code>) stored in the database.</li>
                <li>Curated <b>metadata</b> stored in the database.</li>
                <li>
                    Users can also <b>further analyze</b> the data, e.g., according to <b>country</b>,
                    <b>gender</b>, <b>age</b>, <b>smoking status</b>, and many other available metadata.
                </li>
            </ul>

            <br/>
            <div class="intro" id="sec-5"><h3 style="margin:10px 0 5px 0;">5. Data availability</h3></div>
            <ul class="intro">
                <li>A summary of BioProjects is available <a style="color:#003325;" href="resource/public/bioproject.csv">here</a>.</li>
                <li>
                    The source code of MDPD is available in a GitHub repository -
                    <a style="color:#003325;" target="_blank" href="https://github.com/PulmonomicsLab/mdpd">https://github.com/PulmonomicsLab/mdpd <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a>
                </li>
            </ul>

            <br/>
            <div class="intro" id="sec-6"><h3 style="margin:10px 0 5px 0;">6. Tools, libraries, and packages used</h3></div>
            <table class="details">
                <tr>
                    <th>Name</th>
                    <th>Tool/Package/Library</th>
                    <th>Version</th>
                    <th>Usage</th>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://github.com/FelixKrueger/TrimGalore">Trim galore <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>Perl wrapper</td>
                    <td>0.6.7</td>
                    <td style="text-align:left; padding-left:5px;">Quality trimming of the raw FastQ reads.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://www.bioconductor.org/packages/release/bioc/html/dada2.html">dada2 <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>1.26</td>
                    <td style="text-align:left; padding-left:5px;">Taxonomic inference from Amplicon-16S and Amplicon-ITS sequencing data.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://toolshed.g2.bx.psu.edu/view/iuc/kraken2/cdee7158adf3">Kraken2 <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>Tool</td>
                    <td>2.1.3</td>
                    <td style="text-align:left; padding-left:5px;">Taxonomic inference from Whole Metagenome Sequencing data.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://usegalaxy.eu/root?tool_id=toolshed.g2.bx.psu.edu/repos/iuc/bracken/est_abundance/3.1+galaxy0">Bracken <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>Tool</td>
                    <td>3.1</td>
                    <td style="text-align:left; padding-left:5px;">Re-estimating species' relative abundance at the level using kmer information from the Kraken2 report file.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://github.com/fbreitwieser/pavian">Pavian <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>Web application</td>
                    <td>1.0</td>
                    <td style="text-align:left; padding-left:5px;">To explore the classified taxonomic reads (%) from Whole Metagenome Sequencing data.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://www.bioconductor.org/packages/release/bioc/html/phyloseq.html">phyloseq <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>1.42.0</td>
                    <td style="text-align:left; padding-left:5px;">Manipulation of the .biom files.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://github.com/cpauvert/psadd">psadd <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>0.1.3</td>
                    <td style="text-align:left; padding-left:5px;">Generate interactive Krona plots using the Kronatools.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://cran.r-project.org/web/packages/file2meco/index.html">file2meco <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>0.7.1</td>
                    <td style="text-align:left; padding-left:5px;">Conversion of the Phyloseq object to a microtable object</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://cran.r-project.org/web/packages/microeco/index.html">microeco <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>1.8.0</td>
                    <td style="text-align:left; padding-left:5px;">
                        Detecting taxonomic abundance at the genus/species level. Microbial marker
                        identification using LEfSe (Kruskal-Wallis’ test and Wilcoxon rank-sum tests)
                        and edgeR (generalized linear models) with Benjamini-Hochberg (BH) method for
                        FDR correction.
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://www.bioconductor.org/packages/release/bioc/html/Maaslin2.html">Maaslin2 <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>1.12.0</td>
                    <td style="text-align:left; padding-left:5px;">Finding the association of the microbes with the covariates, such as age groups and gender, using linear mixed models.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://bioconductor.org/packages/release/data/experiment/html/bugphyzz.html">bugphyzz <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>1.0</td>
                    <td style="text-align:left; padding-left:5px;">Functional annotation of the identified microbes.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://cran.r-project.org/web/packages/taxonomizr/index.html">taxonomizr <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>0.11.1</td>
                    <td style="text-align:left; padding-left:5px;">Fetch the NCBI taxonomy information, such as taxonomy IDs.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://cran.r-project.org/web/packages/mboost/index.html">mboost <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>2.9.11</td>
                    <td style="text-align:left; padding-left:5px;">Model-based gradient boosting for generating co-occurrence networks.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://cran.r-project.org/web/packages/boot/index.html">boot <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>R package</td>
                    <td>1.3.30</td>
                    <td style="text-align:left; padding-left:5px;">Bootstrap Resampling for making the co-occurrence networks.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://github.com/plotly/plotly.js">Plotly.js <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>JavaScript library</td>
                    <td>3.0.1</td>
                    <td style="text-align:left; padding-left:5px;">Creating interactive plots.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://github.com/cytoscape/cytoscape.js">Cytoscape.js <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>JavaScript library</td>
                    <td>3.31.2</td>
                    <td style="text-align:left; padding-left:5px;">Building microbial co-occurrence networks.</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-left:5px;"><a style="color:#003325;" target="_blank" href="https://github.com/NiklasKnaack/jquery-svg3dtagcloud-plugin">SVG 3D Tag Cloud jQuery plugin <img src="resource/redirect-icon.png" height="14pt" width="auto" /></a></td>
                    <td>JavaScript library</td>
                    <td>-</td>
                    <td style="text-align:left; padding-left:5px;">Drawing the 3D interactive plot on the home page</td>
                </tr>
            </table>

            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
