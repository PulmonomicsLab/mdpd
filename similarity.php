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
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_similarity.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script>
<!--         <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script> -->
        <script src="https://unpkg.com/@upsetjs/bundle"></script>
        <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
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
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->

        <div class = "section_middle">
            <center><p id="display_text"></p></center>
            <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div>
            <center><div style="width:100%; background-color: #fff0f5; overflow: auto;" id="upset_plot_container"></div></center><br/><br/>
            <center><div style="width:100%; background-color: #fff0f5; height:400px;" id="likert_plot_container"></div></center>

            <script>
                function getSimilarityData(disease, assayType, isolationSource) {
                    var is1 = isolationSource.split('_')[0];
                    var is2 = isolationSource.split('_')[1];
                    var prefix = 'input/Similarity/';
                    var file = prefix + assayType + '/' + disease + '/' + disease + '_' + isolationSource.replace(/ /g,"_") + '.csv';
                    var display = disease + ' | ' + assayType + ' | ' + 'Lung (' + is1 + ') - Gut (' + is2 + ')';

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById('display_text').innerHTML = '<h3>' + display + '</h3>';
                            document.getElementById('download_div').innerHTML = '<a href="' + file + '"><button type="button" style="margin:2px;">Download data</button></a>';
                            plotCharts('upset_plot_container', 'likert_plot_container', this.responseText);
//                             document.getElementById('download_div').innerHTML = is1 + is2;
                        }
                    };
                    xmlhttp.open('GET', file, true);
                    xmlhttp.setRequestHeader("Content-type", "text/csv");
                    xmlhttp.send();
                }

                <?php echo "getSimilarityData('".$ds."','".$at."','".$is."');"; ?>
            </script>
        </div>
<!--         <center><div style="width:100%; background-color: #fff0f5; height:600px;" id="foo"></div></center> -->
    </body>
</html>

