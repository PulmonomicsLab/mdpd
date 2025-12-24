<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Submit data - MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
<!--         <script type = "text/javascript" src = "js/analysis.js"></script> -->
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
                    <td class="nav"><a href="#" class="active">Submit data</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left" id="section_left">
        </div>-->

        <div class = "section_middle" style="font-size:1.2em;">
            <br/>
            <form name="data_submission_form" method="post" action="submission_result.php">
                <table class="details">
                    <tr>
                        <td class="odd" style="width:50%; text-align:left; padding-left:20px;">
                            <b>BioProject ID</b> <!--<br/>-->
                            <input type="text" id="ds1" class="full" name="ds1" value="" placeholder="Enter ID" style="height:30px;" required />
                        </td>
                        <td class="odd" style="width:50%; text-align:left; padding-left:20px;">
                            <b>Pulmonary disease name / Healthy</b> <!--<br/>-->
                            <input type="text" id="ds2" class="full" name="ds2" value="" placeholder="Enter pulmonary disease name / Healthy" style="height:30px;" required />
                        </td>
                    </tr>
                    <tr>
                        <td class="even" style="width:50%; text-align:left; padding-left:20px;">
                            <b>PMID/DOI</b> <!--<br/>-->
                            <input type="text" id="ds3" class="full" name="ds3" value="" placeholder="Enter PMID/DOI" style="height:30px;" required />
                        </td>
                        <td class="even" style="width:50%; text-align:left; padding-left:20px;">
                            <b>Country</b> <!--<br/>-->
                            <input type="text" id="ds4" class="full" name="ds4" value="" placeholder="Enter country name" style="height:30px;" required />
                        </td>
                    </tr>
                    <tr>
                        <td class="odd" style="width:50%; text-align:left; padding-left:20px;">
                            <b>Email ID</b> <!--<br/>-->
                            <input type="email" id="ds5" class="full" name="ds5" value="" placeholder="Enter email ID" style="height:30px;" required />
                        </td>
                        <td class="odd" style="width:50%; text-align:left; padding-left:20px;">
                            <b>Isolation source / Body site</b> <!--<br/>-->
                            <input type="text" id="ds6" class="full" name="ds6" value="" placeholder="Enter isolation source / body site" style="height:30px;" required />
                        </td>
                    </tr>
                    <tr>
                        <td class="even" colspan="2" style="text-align:left; padding-left:20px;">
                            <b>Link to appropriate metadata / sample information (e.g., supplementary data)</b> <!--<br/>-->
                            <input type="text" id="ds7" class="full" name="ds7" value="" placeholder="Enter link" style="height:30px;" required />
                        </td>
                    </tr>
                </table>
                <center>
                    <input type="submit" style="width:70px;margin:5px;font-weight:em;" value="Submit" />
                    <button type="reset" style="width:70px;margin:5px;" value="Reset">Reset</button>
<!--                     <button style="width:160px;margin:5px;">View submitted data</button> -->
                </center>
                <center><p>* All fields are mandatory</p></center>
            </form>
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
</html>
