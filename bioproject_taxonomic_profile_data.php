<?php
    $bioproject = (isset($_POST["bioproject"])) ? urldecode($_POST["bioproject"]) : "";
    $at = (isset($_POST["at"])) ? urldecode($_POST["at"]) : "";
    $is = (isset($_POST["is"])) ? urldecode($_POST["is"]) : "";

    $command = "Rscript R/bioproject_taxa_distribution.R \"".$bioproject."\" \"".$at."\" \"".$is."\" 2>&1";
//     echo "<pre>".$command."</pre>\n";

    exec($command, $out, $status);
//     echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//     echo $out[0]."<br/>";  // for checking output only

    echo $out[0];
?>
