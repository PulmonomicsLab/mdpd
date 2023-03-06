<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Statistics - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/stat_plots.js"></script>
<!--         <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> -->
<!--         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script> -->
        <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script>
        <style>
            .intro{
                width:90%;
                height:auto;
                margin: 10px 5% 0 5%;
            }
            .plot{
                max-width: 100%;
                overflow: auto;
                margin-left: auto;
                margin-right: auto;
            }
            .caption{
                text-align: center;
                font-size: 1.2em;
                margin-top: 10px;
            }
            #bar-plot-wrapper_1 {
                width : 48%;
            }
            #bar-plot-wrapper_2 {
                width : 48%;
            }
            #stat-plot-wrapper_1 {
                width : 32%;
            }
            #stat-plot-wrapper_2 {
                width : 32%;
            }
            #stat-plot-wrapper_3 {
                width : 32%;
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
                    <td class="nav"><a href="#" class="active">Statistics</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->

        <div class = "section_middle">
            <br/>
            <div class="intro">
                <hr/><h2>1. Data distribution on the basis of biome, assay type and group</h2><hr/>
                <div id="sunburst-plot-container" class="plot"></div>
                <div class="plot caption">Fig. 1A - Interactive sunburst plot showing the total distibution of runs on the basis of group, biome and assay type.</div>
                <div id="stat-plot-wrapper_1" class="plot" style="margin:10px 1% 10px 0; float:left;">
                    <div id="stat-plot-container_1" style="width:100%;"></div>
                    <div class="caption">Fig. 1B - Distribution of runs among gut and lung biome.</div>
                </div>
                <div id="stat-plot-wrapper_2" class="plot" style="margin:10px 1% 10px 1%; float:left;">
                    <div id="stat-plot-container_2" style="width:100%;"></div>
                    <div class="caption">Fig. 1C - Distribution of runs on the basis of assay types.</div>
                </div>
                <div id="stat-plot-wrapper_3" class="plot" style="margin:10px 0 10px 1%; float:left;">
                    <div id="stat-plot-container_3" style="width:100%;"></div>
                    <div class="caption">Fig. 1D - Group-wise distribution of the runs.</div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <br/>
            <div class="intro">
                <hr/><h2>2. Year-wise data distribution</h2><hr/>
                <div id="bar-plot-container_3" class="plot" style="margin:10px 0% 0 0%;"></div>
                <div class="caption">Fig. 2A - Year-wise distribution of the data for each group.</div>
                <div id="bar-plot-wrapper_2" class="plot" style="margin:10px 2% 10px 0; float:left;">
                    <div id="bar-plot-container_2" style="width:100%;"></div>
                    <div class="caption">Fig. 2B - Year-wise distribution of lung and gut microbiome data.</div>
                </div>
                <div id="bar-plot-wrapper_1" class="plot" style="margin:10px 0 10px 2%; float:left;">
                    <div id="bar-plot-container_1" style="width:100%;"></div>
                    <div class="caption">Fig. 2C - Year-wise distribution of amplicon and WGS data.</div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <br/>
            <div class="intro"><hr/><h2>3. Country-wise data distribution</h2><hr/></div>
            <div id="map-plot-container" class="intro plot"></div>
            <div class="intro plot caption">Fig. 3 - Interactive plot showing country-wise distribution of the data.</div>
            <br/><br/>
        </div>
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
        getStatisticsData('stat-plot-container',  'sunburst-plot-container');
        getChoroplethData('map-plot-container');
        getYearWiseHistogramData('bar-plot-container');
        
        window.onresize = function() {
            Plotly.Plots.resize('stat-plot-container_1');
            Plotly.Plots.resize('stat-plot-container_2');
            Plotly.Plots.resize('stat-plot-container_3');
            Plotly.Plots.resize('map-plot-container');
            Plotly.Plots.resize('sunburst-plot-container');
            Plotly.Plots.resize('bar-plot-container_1');
            Plotly.Plots.resize('bar-plot-container_2');
            Plotly.Plots.resize('bar-plot-container_3');
        };
    </script>
    
    <style>
        @media screen and (max-width: 900px) {
            #bar-plot-wrapper_1 {
                width : 100%;
            }
            #bar-plot-wrapper_2 {
                width : 100%;
            }
            #stat-plot-wrapper_1 {
                width : 100%;
            }
            #stat-plot-wrapper_2 {
                width : 100%;
            }
            #stat-plot-wrapper_3 {
                width : 100%;
            }
        }
    </style>
</html>
