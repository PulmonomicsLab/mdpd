<?php
//     Replace all Grp to run.Grp for mariadb

    include('db.php');

    $type = $_GET["type"];

    if ($type == "AssayType")
        $histogramQuery = "select Year, count(case when AssayType = 'Amplicon-16S' then 1 end) as Amplicon16SRunCount, count(case when AssayType = 'Amplicon-ITS' then 1 end) as AmpliconITSRunCount, count(case when AssayType = 'WMS' then 1 end) as WMSRunCount from run group by Year order by Year;";
    elseif ($type ==  "Biome")
        $histogramQuery = "select Year, Biome, count(Run) as Counts from run inner join disease on run.SubGroup = disease.SubGroup group by Year, Biome;";
    elseif ($type == "Disease")
        $histogramQuery = "select Year, disease.Grp, count(Run) as Counts from run inner join disease on run.SubGroup = disease.SubGroup group by Year, disease.Grp;";

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
