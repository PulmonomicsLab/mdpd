<?php
    include('db.php');

    $is = urldecode($_GET['key']);

    $bioprojectQuery = "select ".implode(",", $viewBioProjectAttributes)." from bioproject where BioProject in (select distinct(BioProject) from run where IsolationSource = ?);";
//     echo $bioprojectQuery." ".$disease;

    $conn = connect();

    $bioprojectStmt = $conn->prepare($bioprojectQuery);
    $bioprojectStmt->bind_param("s", $is);
//     $bioprojectStmt->execute();
//     $bioprojectResult = $bioprojectStmt->get_result();
//     echo $bioprojectResult->num_rows." ".$bioprojectResult->field_count."<br/><br/>";
//     $rows = $bioprojectResult->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($bioprojectStmt);
    $rowsJSON = json_encode($rows);
    $bioprojectStmt->close();
    closeConnection($conn);
    echo $rowsJSON;
?>
