<?php
    $bioproject = (isset($_GET["key"])) ? urldecode($_GET["key"]) : "";
    $at = (isset($_GET["at"])) ? urldecode($_GET["at"]) : "";
    $is = (isset($_GET["is"])) ? urldecode($_GET["is"]) : "";

    $confounder_json = json_decode(file_get_contents("input/bioproject_confounder_list.json"), true);
    if (array_key_exists($bioproject, $confounder_json["confounders"])) {
        $confounders = $confounder_json["confounders"][$bioproject];
        $display_text = "Multivariate association analysis - ".$bioproject." | ".$at." | ".$is." | Confounders = [".$confounders."]";
    } else {
        $confounders = "";
        $display_text = "Multivariate association analysis - ".$bioproject." | ".$at." | ".$is." | No confounders";
    }

    $dataJSON = json_encode(
        array(
            "bioproject" => $bioproject,
            "at" => $at,
            "is" => $is,
            "confounders" => $confounders
        )
    );
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Multivariate association analysis - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_covariate.js"></script>
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
            <p style="margin:0 0 10px 0; font-size:1.2em; font-weight:bold; text-align:center;"><?php echo $display_text; ?></p>
            <div id="download_div" style="width:100%; text-align:center; display:none;">
                <a id="download_button" download="multivariate_figure_data.tsv">
                    <button type="button" style="margin:2px;">Download figure data</button>
                </a>
            </div>
            <div id="covariate_plot_div" style="width:70%; margin:0 15% 0 15%;">
                <center><img style="height:300px;" src="resource/loading.gif" /></center>
            </div>
            <p id="taxa_button_group_heading" style="margin:3px; font-weight:bold; display:none;">Taxa details</p>
            <div id="taxa_button_group" style="width:100%; background-color:#fff9e6; border:1px dashed #004d99; display:none;"></div>
            <p id="covariate_legend" style="font-size: 0.9em; margin-top:5px; display:none;">
                <b>Heatmap</b> shows the association of microbes with the covariates
                such as age-groups, gender and smoking status. Each cell of the
                heatmap may be annotated with asterisks denoting the significance
                based on FDR-adjusted p-values (<i>*** denotes p-value &lt; 0.001</i>,
                <i>** denotes p-value &lt; 0.01</i>, <i>* denotes p-value &lt; 0.05</i>,
                <i>no asterisks denote p-value &gt; 0.05</i>). <b>Hover mouse</b> on each
                cell to highlight the taxa name, covariate and the MaAsLin2 coefficient.
                <b>Click on the buttons</b> below the plot to get the information of
                the respective taxa. The plot can be downloaded as SVG by clicking on the <b>"
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                </svg>
                "</b> button in the figure menu bar at the top right corner. The
                data can be downloaded using the <b>"Download figure data"</b>
                button.
                <br/>
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
        <?php echo "getCovariateData('covariate_plot_div', '".$dataJSON."');"; ?>
    </script>
</html>
