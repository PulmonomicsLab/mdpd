<?php
    $bioproject = (isset($_GET["key"])) ? urldecode($_GET["key"]) : "";
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
        <title>Network analysis - MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script type = "text/javascript" src = "js/plot_network.js"></script>
        <script type = "text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type = "text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script>
        <script type = "text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.30.4/cytoscape.min.js" integrity="sha512-xpXUCbrkyJCwC5noJXZqec9rSXdRgFfDoL7Q9/pCPe54Y04OlqwPobMcNuA5NPJ7DRR51OIr6xcOH2wUspyKDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type = "text/javascript" src="https://unpkg.com/cytoscape-svg/cytoscape-svg.js"></script>
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
            <p style="margin:0 0 0 5px; font-size:1.2em; font-weight:bold; text-align:center;">
                <?php echo "Microbial co-occurrence analysis - ".$bioproject." | ".$at." | ".$is; ?>
            </p>
<!--             <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div> -->

            <div id="network_plot_div" style="width:100%;">
                <center><img style="height:300px;" src="resource/loading.gif" /></center>
            </div>
            <p id="network_note" style="font-size: 0.9em; margin-top:5px; display:none;">
                Network(s) show the co-occurrence of the microbes across the
                subgroup(s). The <b>nodes</b> represent the microbes (bacteria,
                virus, eukaryota, and/or archaea). The <b>edges</b> indicate
                relationships between microbes. The colors denote positive
                <font style="color:green;">(green)</font> or negative
                <font style="color:red;">(red)</font> interactions while the
                color intensity is proportional to <b>edge weights</b>. <b>Hover
                mouse on a node</b> to highlight the taxa name, out-degree, and
                in-degree. <b>Double click on a node</b> to get the taxa information.
                Users can <i>pan</i>, <i>zoom</i>, <i>reset</i> the network, and
                <i>modify the layout</i> using the respective buttons in the control
                panel. The network can be downloaded in <i>PNG</i>, <i>SVG</i>,
                <i>JPEG</i>, and <i>JSON</i> format using the availbale buttons on
                the control panel. The JSON format can be easily exported to Cytoscape.
            </p>

            <div style="clear:both">
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "getNetworkData('network_plot_div', '".$dataJSON."');"; ?>
    </script>
</html>
