<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/advance_search_input.js"></script>
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
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
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
            
            <center><br/><h3>Search MDPD</h3></center>
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
                <input type="hidden" id="total_count" name="total_count" value="1" />
                <script>document.getElementById('total_count').value = 1;</script>
                <table border="0" align="center" style="margin-top:10px;">
                    <tr>
                        <td style="text-align:right;"><button type="button" style="width:180px;margin:5px;" onclick="addRow()">Add search predicate</button></td>
                        <td style="text-align:left;"><button type="button" style="width:180px;margin:5px;" onclick="deleteRow()">Delete search predicate</button></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;//padding-right:100px;"><input type="submit" style="width:100px;margin:15px;border-radius:10px;" name="Submit" value="Submit" /></td>
                        <td style="text-align:left;//padding-left:100px;"><button type="reset" style="width:100px;margin:15px;" value="Reset">Reset</button></td>
                    </tr>
                </table>
            </form>
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html> 
