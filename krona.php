<?php
    $type = urldecode($_GET["type"]);
    $bioproject = urldecode($_GET["bioproject"]);
    $ds = urldecode($_GET["ds"]);
    $at = urldecode($_GET["at"]);
    $is = urldecode($_GET["is"]);
//     echo $type."<br/>".$bioproject."<br/>".$ds."<br/>".$at."<br/>".$is;

    $noRunWiseBioProjects = array("BIOPROJECT_PRJEB9033_COPD_WMS", "BIOPROJECT_PRJEB9033_Asthma_WMS", "BIOPROJECT_PRJNA322414_COPD_Amplicon");
    $runWiseRequired = (array_search($type."_".$bioproject."_".$ds."_".$at, $noRunWiseBioProjects) === FALSE);
    $runwiseMessage = "";
    if (!$runWiseRequired)
        $runwiseMessage = "<p>Only one run present for ".$bioproject." - ".$ds." - ".$at.". No run-wise krona possible.</p>";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Krona - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <style>
            .side_nav_div{
                display : block;
                padding : 15px 5px 15px 10px;
                margin: 10px 0 10px 0;
                font-size: 16px;
                font-weight: bold;
                text-decoration : none;
                color : white;
                background-color: #51414f;
                border: 2px solid black;
                box-shadow: 0px 0px 10px grey;
                cursor: pointer;
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
        
        <div class = "section_left" id="section_left">
            <div class="side_nav_div" id="merged_div" style="width:100%;" onclick="<?php echo "getKronaData('".$type."','".$bioproject."','".$ds."','".$at."','".$is."','Merged')" ?>">Merged</div>
            <?php
                if(!$runWiseRequired)
                    echo "<div class=\"side_nav_div\" id=\"runwise_div\" style=\"width:100%;\">Run-wise</div>";
                else
                    echo "<div class=\"side_nav_div\" id=\"runwise_div\" style=\"width:100%;\" onclick=\"getKronaData('".$type."','".$bioproject."','".$ds."','".$at."','".$is."','Runwise')\">Run-wise</div>";
                if($runwiseMessage !== "")
                    echo $runwiseMessage;
            ?>
        </div>
        
        <script>
            window.onscroll = function() {makeSticky()};
            var header = document.getElementById("section_left");
            var sticky = header.offsetTop;
            function makeSticky() {
                if (window.pageYOffset > sticky) {
                    header.classList.add("sticky");
                } else {
                    header.classList.remove("sticky");
                }
            }
        </script>
        
        <div class = "section_middle" style="width:72%;">
            <center><p id="display_text"></p></center>
            <iframe id="krona_frame" style="width:100%; height:600px;"></iframe>
            <?php
                if ($at === "WMS")
                    echo "<p>N.B. - <b>1)</b> For Amplicon data, Krona allows visualization upto genus
                    level. <b>2)</b> For WMS data, Krona allows visualization upto species level.
                    <b>3)</b> The depth can be modified using the \"Max depth\" button on the upper
                    left corner. <b>4)</b> For better view, please view the plot at <i>\"Max depth\"
                    = 7</i>. <b>5)</b> The current view of the Krona plot can be downloaded as a SVG
                    image by clicking on the <i>\"Snapshot\"</i> button located at the upper left corner
                    of the plot.</p>";
                else
                    echo "<p>N.B. - <b>1)</b> For Amplicon data, Krona allows visualization upto genus
                    level. <b>2)</b> For WMS data, Krona allows visualization upto species level.
                    <b>3)</b> The depth can be modified using the \"Max depth\" button on the upper
                    left corner. <b>4)</b> For better view, please view the plot at <i>\"Max depth\"
                    = 6</i>. <b>5)</b> The current view of the Krona plot can be downloaded as a SVG
                    image by clicking on the <i>\"Snapshot\"</i> button located at the upper left corner
                    of the plot.</p>";
            ?>
        </div>
        <div style="clear:both;">
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
        
        <script>
            function getKronaData(queryType, bioproject, disease, assayType, isolationSource, kronaType) {
                var prefix = 'input/Krona/';
                if(queryType == 'DISEASE') {
                    var folder = prefix + assayType + '/' + disease + '/' ;
                    var file = folder + 'Krona_' + disease.replace(/ /g,"_") + '_' + isolationSource.replace(/ /g,"_") + '_' + assayType.replace(/ /g,"_") + '_' + kronaType.replace(/ /g,"_") + '.html';
                    var display = disease + ' | ' + assayType + ' | ' + isolationSource + ' (' + kronaType + ')';
                } else if(queryType == 'BIOPROJECT') {
                    var folder = prefix + assayType.split('_')[0] + '/' + queryType + '/' ;
                    var file = folder + 'Krona_' + bioproject.replace(/ /g,"_") + '_' + disease.replace(/ /g,"_") + '_' + assayType.replace(/ /g,"_") + '_' + kronaType.replace(/ /g,"_") + '.html';
                    var display = 'BioProject ID: ' + bioproject + ' - ' + disease + ' | ' + assayType.replace(/_/g, ' | ') + ' (' + kronaType + ')';
                }                
//                 alert(queryType+'<br/>'+bioproject+'<br/>'+disease+'<br/>'+assayType+'<br/>'+isolationSource+'<br/>'+kronaType+'\n'+file);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
//                         alert(this.responseText);
                        var frame = document.getElementById('krona_frame');
                        frame.contentWindow.document.open();
                        frame.contentWindow.document.write(this.responseText);
                        frame.contentWindow.document.close();
                        
                        document.getElementById('display_text').innerHTML = '<h3>' + display + '</h3>';
                        
                        if(kronaType == 'Merged') {
                            document.getElementById('merged_div').style.borderWidth = '4px';
                            document.getElementById('runwise_div').style.borderWidth = '2px';
                        } else {
                            document.getElementById('merged_div').style.borderWidth = '2px';
                            document.getElementById('runwise_div').style.borderWidth = '4px';
                        }
                    }
                };
                xmlhttp.open('GET', file, true);
                xmlhttp.setRequestHeader('Content-type', 'text/html');
                xmlhttp.send();
            }
            
            <?php echo "getKronaData('".$type."','".$bioproject."','".$ds."','".$at."','".$is."','Merged');"; ?>
        </script>
    </body>
</html>
