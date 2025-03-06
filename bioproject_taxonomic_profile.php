<?php
    $bioproject = (isset($_GET["key"])) ?  urldecode($_GET["key"]) : "";
    $at = (isset($_GET["at"])) ? urldecode($_GET["at"]) : "";
    $is = (isset($_GET["is"])) ? urldecode($_GET["is"]) : "";

    $dataJSON = json_encode(
        array(
            "bioproject" => $bioproject,
            "at" => $at,
            "is" => $is
        )
    );
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Taxonomic profile - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_box.js"></script>
        <script type = "text/javascript" src = "js/plot_krona.js"></script>
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
            <p style="margin-top:0; font-size:1.2em; font-weight:bold; text-align:center;"><?php echo "Taxonomic profile - ".$bioproject." | ".$at." | ".$is; ?></p>
            <p style="margin-bottom:0; font-weight:bold;">A. Taxonomic composition (Krona plot)</p>
            <div id="download_div_krona" style="width:100%; text-align:center; display:none;">
                <a id="download_button_krona">
                    <button type="button" style="margin:2px;">Download Krona data</button>
                </a>
            </div>
            <iframe id="krona_frame" style="width:100%; height:600px; margin-top:5px;"></iframe>

            <p style="margin-bottom:0; font-weight:bold;">B. Top 10 taxa distribution across runs (Box plot)</p>
            <div id="download_div_taxa_distribution" style="width:100%; text-align:center; display:none;">
                <a id="download_button_taxa_distribution" download="top_taxa_across_runs_figure_data.csv">
                    <button type="button" style="margin:2px;">Download figure data</button>
                </a>
            </div>
            <div id="box_plot_div" style="width:100%;">
                <center><img style="height:300px;" src="resource/loading.gif" /></center>
            </div>
            <p id="taxa_button_group_heading" style="margin:3px; font-weight:bold; display:none;">Top taxa details</p>
            <div id="taxa_button_group" style="width:100%; background-color:#fff9e6; border:1px dashed #004d99; display:none;"></div>

            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "getKronaData('".$bioproject."','".$at."','".$is."','subgroup');"; ?>
        <?php echo "getBoxPlotData('box_plot_div', '".$dataJSON."');"; ?>
    </script>
</html>

