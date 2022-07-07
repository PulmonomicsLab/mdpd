<?php
    include('db.php');
        
    $choroplethQuery = "select Country, count(Run) as RunCount from run group by Country;";
//     echo $bioprojectQuery." ".$disease;
    
    $conn = connect();
    
    $choroplethStmt = $conn->prepare($choroplethQuery);
    $choroplethStmt->execute();
    $choroplethResult = $choroplethStmt->get_result();
//     echo $bioprojectResult->num_rows." ".$bioprojectResult->field_count."<br/><br/>";
    $rows = $choroplethResult->fetch_all(MYSQLI_ASSOC);
    $rowsJSON = json_encode($rows);
    $choroplethStmt->close();
    closeConnection($conn);
    echo $rowsJSON;
?>
