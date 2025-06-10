<?php
    $bioproject = (isset($_POST["bioproject"])) ? urldecode($_POST["bioproject"]) : "";
    $at = (isset($_POST["at"])) ? urldecode($_POST["at"]) : "";
    $is = (isset($_POST["is"])) ? urldecode($_POST["is"]) : "";

    $networkData = array();
    $fname = "input/network/".str_replace(" ", "_", $bioproject)."_".str_replace(" ", "_", $at)."_".str_replace(" ", "_", $is).".csv";
    $f = fopen($fname, "r");
    if ($f) {
        $dataMap = array();
        $i = 0;
        while (($data = fgetcsv($f, null, "\t")) !== FALSE) {
            if ($i++ == 0)
                continue;
            $label = $data[0];
            $subgroup = $data[0];
            $subgroup = str_replace(" ", "_", $subgroup);
            $subgroup = str_replace("-", "_", $subgroup);
            $subgroup = str_replace("(", "_", $subgroup);
            $subgroup = str_replace(")", "_", $subgroup);
            if(array_key_exists($subgroup, $dataMap)) {
    //             array_push($dataMap[$subgroup]["label"], $label);
                array_push($dataMap[$subgroup]["sources"], $data[1]);
                array_push($dataMap[$subgroup]["targets"], $data[2]);
                array_push($dataMap[$subgroup]["weights"], floatval($data[3]));
                array_push($dataMap[$subgroup]["nodes"], $data[1]);
                array_push($dataMap[$subgroup]["nodes"], $data[2]);
            } else {
                $dataMap[$subgroup] = array(
                    "label" => $label,
                    "sources" => array($data[1]),
                    "targets" => array($data[2]),
                    "weights" => array(floatval($data[3])),
                    "nodes" => array($data[1], $data[2])
                );
            }
        }
        fclose($f);
        foreach($dataMap as $subgroup=>$subgroupData)
            $dataMap[$subgroup]["nodes"] = array_values(array_unique($dataMap[$subgroup]["nodes"]));

        foreach($dataMap as $subgroup=>$subgroupData) {
            $nodelist = array();
            foreach($dataMap[$subgroup]["nodes"] as $node) {
                array_push(
                    $nodelist, array(
                        "group" => "nodes",
                        "data" => array(
                            "id" => $subgroup."_".$node,
                            "label" => $node
                        )
                    )
                );
            }
            $edgelist = array();
            for($i=0; $i<count($dataMap[$subgroup]["sources"]); ++$i) {
                array_push(
                    $edgelist, array(
                        "group" => "edges",
                        "classes" => ($dataMap[$subgroup]["weights"][$i] > 0) ? "positive" : "negative",
                        "data" => array(
                            "id" => $subgroup."_".$dataMap[$subgroup]["sources"][$i].'_'.$dataMap[$subgroup]["targets"][$i],
                            "source" => $subgroup.'_'.$dataMap[$subgroup]["sources"][$i],
                            "target" => $subgroup.'_'.$dataMap[$subgroup]["targets"][$i],
                            "weight" => $dataMap[$subgroup]["weights"][$i]
                        )
                    )
                );
            }
            $nodenames = $dataMap[$subgroup]["nodes"];
            $subgroupLabel = $dataMap[$subgroup]["label"];
            $networkData[$subgroup] = array("label" => $subgroupLabel, "at" => $at, "nodelist" => $nodelist, "edgelist" => $edgelist, "nodenames" => $nodenames);
        }
    }

    echo json_encode($networkData);
?>
