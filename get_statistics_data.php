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
    $asthmaLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Lung' and AssayType='WMS';";
    $asthmaGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Gut' and AssayType='Amplicon';";
    $asthmaGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Asthma' and Biome='Gut' and AssayType='WMS';";
    $copdLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Lung' and AssayType='Amplicon';";
    $copdLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Lung' and AssayType='WMS';";
    $copdGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Gut' and AssayType='Amplicon';";
    $copdGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COPD' and Biome='Gut' and AssayType='WMS';";
    $covidLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Lung' and AssayType='Amplicon';";
    $covidLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Lung' and AssayType='WMS';";
    $covidGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Gut' and AssayType='Amplicon';";
    $covidGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='COVID-19' and Biome='Gut' and AssayType='WMS';";
    $cfLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Lung' and AssayType='Amplicon';";
    $cfLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Lung' and AssayType='WMS';";
    $cfGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Gut' and AssayType='Amplicon';";
    $cfGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Cystic Fibrosis' and Biome='Gut' and AssayType='WMS';";
    $lcLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Lung' and AssayType='Amplicon';";
    $lcLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Lung' and AssayType='WMS';";
    $lcGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Gut' and AssayType='Amplicon';";
    $lcGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Lung Cancer' and Biome='Gut' and AssayType='WMS';";
    $pneumoniaLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Lung' and AssayType='Amplicon';";
    $pneumoniaLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Lung' and AssayType='WMS';";
    $pneumoniaGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Gut' and AssayType='Amplicon';";
    $pneumoniaGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Pneumonia' and Biome='Gut' and AssayType='WMS';";
    $tbLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Lung' and AssayType='Amplicon';";
    $tbLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Lung' and AssayType='WMS';";
    $tbGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Gut' and AssayType='Amplicon';";
    $tbGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Tuberculosis' and Biome='Gut' and AssayType='WMS';";
    $controlLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Lung' and AssayType='Amplicon';";
    $controlLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Lung' and AssayType='WMS';";
    $controlGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Gut' and AssayType='Amplicon';";
    $controlGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Control' and Biome='Gut' and AssayType='WMS';";
    $healthyLungAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Lung' and AssayType='Amplicon';";
    $healthyLungWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Lung' and AssayType='WMS';";
    $healthyGutAmpQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Gut' and AssayType='Amplicon';";
    $healthyGutWMSQuery = "select count(Run) as C from run inner join disease on run.SubGroup=disease.SubGroup where Grp='Healthy' and Biome='Gut' and AssayType='WMS';";
    
    $lungQuery = "select count(Run) as C from run where Biome='Lung';";
    $gutQuery = "select count(Run) as C from run where Biome='Gut';";
    $ampQuery = "select count(Run) as C from run where AssayType='Amplicon';";
    $wmsQuery = "select count(Run) as C from run where AssayType='WMS';";

    $querries = array(
        $totalQuery, $asthmaQuery, $copdQuery, $covidQuery, $cfQuery, $lcQuery, $pneumoniaQuery, $tbQuery, $controlQuery, $healthyQuery,

        $asthmaLungQuery, $asthmaGutQuery, $copdLungQuery, $copdGutQuery, $covidLungQuery, $covidGutQuery, $cfLungQuery, $cfGutQuery, $lcLungQuery, $lcGutQuery,
        $pneumoniaLungQuery, $pneumoniaGutQuery, $tbLungQuery, $tbGutQuery, $controlLungQuery, $controlGutQuery, $healthyLungQuery, $healthyGutQuery,

        $asthmaLungAmpQuery, $asthmaGutAmpQuery, $copdLungAmpQuery, $copdGutAmpQuery, $covidLungAmpQuery, $covidGutAmpQuery, $cfLungAmpQuery, $cfGutAmpQuery, $lcLungAmpQuery, $lcGutAmpQuery,
        $pneumoniaLungAmpQuery, $pneumoniaGutAmpQuery, $tbLungAmpQuery, $tbGutAmpQuery, $controlLungAmpQuery, $controlGutAmpQuery, $healthyLungAmpQuery, $healthyGutAmpQuery,
        $asthmaLungWMSQuery, $asthmaGutWMSQuery, $copdLungWMSQuery, $copdGutWMSQuery, $covidLungWMSQuery, $covidGutWMSQuery, $cfLungWMSQuery, $cfGutWMSQuery, $lcLungWMSQuery, $lcGutWMSQuery,
        $pneumoniaLungWMSQuery, $pneumoniaGutWMSQuery, $tbLungWMSQuery, $tbGutWMSQuery, $controlLungWMSQuery, $controlGutWMSQuery, $healthyLungWMSQuery, $healthyGutWMSQuery,
        
        $lungQuery, $gutQuery, $ampQuery, $wmsQuery,
    );
    
    $labels = array(
        'totalCount', 'asthmaCount', 'copdCount', 'covidCount', 'cfCount', 'lcCount', 'pneumoniaCount', 'tbCount', 'controlCount', 'healthyCount',
        'asthmaLungCount', 'asthmaGutCount', 'copdLungCount', 'copdGutCount', 'covidLungCount', 'covidGutCount', 'cfLungCount', 'cfGutCount', 'lcLungCount', 'lcGutCount',
        'pneumoniaLungCount', 'pneumoniaGutCount', 'tbLungCount', 'tbGutCount', 'controlLungCount', 'controlGutCount', 'healthyLungCount', 'healthyGutCount',

        'asthmaLungAmpCount', 'asthmaGutAmpCount', 'copdLungAmpCount', 'copdGutAmpCount', 'covidLungAmpCount', 'covidGutAmpCount', 'cfLungAmpCount', 'cfGutAmpCount', 'lcLungAmpCount', 'lcGutAmpCount',
        'pneumoniaLungAmpCount', 'pneumoniaGutAmpCount', 'tbLungAmpCount', 'tbGutAmpCount', 'controlLungAmpCount', 'controlGutAmpCount', 'healthyLungAmpCount', 'healthyGutAmpCount',
        'asthmaLungWMSCount', 'asthmaGutWMSCount', 'copdLungWMSCount', 'copdGutWMSCount', 'covidLungWMSCount', 'covidGutWMSCount', 'cfLungWMSCount', 'cfGutWMSCount', 'lcLungWMSCount', 'lcGutWMSCount',
        'pneumoniaLungWMSCount', 'pneumoniaGutWMSCount', 'tbLungWMSCount', 'tbGutWMSCount', 'controlLungWMSCount', 'controlGutWMSCount', 'healthyLungWMSCount', 'healthyGutWMSCount',
        
        'lungCount', 'gutCount', 'ampCount', 'wmsCount',
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

