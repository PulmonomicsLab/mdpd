<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
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
                    <td class="nav"><a href="advance_search.html" class="side_nav">Search</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>
        
        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <?php
                $bioprojectID = urldecode($_GET["key"]);
                $subGroup = urldecode($_GET["sg"]);
                echo "<center><h3>BioProject ID: ".$bioprojectID." - ".$subGroup."</h3></center>";
            ?>
            <iframe src="<?php echo "input/Krona/".$bioprojectID."/".$bioprojectID."_".$subGroup.".html"; ?>" style="width:100%; height:800px;float:left;"></iframe>
        </div>
    </body>
</html>
