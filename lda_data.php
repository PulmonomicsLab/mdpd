<?php
    $bioproject = urldecode($_POST["bioproject"]);
    $at = urldecode($_POST["at"]);
    $is = urldecode($_POST["is"]);
    $method = urldecode($_POST["method"]);
    $alpha = urldecode($_POST["alpha"]);
    $p_adjust_method = urldecode($_POST["p_adjust_method"]);
    $filter_thres = urldecode($_POST["filter_thres"]);
    $taxa_level = urldecode($_POST["taxa_level"]);
    $threshold = urldecode($_POST["threshold"]);

    $command = "Rscript R/bioproject_discriminant_analysis.R \"".$bioproject."\" \"".$at."\" \"".$is."\" \"".$method."\" \"".$alpha."\" \"".$p_adjust_method."\" \"".$filter_thres."\" \"".$taxa_level."\" \"".$threshold."\" 2>&1";
//     echo "<pre>".$command."</pre>\n";

    exec($command, $out, $status);
//     echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//     echo $out[0]."<br/>";  // for checking output only
    echo $out[0];
?>
