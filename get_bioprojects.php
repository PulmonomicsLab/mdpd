<?php
    include('db.php');

    $key = urldecode($_GET['key']);
    $keyType = urldecode($_GET["keyType"]);

    if ($keyType == "ds") {
        $query = "select ".implode(",", $viewBioProjectAttributes)." from bioproject where Grp like ?";
        $key = "%".$key."%";
    } elseif ($keyType == "sg") {
        $query = "select ".implode(",", $viewBioProjectAttributes)." from bioproject where BioProject in (select distinct(BioProject) from run where SubGroup = ?);";
    } elseif ($keyType == "is") {
        $query = "select ".implode(",", $viewBioProjectAttributes)." from bioproject where BioProject in (select distinct(BioProject) from run where IsolationSource = ?);";
    }
//     echo $query." ".$key;

    $conn = connect();

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $key);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/><br/>";
//     $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($stmt);
    $rowsJSON = json_encode($rows);
    $stmt->close();
    closeConnection($conn);
    echo $rowsJSON;
?>

