<?php
    include('db.php');

    $disease = urldecode($_GET['key']);

    $bioprojectQuery = "select ".implode(",", $viewBioProjectAttributes)." from bioproject where SubGroup like ?;";
//     echo $bioprojectQuery." ".$disease;

    $conn = connect();

    $bioprojectStmt = $conn->prepare($bioprojectQuery);
    $disease = "%".$disease."%";
    $bioprojectStmt->bind_param("s", $disease);
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
