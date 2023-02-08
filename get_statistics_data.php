<?php
    include('db.php');

    $totalQuery = "select count(Run) as C from run;";

    $asthmaQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma';";
    $copdQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD';";
    $covidQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19';";
    $cfQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis';";
    $lcQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer';";
    $pneumoniaQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia';";
    $tbQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis';";
    $controlQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control';";
    $healthyQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy';";

    $asthmaLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Lung';";
    $asthmaGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Gut';";
    $copdLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Lung';";
    $copdGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Gut';";
    $covidLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Lung';";
    $covidGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Gut';";
    $cfLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Lung';";
    $cfGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Gut';";
    $lcLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Lung';";
    $lcGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Gut';";
    $pneumoniaLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Lung';";
    $pneumoniaGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Gut';";
    $tbLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Lung';";
    $tbGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Gut';";
    $controlLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Lung';";
    $controlGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Gut';";
    $healthyLungQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Lung';";
    $healthyGutQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Gut';";

    $asthmaLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Lung' and AssayType='Amplicon';";
    $asthmaLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Lung' and AssayType='WGS';";
    $asthmaGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Gut' and AssayType='Amplicon';";
    $asthmaGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Gut' and AssayType='WGS';";
    $copdLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Lung' and AssayType='Amplicon';";
    $copdLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Lung' and AssayType='WGS';";
    $copdGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Gut' and AssayType='Amplicon';";
    $copdGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Gut' and AssayType='WGS';";
    $covidLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Lung' and AssayType='Amplicon';";
    $covidLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Lung' and AssayType='WGS';";
    $covidGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Gut' and AssayType='Amplicon';";
    $covidGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Gut' and AssayType='WGS';";
    $cfLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Lung' and AssayType='Amplicon';";
    $cfLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Lung' and AssayType='WGS';";
    $cfGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Gut' and AssayType='Amplicon';";
    $cfGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Gut' and AssayType='WGS';";
    $lcLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Lung' and AssayType='Amplicon';";
    $lcLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Lung' and AssayType='WGS';";
    $lcGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Gut' and AssayType='Amplicon';";
    $lcGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Gut' and AssayType='WGS';";
    $pneumoniaLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Lung' and AssayType='Amplicon';";
    $pneumoniaLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Lung' and AssayType='WGS';";
    $pneumoniaGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Gut' and AssayType='Amplicon';";
    $pneumoniaGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Gut' and AssayType='WGS';";
    $tbLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Lung' and AssayType='Amplicon';";
    $tbLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Lung' and AssayType='WGS';";
    $tbGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Gut' and AssayType='Amplicon';";
    $tbGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Gut' and AssayType='WGS';";
    $controlLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Lung' and AssayType='Amplicon';";
    $controlLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Lung' and AssayType='WGS';";
    $controlGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Gut' and AssayType='Amplicon';";
    $controlGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Gut' and AssayType='WGS';";
    $healthyLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Lung' and AssayType='Amplicon';";
    $healthyLungWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Lung' and AssayType='WGS';";
    $healthyGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Gut' and AssayType='Amplicon';";
    $healthyGutWGSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Gut' and AssayType='WGS';";
    
    $lungQuery = "select count(Run) as C from run where Biome='Lung';";
    $gutQuery = "select count(Run) as C from run where Biome='Gut';";
    $ampQuery = "select count(Run) as C from run where AssayType='Amplicon';";
    $wgsQuery = "select count(Run) as C from run where AssayType='WGS';";

    $querries = array(
        $totalQuery, $asthmaQuery, $copdQuery, $covidQuery, $cfQuery, $lcQuery, $pneumoniaQuery, $tbQuery, $controlQuery, $healthyQuery,

        $asthmaLungQuery, $asthmaGutQuery, $copdLungQuery, $copdGutQuery, $covidLungQuery, $covidGutQuery, $cfLungQuery, $cfGutQuery, $lcLungQuery, $lcGutQuery,
        $pneumoniaLungQuery, $pneumoniaGutQuery, $tbLungQuery, $tbGutQuery, $controlLungQuery, $controlGutQuery, $healthyLungQuery, $healthyGutQuery,

        $asthmaLungAmpQuery, $asthmaGutAmpQuery, $copdLungAmpQuery, $copdGutAmpQuery, $covidLungAmpQuery, $covidGutAmpQuery, $cfLungAmpQuery, $cfGutAmpQuery, $lcLungAmpQuery, $lcGutAmpQuery,
        $pneumoniaLungAmpQuery, $pneumoniaGutAmpQuery, $tbLungAmpQuery, $tbGutAmpQuery, $controlLungAmpQuery, $controlGutAmpQuery, $healthyLungAmpQuery, $healthyGutAmpQuery,
        $asthmaLungWGSQuery, $asthmaGutWGSQuery, $copdLungWGSQuery, $copdGutWGSQuery, $covidLungWGSQuery, $covidGutWGSQuery, $cfLungWGSQuery, $cfGutWGSQuery, $lcLungWGSQuery, $lcGutWGSQuery,
        $pneumoniaLungWGSQuery, $pneumoniaGutWGSQuery, $tbLungWGSQuery, $tbGutWGSQuery, $controlLungWGSQuery, $controlGutWGSQuery, $healthyLungWGSQuery, $healthyGutWGSQuery,
        
        $lungQuery, $gutQuery, $ampQuery, $wgsQuery,
    );
    
    $labels = array(
        'totalCount', 'asthmaCount', 'copdCount', 'covidCount', 'cfCount', 'lcCount', 'pneumoniaCount', 'tbCount', 'controlCount', 'healthyCount',
        'asthmaLungCount', 'asthmaGutCount', 'copdLungCount', 'copdGutCount', 'covidLungCount', 'covidGutCount', 'cfLungCount', 'cfGutCount', 'lcLungCount', 'lcGutCount',
        'pneumoniaLungCount', 'pneumoniaGutCount', 'tbLungCount', 'tbGutCount', 'controlLungCount', 'controlGutCount', 'healthyLungCount', 'healthyGutCount',

        'asthmaLungAmpCount', 'asthmaGutAmpCount', 'copdLungAmpCount', 'copdGutAmpCount', 'covidLungAmpCount', 'covidGutAmpCount', 'cfLungAmpCount', 'cfGutAmpCount', 'lcLungAmpCount', 'lcGutAmpCount',
        'pneumoniaLungAmpCount', 'pneumoniaGutAmpCount', 'tbLungAmpCount', 'tbGutAmpCount', 'controlLungAmpCount', 'controlGutAmpCount', 'healthyLungAmpCount', 'healthyGutAmpCount',
        'asthmaLungWGSCount', 'asthmaGutWGSCount', 'copdLungWGSCount', 'copdGutWGSCount', 'covidLungWGSCount', 'covidGutWGSCount', 'cfLungWGSCount', 'cfGutWGSCount', 'lcLungWGSCount', 'lcGutWGSCount',
        'pneumoniaLungWGSCount', 'pneumoniaGutWGSCount', 'tbLungWGSCount', 'tbGutWGSCount', 'controlLungWGSCount', 'controlGutWGSCount', 'healthyLungWGSCount', 'healthyGutWGSCount',
        
        'lungCount', 'gutCount', 'ampCount', 'wgsCount',
    );
    
    $output = array();
    
    $conn = connect();
    
    for ($i=0; $i<count($querries); ++$i) {
        $stmt = $conn -> prepare($querries[$i]);
        $rows = execute_and_fetch_assoc($stmt);
        $output[$labels[$i]] = $rows[0]["C"];
        $stmt->close();
        // echo $rows[0]["C"]." -> ".$labels[$i]."<br/>";
    }
    $outputJSON = json_encode($output);
    echo $outputJSON;
    closeConnection($conn);
?>

