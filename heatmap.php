<?php
    $type = urldecode($_GET["type"]);
    $bioproject = urldecode($_GET["bioproject"]);
    $dp = urldecode($_GET["dp"]);
    $at = urldecode($_GET["at"]);
    $biome = urldecode($_GET["biome"]);
    $is = urldecode($_GET["is"]);
//     echo $type."<br/>".$bioproject."<br/>".$dp."<br/>".$at."<br/>".$is."<br/>".$biome."<br/>";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Heatmap - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_heatmap.js"></script>
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
            <center><p id="display_text"></p></center>
            <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div>
            <div style="width:100%;" id="plot_container">
            <p>
                <b><i>N.B. -</i></b> <b>1)</b> A cutoff of <i>log<sub>10</sub>(LDA score) &ge; 3</i>
                was used to determine the differential markers. <b>2)</b> The normalized abundance
                values in the heatmap are rounded off to 3 digits after the decimal point. <b>3)</b>
                The data can be downloaded by clicking on the <i>"Download data"</i> button located
                at the top of the page. <b>4)</b> The heatmap can be downloaded as a SVG image by
                clicking on the <i>"Export as SVG"</i>
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                </svg>
                button in the menubar located at the top
                right corner of the plot.
            </p>
            </div><br/>
            
            <script>
                function getHeatmapData(queryType, bioproject, diseasePair, assayType, biome, isolationSource, score) {
                    var prefix = 'input/Heatmap/';
//                     if(queryType == 'DISEASE') {
                        var folder = prefix + assayType + '/';
                        var file = folder + 'Heatmap_' + diseasePair.replace(/ /g,"_") + '_' + isolationSource.replace(/ /g,"_") + '.csv';
                        var display = diseasePair.replace(/_/g," - ") + ' | ' + assayType + ' | ' + biome + ' | ' + isolationSource;
//                     } else if(queryType == 'BIOPROJECT') {
//                         var folder = prefix + 'Bioproject/' ;
//                         var file = folder + 'LDA_' + bioproject + '_' + isolationSource.replace(/ /g,"_") + '_' + assayType.replace(/ /g,"_") + '.csv';
//                         var display = 'BioProject ID: ' + bioproject + ' | ' + assayType + ' | ' + isolationSource;
//                     }                
//                     alert(queryType+'<br/>'+bioproject+'<br/>'+diseasePair+'<br/>'+assayType+'<br/>'+isolationSource+'<br/>'+'\n'+file);
                    
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById('display_text').innerHTML = '<h3>' + display + '</h3>';
                            document.getElementById('download_div').innerHTML = '<a href="' + file + '"><button type="button" style="margin:2px;">Download data</button></a>';
                            plotHeatmap('plot_container', this.responseText, diseasePair.replace(/_/g,"-"), assayType, biome, isolationSource.replace(/ /g,"_"), score);
                        }
                    };
                    xmlhttp.open('GET', file, true);
                    xmlhttp.setRequestHeader("Content-type", "text/csv");
                    xmlhttp.send();
                }
                
                <?php echo "getHeatmapData('".$type."','".$bioproject."','".$dp."','".$at."','".$biome."','".$is."',0);"; ?>
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
