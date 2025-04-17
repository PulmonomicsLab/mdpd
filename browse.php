<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Browse - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
        <script type = "text/javascript" src = "js/browse.js"></script>
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
                    <td class="nav"><a href="#" class="active">Browse</a></td>
                    <td class="nav"><a href="analysis.php" class="side_nav">Analysis</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="about.php" class="side_nav">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <div class = "section_left" id="section_left">
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-1" class="browse_side_nav">1. Group-wise BioProjects</a></div>
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-2" class="browse_side_nav">2. Biome-wise BioProjects</a></div>
            <div style="width:100%; margin: 20px 0 20px 0;"><a href="#sec-2" class="browse_side_nav">3. Domain-wise taxa</a></div>
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
            <div class="browse-heading" style="margin-bottom:5px;" id="sec-1">1. Group-wise BioProjects</div>

            <p style="margin:0; font-size:0.9em;"><i>(Please click on the group names in figure to get details)</i></p>
            <center><div id="group_wise_browse"><img style="height:300px;" src="resource/loading.gif" /></div></center>

            <div class="browse-heading" style="margin-bottom:5px;" id="sec-2">2. Biome-wise BioProjects</div>
            <p style="margin:0; font-size:0.9em;"><i>(Please click on the biome names in figure to get details)</i></p>
            <center><div id="biome_wise_browse"><img style="height:300px;" src="resource/loading.gif" /></div></center>

            <div class="browse-heading" style="margin-bottom:5px;" id="sec-3">3. Domain-wise taxa</div>
            <p style="margin:0; font-size:0.9em;"><i>(Please click on the taxa domains in figure to get details)</i></p>
            <center><div id="domain_wise_browse"><img style="height:300px;" src="resource/loading.gif" /></div></center>

        </div>
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
        showGroups('group_wise_browse');
        showBiomes('biome_wise_browse')
        showDomains('domain_wise_browse')
    </script>
</html>
