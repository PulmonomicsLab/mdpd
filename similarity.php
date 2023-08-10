<?php
    $ds = urldecode($_GET["ds"]);
    $at = urldecode($_GET["at"]);
    $is = urldecode($_GET["is"]);
    $is1 = substr($is, 0, strpos($is, "_"));
    $is2 = substr($is, strpos($is, "_")+1, strlen($is));
//     echo $ds."<br/>".$at."<br/>".$is."<br/>".$is1."<br/>".$is2."<br/>";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Similarity - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_similarity.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script>
<!--         <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script> -->
        <script src="https://unpkg.com/@upsetjs/bundle"></script>
        <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

        <style>
            .caption{
                text-align: center;
                font-size: 1.2em;
                margin: 5px 0 5px 0;
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
            <center><p id="display_text"></p></center>
            <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div>
            <center><div style="width:100%; background-color: #ffffff; overflow: auto;" id="upset_plot_container"></div></center>
            <div class="caption">
                UpSet plot showing the number of prevalent taxa in lung microbiome, gut microbiome and shared in both microbiomes.
            </div>
            <div id="plot-footer1" style="margin:5px 0 0 5px;"></div><br/>
            <center><div style="width:100%; background-color: #ffffff; height:400px;" id="likert_plot_container"></div></center>
            <div class="caption">
                Likert plot showing prevalence values of the shared taxa between lung and gut microbiomes.
            </div>
            <p>
                <br/>
                <b><i>N.B. -</i></b> <b>1)</b> The UpSet plot can be downloaded as a SVG image
                by clicking on the <i>"Download UpSet plot"</i> button located at the top of
                the page. <b>2)</b> The Likert plot can be downloaded as a SVG image by clicking
                on the <i>"Download Likert plot"</i> button located at the top of the page.
            </p>

            <script>
                function createDownloadLink(plotType) {
                    if(plotType == 'upset')
                        var svg = document.getElementById(plotType + '_plot_container').firstElementChild;
                    else
                        var svg = document.getElementById(plotType + '_plot_container').firstElementChild.firstElementChild;
                    var svgCode = new XMLSerializer().serializeToString(svg);
                    var blob = new Blob([svgCode], {type: 'image/svg+xml'});
                    var link = document.getElementById(plotType + '_download');
                    link.href = URL.createObjectURL(blob);
                }

                function getSimilarityData(disease, assayType, isolationSource) {
                    var is1 = isolationSource.split('_')[0];
                    var is2 = isolationSource.split('_')[1];
                    var prefix = 'input/Similarity/';
                    var file = prefix + assayType + '/' + disease + '/' + disease.replace(/ /g,"_") + '_' + isolationSource.replace(/ /g,"_") + '.csv';
                    var display = disease + ' | ' + assayType + ' | ' + 'Lung (' + is1 + ') - Gut (' + is2 + ')';
                    var upset_export_filename = 'upset_plot_' + assayType + '_' + disease.replace(/ /g,"_") + '_' + isolationSource.replace(/ /g,"_") + '.svg';
                    var likert_export_filename = 'likert_plot_' + assayType + '_' + disease.replace(/ /g,"_") + '_' + isolationSource.replace(/ /g,"_") + '.svg';

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById('display_text').innerHTML = '<h3>' + display + '</h3>';
                            document.getElementById('download_div').innerHTML = '<a style="margin:10px;" href="' + file + '"><button type="button" style="margin:2px;">Download data</button></a>' +
                                                                                '<a style="margin:10px;" id="upset_download" onclick="createDownloadLink(\'upset\')" download="' + upset_export_filename + '"><button type="button" style="margin:2px;">Download UpSet plot</button></a>' +
                                                                                '<a style="margin:10px;" id="likert_download" onclick="createDownloadLink(\'likert\')" download="' + likert_export_filename + '"><button type="button" style="margin:2px;">Download Likert plot</button></a>';
                            plotCharts('upset_plot_container', 'likert_plot_container', this.responseText);
//                             document.getElementById('download_div').innerHTML = is1 + is2;
                            if (is1 == 'Endotracheal Aspirate' || is2 == 'Endotracheal Aspirate')
                                document.getElementById('plot-footer1').innerHTML = '<i>EA</i> = Endotracheal Aspirate';
                        }
                    };
                    xmlhttp.open('GET', file, true);
                    xmlhttp.setRequestHeader("Content-type", "text/csv");
                    xmlhttp.send();
                }

                <?php echo "getSimilarityData('".$ds."','".$at."','".$is."');"; ?>

            </script>
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>

