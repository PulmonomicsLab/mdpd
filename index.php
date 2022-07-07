<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/index_plots.js"></script>
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
                    <td class="nav"><a href="#" class="active">Home</a></td>
                    <td class="nav"><a href="advance_search.html" class="side_nav">Search</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <br/>
            <p class="intro">
                <b>LHSPred</b> is a web based tool that enables users to predict risk of 
                pneumonia with clinical examination features. It uses Support Vector Regressor 
                (SVR) and Multi-layer Perceptron Regressor (MLPR) trained with COVID-19 patients' 
                data to determine a score that evaluates the involvement of lesions in the lungs. 
                This computed score is then used to predict risk of pneumonia.
            </p><br/>
            <div id="map-plot-container" style="width:100%"></div>
        </div>
    </body>
    <script>getChoroplethData('map-plot-container')</script>
</html> 
