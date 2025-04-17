<?php
    $run = (isset($_POST["run"])) ? $_POST["run"] : "";
    $bioproject = (isset($_POST["bioproject"])) ? $_POST["bioproject"] : "";
    $at = (isset($_POST["at"])) ? $_POST["at"] : "";

    $command = "Rscript R/runwise_top_taxa.R \"".$run."\" \"".$bioproject."\" \"".$at."\" 2>&1";
//     echo "<pre>".$command."</pre>\n";

    exec($command, $out, $status);
//     echo implode("</br/>", $out)."<br/>";  // for checking output with errors, if any
//     echo $out[0]."<br/>";  // for checking output only
    echo $out[0];
?>
