<?php
    $bioproject = urldecode($_GET["key"]);
    $at = urldecode($_GET["at"]);
    $is = urldecode($_GET["is"]);

    $command = "Rscript R/bioproject_taxa_distribution.R \"".$bioproject."\" \"".$at."\" \"".$is."\" 2>&1";
//     echo "<pre>".$command."</pre>\n";

    exec($command, $out, $status);
//     echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//     echo $out[0]."<br/>";  // for checking output only
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Taxonomic profile - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_box.js"></script>
        <script type = "text/javascript" src = "js/plot_krona.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script>
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
            <div id="box_plot_div" style="width:100%;"></div>

            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "getKronaData('".$bioproject."','".$at."','".$is."','subgroup');"; ?>
        <?php echo "plotBox('box_plot_div', '".$out[0]."');"; ?>
    </script>
</html>

