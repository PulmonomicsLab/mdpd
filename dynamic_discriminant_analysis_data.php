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

    $at = urldecode($_POST["at"]);
    $bioprojects = json_decode(urldecode($_POST["bioprojects"]));
    $runs = json_decode(urldecode($_POST["runs"]));
    $p_adjust_method = urldecode($_POST["p_adjust_method"]);
    $method = urldecode($_POST["method"]);
    $alpha = urldecode($_POST["alpha"]);
    $filter_thres = urldecode($_POST["filter_thres"]);
    $taxa_level = urldecode($_POST["taxa_level"]);
    $threshold = urldecode($_POST["threshold"]);

    $tmp_path = $tmp_prefix . get_temp_folder_name() . "/";
    mkdir($tmp_path, 0700);
    file_put_contents($tmp_path."bioprojects.tsv", implode("\n", $bioprojects)."\n");
    file_put_contents($tmp_path."runs.tsv", implode("\n", $runs)."\n");

    $command = "Rscript R/dynamic_discriminant_analysis.R \"".$at."\" \"".$tmp_path."\" \"".$method."\" \"".$alpha."\" \"".$p_adjust_method."\" \"".$filter_thres."\" \"".$taxa_level."\" \"".$threshold."\" 2>&1";
//     echo "<pre>".$command."</pre>\n";
    exec($command, $out, $status);
//     echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//     echo $out[0]."<br/>";  // for checking output only

    remove_directory_recursively($tmp_path);

    echo $out[0];
?>

