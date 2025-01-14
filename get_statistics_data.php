<?php
//     Replace all Grp to disease.Grp for mariadb
//     Replace all Biome to run.Biome for mariadb
//     Replace all AssayType to run.AssayType for mariadb


    include('db.php');

    $totalQuery = "select count(Run) as C from run;";
    $diseaseCountQuery = "select disease.Grp, count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup group by disease.Grp order by disease.Grp;";
    $diseaseATCountQuery = "select disease.Grp, AssayType, count(Run) as C from run inner join disease on run.SubGroup = disease.SubGroup group by disease.Grp, AssayType order by disease.Grp, AssayType;";
    $diseaseBiomeCountQuery = "select disease.Grp, Biome, count(Run) as DiseaseCount from run inner join disease on run.SubGroup = disease.SubGroup group by disease.Grp, Biome";
    $biomeQuery = "select Biome, count(Run) as BiomeCount from run group by Biome order by Biome";
    $assayTypeQuery = "select AssayType, count(Run) as AssayTypeCount from run group by AssayType order by AssayType";
    $queries = array(
        $totalQuery, $diseaseCountQuery, $diseaseATCountQuery,
        $diseaseBiomeCountQuery, $biomeQuery, $assayTypeQuery
    );
    
    $labels = array(
        'totalCountResult', 'diseaseCountResult', 'diseaseATCountResult',
        'diseaseBiomeCountResult', 'biomeResult', 'assayTypeResult'
    );
    
    $output = array();
    
    $conn = connect();
    
    for ($i=0; $i<count($queries); ++$i) {
        $stmt = $conn -> prepare($queries[$i]);
        $rows = execute_and_fetch_assoc($stmt);
        $output[$labels[$i]] = $rows;
        $stmt->close();
        // echo $rows[0]["C"]." -> ".$labels[$i]."<br/>";
    }
    $outputJSON = json_encode($output);
    echo $outputJSON;
    closeConnection($conn);
?>

