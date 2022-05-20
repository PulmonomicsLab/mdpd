<?php
    $dbConfigFileName = "config/config_db.xml";
    
    $allAttributes = array(
        "Run",
        "AssayType",
        "BioProject",
        "BioSample",
        "AvgSpotLen",
        "Bases",
        "BioSampleModel",
        "Bytes",
        "CenterName",
        "Experiment",
        "geo_loc_name_country",
        "geo_loc_name_country_continent",
        "Host",
        "Age",
        "Sex",
        "LibrarySource",
        "Instrument",
        "IsolationSource_EnvMedium",
        "LibraryName",
        "LibraryLayout",
        "LibrarySelection",
        "ReleaseDate",
        "SampleName",
        "SRAStudy",
        "Organism",
        "HostDisease",
        "Description",
        "PMID",
    );
    $attributeFriendlyNames = array(
        "Run"=>"Run ID",
        "AssayType"=>"Assay Type",
        "BioProject"=>"BioProject ID",
        "BioSample"=>"BioSample ID",
        "AvgSpotLen"=>"Average Spot Length",
        "Bases"=>"Number of Bases",
        "BioSampleModel"=>"BioSample Model",
        "Bytes"=>"Number of bytes",
        "CenterName"=>"Center Name",
        "Experiment"=>"Experiment ID",
        "geo_loc_name_country"=>"Country",
        "geo_loc_name_country_continent"=>"Continent",
        "Host"=>"Host",
        "Age"=>"Age",
        "Sex"=>"Sex",
        "LibrarySource"=>"Library Source",
        "Instrument"=>"Instrument",
        "IsolationSource_EnvMedium"=>"Isolation source / Environment medium",
        "LibraryName"=>"Library Name",
        "LibraryLayout"=>"Library Layout",
        "LibrarySelection"=>"Library Selection",
        "ReleaseDate"=>"Release Date",
        "SampleName"=>"Sample Name",
        "SRAStudy"=>"SRA Study ID",
        "Organism"=>"Organism", 
        "HostDisease"=>"Host Disease",
        "Description"=>"Description",
        "PMID"=>"PubMed ID",
    );
    $viewAttributes = array(
        "Run",
        "AssayType",
        "BioProject",
        "geo_loc_name_country",
        "geo_loc_name_country_continent",
        "HostDisease",
    );
    $defaultSortAttribute = "Run";

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
