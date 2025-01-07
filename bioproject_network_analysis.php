<?php
    $bioproject = urldecode($_GET["key"]);
    $at = urldecode($_GET["at"]);
    $is = urldecode($_GET["is"]);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Network analysis - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script type = "text/javascript" src = "js/plot_network.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.30.4/cytoscape.min.js" integrity="sha512-xpXUCbrkyJCwC5noJXZqec9rSXdRgFfDoL7Q9/pCPe54Y04OlqwPobMcNuA5NPJ7DRR51OIr6xcOH2wUspyKDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://unpkg.com/cytoscape-svg/cytoscape-svg.js"></script>
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
            <p style="margin:0 0 0 5px; font-size:1.2em; font-weight:bold; text-align:center;">
                <?php echo "Microbial co-occurence analysis - ".$bioproject." | ".$at." | ".$is; ?>
            </p>
<!--             <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div> -->
            <div id="network_plot_div" style="width:100%;"></div>

            <div style="clear:both">
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "getNetworkData('network_plot_div', '".$bioproject."', '".$at."', '".$is."');"; ?>
    </script>
</html>
