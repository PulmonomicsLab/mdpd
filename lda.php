<?php
    $bioproject = (isset($_GET["key"])) ? urldecode($_GET["key"]) : "";
    $at = (isset($_GET["at"])) ? urldecode($_GET["at"]) : "";
    $is = (isset($_GET["is"])) ? urldecode($_GET["is"]) : "";
    $method_joined = (isset($_GET["method"])) ? urldecode($_GET["method"]) : "lefse_none";
    $alpha = (isset($_GET["alpha"])) ? urldecode($_GET["alpha"]) : "0.1";
    $filter_thres = (isset($_GET["filter_thres"])) ? urldecode($_GET["filter_thres"]) : "0.0001";
    if ($at == "WMS")
        $taxa_level = (isset($_GET["taxa_level"])) ? urldecode($_GET["taxa_level"]) : "Species";
    else
        $taxa_level = (isset($_GET["taxa_level"])) ? urldecode($_GET["taxa_level"]) : "Genus";
    $threshold = (isset($_GET["threshold"])) ? urldecode($_GET["threshold"]) : "2";

    $p_adjust_method = explode("_", $method_joined)[1];
    $method = explode("_", $method_joined)[0];

    $dataJSON = json_encode(
        array(
            "bioproject" => $bioproject,
            "at" => $at,
            "is" => $is,
            "method" => $method,
            "alpha" => $alpha,
            "p_adjust_method" => $p_adjust_method,
            "filter_thres" => $filter_thres,
            "taxa_level" => $taxa_level,
            "threshold" => $threshold
        )
    );
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LDA - MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_lda.js"></script>
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
            <p style="margin:0 0 10px 0; font-size:1.2em; font-weight:bold; text-align:center;"><?php echo "Discriminant analysis - ".$bioproject." | ".$at." | ".$is; ?></p>
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
                                    <option value="lefse_none" <?php echo ($method_joined == "lefse_none") ? "selected" : ""; ?>>LEfSe (without FDR p-value adjustment)</option>
                                    <option value="lefse_fdr" <?php echo ($method_joined == "lefse_fdr") ? "selected" : ""; ?>>LEfSe (with FDR p-value adjustment)</option>
                                    <option value="edgeR_fdr" <?php echo ($method_joined == "edgeR_fdr") ? "selected" : ""; ?>>edgeR (with FDR p-value adjustment)</option>
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
                                    <?php if($at == "WMS") echo "<option value=\"Species\"".(($taxa_level == "Species") ? "selected>" : ">")."Species</option>"; ?>
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
            <p style="margin-top:5px; font-weight:bold;">
                <?php
                    if ($method_joined == "edgeR_fdr")
                        echo "Analysis parameters: Method = \"edgeR (with FDR p-value adjustment)\" | Filter threshold = ".$filter_thres." | Taxa level = \"".$taxa_level."\" | Cut-off value = ".$threshold;
                    else if ($method_joined == "lefse_none")
                        echo "Analysis parameters: Method = \"LEfSe (without FDR p-value adjustment)\" | P-value = ".$alpha." | Filter threshold = ".$filter_thres." | Taxa level = \"".$taxa_level."\" | Cut-off value = ".$threshold;
                    else if ($method_joined == "lefse_fdr")
                        echo "Analysis parameters: Method = \"LEfSe (with FDR p-value adjustment)\" | P-value = ".$alpha." | Filter threshold = ".$filter_thres." | Taxa level = \"".$taxa_level."\" | Cut-off value = ".$threshold;
                ?>
            </p>
            <div id="download_div" style="width:100%; text-align:center; display:none;">
                <a id="download_button" download="discriminant_figure_data.csv">
                    <button type="button" style="margin:2px;">Download figure data</button>
                </a>
            </div>
            <div style="width:100%;" id="lda_plot_div">
                <center><img style="height:300px;" src="resource/loading.gif" /></center>
            </div>
            <p id="taxa_button_group_heading" style="margin:3px; font-weight:bold; display:none;">Differentially abundant taxa details</p>
            <div id="taxa_button_group" style="width:100%; background-color:#fff9e6; border:1px dashed #004d99; display:none;"></div>
            <p id="bar_legend" style="font-size: 0.9em; margin-top:5px; display:none;">
                Interactive <b>bar plot</b> shows the differential microbial
                signatures between the subgroups. <b>Hover mouse</b> on a bar
                to highlight the taxa name and the LDA score (log<sub>10</sub>)
                or Log<sub>2</sub> fold change. <b>Click on each subgroup on
                the legend</b> to select or deselect the subgroup(s). <b>Click
                on the buttons</b> below the plot to get the information of
                the respective taxa. The plot can be downloaded as SVG by
                clicking on the <b>"
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                </svg>
                "</b> button in the figure menu bar at the top right corner. The
                data can be downloaded using the <b>"Download figure data"</b>
                button.
                <br/>
                Users can modify the discriminant analysis parameters, by changing
                the <i>"Method"</i>, <i>"P-value"</i>, <i>"Filter threshold"</i>,
                <i>"Taxa level"</i>, and <i>"Cut-off value"</i> using the respective
                drop-down buttons.
            </p>
            <hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "getLDAData('lda_plot_div', '".$dataJSON."');"; ?>
    </script>
</html>
