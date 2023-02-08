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
                    <td class="nav"><a href="#" class="active">Statistics</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->

        <div class = "section_middle">
            <br/>
            <div class="intro">
                <div id="stat-plot-wrapper_1" class="plot" style="margin:10px 1% 10px 0; float:left;">
                    <div id="stat-plot-container_1" style="width:100%;"></div>
                    <div>1A.</div>
                </div>
                <div id="stat-plot-wrapper_2" class="plot" style="margin:10px 1% 10px 1%; float:left;">
                    <div id="stat-plot-container_2" style="width:100%;"></div>
                    <div>1B.</div>
                </div>
                <div id="stat-plot-wrapper_3" class="plot" style="margin:10px 0 10px 1%; float:left;">
                    <div id="stat-plot-container_3" style="width:100%;"></div>
                    <div>1C.</div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div id="sunburst-plot-container" class="intro plot"></div>
            <div class="intro plot">1D.</div>
            <div class="intro">
                <div id="bar-plot-wrapper_1" class="plot" style="margin:10px 2% 10px 0; float:left;">
                    <div id="bar-plot-container_1" style="width:100%;"></div>
                    <div>2A.</div>
                </div>
                <div id="bar-plot-wrapper_2" class="plot" style="margin:10px 0 10px 2%; float:left;">
                    <div id="bar-plot-container_2" style="width:100%;"></div>
                    <div>2B.</div>
                </div>
                <div style="clear:both;"></div>
                <div id="bar-plot-container_3" class="plot" style="margin:10px 0% 0 0%;"></div>
                <div>2C.</div>
            </div>
            <div id="map-plot-container" class="intro plot"></div>
            <div class="intro plot">3.</div>
            <br/><br/>
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
