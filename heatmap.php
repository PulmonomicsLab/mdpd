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
        <title>Home - MDPD</title>
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
            <div style="width:100%;" id="plot_container">
            
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
                            plotHeatmap('plot_container', this.responseText, assayType, score);
                        }
                    };
                    xmlhttp.open('GET', file, true);
                    xmlhttp.setRequestHeader("Content-type", "text/csv");
                    xmlhttp.send();
                }
                
                <?php echo "getHeatmapData('".$type."','".$bioproject."','".$dp."','".$at."','".$biome."','".$is."',0);"; ?>
            </script>
        </div>
    </body>
</html>
