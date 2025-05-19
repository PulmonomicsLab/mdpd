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
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
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
            <p style="margin-bottom:0; font-weight:bold;">A. Taxonomic composition</p>
            <div id="download_div_krona" style="width:100%; text-align:center; display:none;">
                <a id="download_button_krona">
                    <button type="button" style="margin:2px;">Download Krona data</button>
                </a>
            </div>
            <iframe id="krona_frame" style="width:100%; height:600px; margin-top:5px;"></iframe>
            <p style="font-size:0.9em; margin-top:0;">
                Interactive <b>Krona plot</b> shows the hierarchical distribution
                of microbes, where the colors denote the abundances (for example
                the most abundant taxa is red). The taxonomic composition of
                different <b>subgroups can be selected</b> from the panel in
                upper left corner. The depth can be modified using the <b>"Max
                depth"</b> button on the upper left corner. The current view of
                the Krona plot can be downloaded as SVG by clicking on the
                <b>"Snapshot"</b> button at the upper left corner. The data can
                be downloaded using the <b>"Download Krona data"</b> button.
            <p>

            <p style="margin-bottom:0; font-weight:bold;">B. Top 10 taxa distribution across subgroup(s)</p>
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
            <p id="box_legend" style="font-size:0.9em; margin-top:5px; display:none;">
                Interactive <b>box plot</b> shows the abundance of top 10 taxa
                across the subgroup(s). <b>Hover mouse on a box</b> to highlight
                the mean, median, maximum, minimum and inter quartile range.
                <b>Click on each subgroup on the legend</b> to select or deselect
                the subgroup(s). <b>Click on the buttons</b> below the plot to get
                the information of the respective taxa. The plot can be downloaded
                as SVG by clicking on the <b>"
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                </svg>
                "</b> button in the figure menu bar at the top right corner. The
                data can be downloaded using the <b>"Download figure data"</b>
                button.
            </p>

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

