<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/advance_search_input.js"></script>
<!--         <script type = "text/javascript" src = "js/index_plots.js"></script> -->
<!--         <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> -->
<!--         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/1.0.8/jquery.csv.min.js"></script> -->
<!--         <script type = "text/javascript" src = "https://cdn.plot.ly/plotly-latest.min.js"></script> -->
        <style>
            .intro{
                width:80%;
                height:auto;
                margin:0 10% 0 10%;
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
                    <td class="nav"><a href="#" class="active">Home</a></td>
<!--                     <td class="nav"><a href="advance_search.html" class="side_nav">Search</a></td> -->
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
                Microbiome Database of Pulmonary Diseases (MDPD) is a manually curated human gut-lung 
                microbiome database of seven lung diseases namely <i>Asthma</i>, <i>COPD</i>, <i>COVID-19</i>, 
                <i>Cystic Fibrosis</i>, <i>Lung Cancer</i> and <i>Pneumonia</i>. This database provides:
            </p>
            <br/>
            <ul class="intro">
                <li>Taxonomic profile visualization (Krona Plot) of specific diseases.</li>
                <li>Similarity between gut and lung microbiome of seven lung diseases.</li>
                <li>Dissimilarity between any two microbiome study.</li>
            </ul>
            <div><img class="intro" style="margin-top:30px; //margin-bottom:30px;" src="resource/MDPD_Graphical_Abstract.svg" /></div>
            
            <center><h3>Search MDPD</h3></center>
            <form method="post" action="advance_search_result.php">
                <!--<br/><center><button type="button" onclick="update_label()">Show Current Logical Query</button></center><br/>-->
                <table class="form" id="form_input_table" border="0" align="center">
                    <tr class="input_row">
                        <td style="width:10%;"></td>
                        <td style="width:10%;"><input type="hidden" name="lo0" value="" /></td>
                        <td style="width:30%;">
                            <select class="full" id="k0" name="k0" onchange="updateKeyChoice(this)">
                                <option value="Disease" selected>Disease</option>
                                <option value="AssayType">Assay Type</option>
                                <option value="Country">Country</option>
                                <option value="Instrument">Instrument</option>
                                <option value="Year">Year</option>
                                <option value="IsolationSource">Isolation source</option>
                            </select>
                        </td>
                        <td style="width:10%;">
<!--                            <select class="full" id="op0" name="op0">-->
<!--                                <option value="=" selected>=</option>-->
<!--                                <option value="<">&lt;</option>-->
<!--                                <option value="<=">&lt;=</option>-->
<!--                                <option value=">">&gt;</option>-->
<!--                                <option value=">=">&gt;=</option>-->
<!--                            </select>-->
                        </td>
                        <td style="width:40%;">
<!--                            <input class="full" type="text" id="v0" name="v0" placeholder="Enter search keyword">-->
                        </td>
                    </tr>
                    <script>updateKeyChoice(document.getElementById('k0'));</script>
                </table>
                <br/>
                <input type="hidden" id="total_count" name="total_count" value="1" />
                <script>document.getElementById('total_count').value = 1;</script>
                <table border="0" align="center">
                    <tr>
                        <td><center><button type="button" onclick="addRow()">Add</button></center></td>
                        <td><center><button type="button" onclick="deleteRow()">Delete</button></center></td>
                    </tr>
                    <tr>
                        <td><center><input type="submit" name="Submit" value="Submit" style="border-radius:10px;" /></center></td>
                        <td><center><button type="reset" value="Reset">Reset</button></center></td>
                    </tr>
                </table>
            </form>
            
<!--             <div id="map-plot-container" class="intro"></div> -->
        </div>
    </body>
<!--     <script>getChoroplethData('map-plot-container')</script> -->
</html> 
