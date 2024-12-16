<?php
    $dbConfigFileName = "config/config_db.xml";
    
    $allRunAttributes = array(
        "Run"=>"Run ID",
        "Experiment"=>"Experiment ID",
        "BioSample"=>"BioSample ID",
        "BioProject"=>"BioProject ID",
        "Bases"=>"Number of Bases",
        "Bytes"=>"Number of Bytes",
        "Country"=>"Country",
        "AssayType"=>"Assay Type",
        "Biome"=>"Biome",
        "IsolationSource"=>"Isolation Source",
        "LibraryLayout"=>"Library Layout",
        "VariableRegion"=>"Variable Region",
        "Instrument"=>"Instrument",
        "Year"=>"Year",
        "Grp"=>"Disease",
        "SubGroup"=>"Disease Sub-group",
        "Gender"=>"Gender",
        "Age"=>"Age",
        "SmokingStatus"=>"Smoking Status",
        "Therapeutics"=>"Therapeutics",
        "Comorbidity"=>"Comorbidity",
        "TotalRuns"=>"Total Reads",
        "ProcessedRuns"=>"Processed Reads",
    );
    $allBioProjectAttributes = array(
        "BioProject"=>"BioProject ID",
        "SRA"=>"SRA Study ID",
//         "Grp"=>"Disease",
        "SubGroup"=>"Disease(s)",
        "IsolationSource"=>"Isolation Source",
        "Biome"=>"Biome",
        "AssayType"=>"Assay Type",
        "Instrument"=>"Instrument",
        "LibraryLayout"=>"Library Layout",
        "Year"=>"Year",
        "TotalRuns"=>"Total Runs",
        "ProcessedRuns"=>"Processed Runs",
        "VariableRegion"=>"Variable Region",
        "PMID"=>"PMID",
        "Country"=>"Country",
    );
    $viewAttributes = array(
        "run.Run",
        "run.BioProject",
        "disease.Grp",
        "run.SubGroup",
        "run.Biome",
        "run.Instrument",
        "run.AssayType",
        "run.LibraryLayout",
        "run.VariableRegion",
        "run.ProcessedRuns",
        "run.Country",
        "run.Year",
    );
    $viewBioProjectAttributes = array(
        "BioProject",
        "Grp",
        "SubGroup",
        "TotalRuns",
        "ProcessedRuns",
        "Biome",
        "AssayType",
        "LibraryLayout",
    );
    $defaultSortAttribute = "run.Run";

    function getDBParams() {
        global $dbConfigFileName;
        $xmlData = simplexml_load_file($dbConfigFileName);
        $server = $xmlData->server;
        $uname = $xmlData->uname;
        $pass = $xmlData->pass;
        $dbname = $xmlData->dbname;
        
        return array("server"=>$server, "uname"=>$uname, "pass"=>$pass, "dbname"=>$dbname);
    }
    
    function connect() {
        $dbParams = getDBParams();
//         $conn = new mysqli($server, $uname, $pass, $dbname);
        $conn = new mysqli($dbParams["server"], $dbParams["uname"], $dbParams["pass"], $dbParams["dbname"]);
        return $conn;
    }
    
    function closeConnection($conn) {
        $conn->close();
    }
    
    function execute_and_fetch_assoc($stmt, $types = false, $params = false) {
        if (!$stmt->execute()) {
            return false;
        }
        $stmt->store_result();
       
        // get column names
        $metadata = $stmt->result_metadata();
        $fields = $metadata->fetch_fields();

        $results = [];
        $ref_results = [];
        foreach($fields as $field){
            $results[$field->name]=null;
            $ref_results[]=&$results[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $ref_results);

        $data = array();
        $i=0;
        while ($stmt->fetch()) {
            $c = 0;
            $new_res = array();
            foreach($results as $f => $v) {
                $new_res[$f] = $v;
                $c++;
            }
//             echo implode(",", $new_res);
            array_push($data, $new_res);
        }

//         echo count($data);
//         foreach($data as $d)
//             echo implode(",", $d)."<br/>";

        $stmt->free_result();
        return  $data;
    }
?>
