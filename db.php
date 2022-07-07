<?php
    $dbConfigFileName = "config/config_db.xml";
    
    $allRunAttributes = array(
        "Run"=>"Run ID",
        "Experiment"=>"Experiment ID",
        "BioSample"=>"BioSample ID",
        "BioProject"=>"BioProject ID",
        "AvgSpotLen"=>"Average Spot Length",
        "Bases"=>"Number of Bases",
        "Bytes"=>"Number of Bytes",
        "CenterName"=>"Center Name",
        "Country"=>"Country",
        "Biome"=>"Biome",
        "IsolationSource"=>"Isolation Source",
        "AssayType"=>"Assay Type",
        "Grp"=>"Disease",
        "SubGroup"=>"Disease Sub-group",
        "Instrument"=>"Instrument",
//         "ReleaseDate"=>"Release Date",
        "ReleaseYear"=>"Release Year",
        "Therapeutics"=>"Therapeutics",
    );
    $allBioProjectAttributes = array(
        "BioProject"=>"BioProject ID",
        "SRA"=>"SRA Study ID",
//         "Grp"=>"Disease",
        "SubGroup"=>"Disease(s)",
        "IsolationSource"=>"Isolation Source",
        "Biome"=>"Biome",
        "AssayType"=>"Assay Type",
        "ReleaseYear"=>"Release Year",
        "RunCount"=>"Run Count",
        "PMID"=>"PMID",
        "DiseaseStudied"=>"Disease Studied",
    );
    $viewAttributes = array(
        "run.Run",
        "run.BioProject",
        "bioproject.SRA",
        "disease.Grp",
        "run.SubGroup",
        "run.IsolationSource",
        "run.Instrument",
        "run.AssayType",
        "run.Country",
        "run.ReleaseYear",
    );
    $viewBioProjectAttributes = array(
        "BioProject",
        "SRA",
        "Grp",
        "SubGroup",
        "RunCount",
        "IsolationSource",
        "Biome",
        "AssayType",
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
?>
