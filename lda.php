<?php
    $bioproject = urldecode($_GET["key"]);
    $at = urldecode($_GET["at"]);
    $is = urldecode($_GET["is"]);
    $method_joined = array_key_exists("method", $_GET) ? urldecode($_GET["method"]) : "edgeR_fdr";
    $alpha = array_key_exists("alpha", $_GET) ? urldecode($_GET["alpha"]) : "0.1";
    $filter_thres = array_key_exists("filter_thres", $_GET) ? urldecode($_GET["filter_thres"]) : "0.0001";
    $taxa_level = array_key_exists("taxa_level", $_GET) ? urldecode($_GET["taxa_level"]) : "Genus";
    $threshold = array_key_exists("threshold", $_GET) ? urldecode($_GET["threshold"]) : "2";

    $p_adjust_method = explode("_", $method_joined)[1];
    $method = explode("_", $method_joined)[0];

     $command = "Rscript R/bioproject_discriminant_analysis.R \"".$bioproject."\" \"".$at."\" \"".$is."\" \"".$method."\" \"".$alpha."\" \"".$p_adjust_method."\" \"".$filter_thres."\" \"".$taxa_level."\" \"".$threshold."\" 2>&1";
//     echo "<pre>".$command."</pre>\n";

    exec($command, $out, $status);
//     echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//     echo $out[0]."<br/>";  // for checking output only
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LDA - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_lda.js"></script>
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
            <p style="margin-top:0; font-size:1.2em; font-weight:bold; text-align:center;"><?php echo "Discriminant analysis - ".$bioproject." | ".$at." | ".$is; ?></p>
            <div style="width:100%;" id="lda_form_div">
                <form method="get" action="lda.php">
                    <input type="hidden" name="key" value="<?php echo $bioproject; ?>" />
                    <input type="hidden" name="at" value="<?php echo $at; ?>" />
                    <input type="hidden" name="is" value="<?php echo $is; ?>" />
                    <table style="width:100%;">
                        <tr>
                            <td style="width:25%;">
                                <label>Method</label>
                                <select class="full" id="method" name="method" required>
                                    <option value="edgeR_fdr" <?php echo ($method_joined == "edgeR_fdr") ? "selected" : ""; ?>>edgeR (with FDR p-value adjustment)</option>
                                    <option value="lefse_none" <?php echo ($method_joined == "lefse_none") ? "selected" : ""; ?>>LEfSe (without FDR p-value adjustment)</option>
                                    <option value="lefse_fdr" <?php echo ($method_joined == "lefse_fdr") ? "selected" : ""; ?>>LEfSe (with FDR p-value adjustment)</option>
                                </select>
                            </td>
                            <td style="width:20%;">
                                <label>P-value (only for <i>LEfSe</i>)</label>
                                <select class="full" id="alpha" name="alpha" required>
                                    <option value="0.1" <?php echo ($alpha == "0.1") ? "selected" : ""; ?>>0.1</option>
                                    <option value="0.05" <?php echo ($alpha == "0.05") ? "selected" : ""; ?>>0.05</option>
                                    <option value="0.01" <?php echo ($alpha == "0.01") ? "selected" : ""; ?>>0.01</option>
                                </select>
                            </td>
                            <td style="width:15%;">
                                <label>Filter threshold</label>
                                <select class="full" id="filter_thres" name="filter_thres" required>
                                    <option value="0.01" <?php echo ($filter_thres == "0.01") ? "selected" : ""; ?>>0.01</option>
                                    <option value="0.001" <?php echo ($filter_thres == "0.001") ? "selected" : ""; ?>>0.001</option>
                                    <option value="0.0001" <?php echo ($filter_thres == "0.0001") ? "selected" : ""; ?>>0.0001</option>
                                </select>
                            </td>
                            <td style="width:15%;">
                                <label>Taxa level</label>
                                <select class="full" id="taxa_level" name="taxa_level" required>
                                    <option value="Genus" <?php echo ($taxa_level == "Genus") ? "selected" : ""; ?>>Genus</option>
                                    <option value="Family" <?php echo ($taxa_level == "Family") ? "selected" : ""; ?>>Family</option>
                                    <option value="Order" <?php echo ($taxa_level == "Order") ? "selected" : ""; ?>>Order</option>
                                </select>
                            </td>
                            <td style="width:15%;">
                                <label>Cut-off value</label><br/>
                                <input type="number" class="full" id="threshold" name="threshold" min="1" step="0.1" value="<?php echo $threshold; ?>" required />
                                <!--<input type="range" style="width:100%;" id="threshold" name="threshold" min="1" max="4" step="1" value="<?php //echo $threshold; ?>">
                                <svg role="presentation" width="100%" height="10" xmlns="http://www.w3.org/2000/svg">
                                    <rect class="range__tick" x="1%" y="1" width="1" height="5"></rect>
                                    <rect class="range__tick" x="34%" y="1" width="1" height="5"></rect>
                                    <rect class="range__tick" x="65%" y="1" width="1" height="5"></rect>
                                    <rect class="range__tick" x="99%" y="1" width="1" height="5"></rect>
                                </svg>
                                <svg role="presentation" width="100%" height="14" xmlns="http://www.w3.org/2000/svg">
                                    <text class="range__point" x="1%" y="14" text-anchor="start">1</text>
                                    <text class="range__point" x="34%" y="14" text-anchor="middle">2</text>
                                    <text class="range__point" x="65%" y="14" text-anchor="middle">3</text>
                                    <text class="range__point" x="99%" y="14" text-anchor="middle">4</text>
                                </svg>-->
                            </td>
                            <td valign="bottom" style="width:10%; padding:5px;">
                                <input type="submit" style="width:100px;border-radius:10px;" value="Submit" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <p style="margin-bottom:0; font-weight:bold;">
                <?php
                    if ($method_joined == "edgeR_fdr")
                        echo "Analysis parameters: Method = \"edgeR (with FDR p-value adjustment)\" | Filter threshold = ".$filter_thres." | Taxa level = \"".$taxa_level."\" | Cut-off value = ".$threshold;
                    else if ($method_joined == "lefse_none")
                        echo "Analysis parameters: Method = \"LEfSe (without FDR p-value adjustment)\" | P-value = ".$alpha." | Filter threshold = ".$filter_thres." | Taxa level = \"".$taxa_level."\" | Cut-off value = ".$threshold;
                    else if ($method_joined == "lefse_fdr")
                        echo "Analysis parameters: Method = \"LEfSe (with FDR p-value adjustment)\" | P-value = ".$alpha." | Filter threshold = ".$filter_thres." | Taxa level = \"".$taxa_level."\" | Cut-off value = ".$threshold;
                ?>
            </p>
            <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div>
            <div style="width:100%;" id="lda_plot_div">
            <p>
                <b><i>N.B.</i></b> - <b>1)</b> To view all the differential markers, please hover on the bars of the plot
                or download the data using the <i>"Download data"</i> button. located at the top of the page <b>2)</b> A
                cutoff of <i>log<sub>10</sub> (LDA score) &ge; 2</i> was used to determine the differential markers.
                <b>3)</b> All differential markers in the LDA plot were found to be statistically significant. <b>4)</b>
                The statistical significance cutoff used was: <i>p-value &lt; 0.01</i> (Kruskal-Wallis test). <b>5)</b>
                To check the p-values for each differential marker, please download the data using the "Download data"
                button. <b>6)</b> The downloaded data shows Kruskal-Wallis <i>p</i>-value. <b>7)</b> The LDA plot can be
                downloaded as a SVG image by clicking on the <i>"Export as SVG"</i>
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                </svg>
                button in the menubar located at the top right corner of the plot.
            </p>
            </div>
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "plotLDA('lda_plot_div', '".$out[0]."', '".$method."');"; ?>
    </script>
</html>
