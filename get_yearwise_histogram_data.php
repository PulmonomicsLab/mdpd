<?php
    include('db.php');

    $type = $_GET["type"];

    if ($type == "AssayType")
        $histogramQuery = "select ReleaseYear, count(case when AssayType = 'Amplicon' then 1 end) as AmpliconRunCount, count(case when AssayType = 'WGS' then 1 end) as WGSRunCount from run group by ReleaseYear order by ReleaseYear;";
    elseif ($type ==  "Biome")
        $histogramQuery = "select ReleaseYear, count(case when Biome = 'Lung' then 1 end) as LungRunCount, count(case when Biome = 'Gut' then 1 end) as GutRunCount from run group by ReleaseYear order by ReleaseYear;";
    elseif ($type == "Disease")
        $histogramQuery = "select ReleaseYear, count(case when Grp = 'Asthma' then 1 end) as AsthmaRunCount, count(case when Grp = 'Control' then 1 end) as ControlRunCount, count(case when Grp = 'COPD' then 1 end) as COPDRunCount, count(case when Grp = 'COVID-19' then 1 end) as COVIDRunCount, count(case when Grp = 'Cystic Fibrosis' then 1 end) as CFRunCount, count(case when Grp = 'Healthy' then 1 end) as HealthyRunCount, count(case when Grp = 'Lung Cancer' then 1 end) as LCRunCount, count(case when Grp = 'Pneumonia' then 1 end) as PneumoniaRunCount, count(case when Grp = 'Tuberculosis' then 1 end) as TBRunCount from run inner join disease on run.SubGroup=disease.Subgroup group by ReleaseYear order by ReleaseYear;";

//     echo $bioprojectQuery." ".$disease;

    $conn = connect();

    $histogramStmt = $conn->prepare($histogramQuery);
//     $histogramStmt->execute();
//     $histogramResult = $histogramStmt->get_result();
//     echo $bioprojectResult->num_rows." ".$bioprojectResult->field_count."<br/><br/>";
//     $rows = $histogramResult->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($histogramStmt);
    $rowsJSON = json_encode($rows);
    $histogramStmt->close();
    closeConnection($conn);
    echo $rowsJSON;
?>
