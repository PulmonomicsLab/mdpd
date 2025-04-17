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

    $bioproject = (isset($_POST["bioproject"])) ? urldecode($_POST["bioproject"]) : "";
    $at = (isset($_POST["at"])) ? urldecode($_POST["at"]) : "";
    $is = (isset($_POST["is"])) ? urldecode($_POST["is"]) : "";
    $confounders = (isset($_POST["confounders"])) ? urldecode($_POST["confounders"]) : "";

    if ($confounders !== "") {
        $tmp_path = $tmp_prefix . get_temp_folder_name() . "/";
        mkdir($tmp_path, 0700);

        $command = "Rscript R/bioproject_covariate_analysis.R \"".$bioproject."\" \"".$at."\" \"".$is."\" \"".$confounders."\" \"".$tmp_path."\" 2>&1";
//         echo "<pre>".$command."</pre>\n";
        exec($command, $out, $status);
        $heatmap = $out[count($out)-1];
//         echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//         echo $heatmap."<br/>";  // for checking output only

        remove_directory_recursively($tmp_path);
        echo $heatmap;

    } else {
        echo "No covariate analysis possible";
    }
?>
