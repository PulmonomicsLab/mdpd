<?php

    function get_temp_folder_name() {
        $t = microtime();
        $sec = explode(" ", $t)[0];
        $msec = explode(" ", $t)[1];
        $timestamp = ($sec*1000000 + $msec * 1000000)% 1000000000;
        return basename($timestamp);
    }

    function remove_directory_recursively($dir) {
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it,
                    RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($dir);
    }

    $tmp_prefix = "demo_output/";

    $bioproject = urldecode($_GET["key"]);
    $at = urldecode($_GET["at"]);

    $confounder_json = json_decode(file_get_contents("input/bioproject_confounder_list.json"), true);
//     echo implode(", ", $confounder_json["bioprojects"]);

    if (array_key_exists($bioproject, $confounder_json["confounders"])) {
        $tmp_path = $tmp_prefix . get_temp_folder_name() . "/";
        mkdir($tmp_path, 0700);

        $confounders = $confounder_json["confounders"][$bioproject];
        $display_text = "Multivariate association analysis - ".$bioproject." | ".$at." | Confounders = [".$confounders."]";
        $command = "Rscript R/bioproject_covariate_analysis.R \"".$bioproject."\" \"".$at."\" \"".$confounders."\" \"".$tmp_path."\" 2>&1";
//         echo "<pre>".$command."</pre>\n";
        exec($command, $out, $status);
        $heatmap = $out[count($out)-1];
//         echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//         echo $heatmap."<br/>";  // for checking output only

        remove_directory_recursively($tmp_path);

    } else {
        $out = array("No covariate analysis possible");
        echo $out[0];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Multivariate association analysis - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/plot_covariate.js"></script>
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
            <p style="margin-top:0; font-size:1.2em; font-weight:bold; text-align:center;"><?php echo $display_text; ?></p>
            <div id="download_div" style="width:100%; text-align:center; margin-bottom:20px;"></div>
            <div id="covariate_plot_div" style="width:70%; margin:0 15% 0 15%;"></div>

            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
    <script>
        <?php echo "plotCovariateHeatmap('covariate_plot_div', '".$heatmap."');"; ?>
    </script>
</html>
