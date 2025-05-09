<?php
    include('db.php');

    $disease = isset($_POST["disease"]) ? urldecode($_POST["disease"]) : "";
    $assayType = isset($_POST["assayType"]) ? urldecode($_POST["assayType"]) : "";
    $subGroups = isset($_POST["subGroups"]) ? json_decode(urldecode($_POST["subGroups"])) : array();
    $isolationSources = isset($_POST["isolationSources"]) ? json_decode(urldecode($_POST["isolationSources"])) : array();
    $libraryLayouts = isset($_POST["libraryLayout"]) ? json_decode(urldecode($_POST["libraryLayout"])) : array();

    function getSubGroupSubquery() {
        global $subGroups;
        global $isolationSources;
        global $libraryLayouts;
        $subquery = "select distinct BioProject from run where AssayType like ? and ";
        $subGroupPredicates = array();
        for ($i = 0; $i < count($subGroups); ++$i)
            array_push($subGroupPredicates, "SubGroup=?");
        $isolationSourcePredicates = array();
        for ($i = 0; $i < count($isolationSources); ++$i)
            array_push($isolationSourcePredicates, "IsolationSource=?");
        $libraryLayoutPredicates = array();
        for ($i = 0; $i < count($libraryLayouts); ++$i)
            array_push($libraryLayoutPredicates, "LibraryLayout=?");
        $subgroupPredicatesString = (count($subGroupPredicates) > 0) ? implode(" or ", $subGroupPredicates) : "1=1";
        $isolationSourcePredicatesString = (count($isolationSourcePredicates) > 0) ? implode(" or ", $isolationSourcePredicates) : "1=1";
        $libraryLayoutPredicatesString = (count($libraryLayoutPredicates) > 0) ? implode(" or ", $libraryLayoutPredicates) : "1=1";
        return "(" . $subquery . "(" . $subgroupPredicatesString . ") and (" . $isolationSourcePredicatesString . ") and (" . $libraryLayoutPredicatesString . "))";
//         return "(" . $subquery . "(" . implode(" or ", $subGroupPredicates) . ") and (" . implode(" or ", $isolationSourcePredicates) . ") and (" . implode(" or ", $libraryLayoutPredicates) . "))";
    }

    function getParamString() {
        global $subGroups;
        global $isolationSources;
        global $libraryLayouts;
        $paramString = "s"; // AssayType
        for ($i = 0; $i < count($subGroups); ++$i)
            $paramString = $paramString."s";
        for ($i = 0; $i < count($isolationSources); ++$i)
            $paramString = $paramString."s";
        for ($i = 0; $i < count($libraryLayouts); ++$i)
            $paramString = $paramString."s";
        return $paramString;
    }

    function getValues() {
        global $assayType;
        global $subGroups;
        global $isolationSources;
        global $libraryLayouts;
        $values = array("%".$assayType."%");
        foreach ($subGroups as $sg)
            array_push($values, $sg);
        foreach ($isolationSources as $is)
            array_push($values, $is);
        foreach ($libraryLayouts as $lib)
            array_push($values, $lib);
        return $values;
    }

    function refValues($arr){
        $refs = array();
        for($i=0; $i<count($arr); ++$i)
            $refs[$i] = &$arr[$i];
        return $refs;
    }

    $bioprojectQuery = "select BioProject, Grp, SubGroup, IsolationSource, Biome, AssayType, LibraryLayout from bioproject where BioProject in ".getSubGroupSubquery().";";
    $paramString = getParamString();
    $values = getValues();
//     echo $bioprojectQuery."<br/>";
//     echo $paramString."<br/>";
//     echo implode(", ", $values)."<br/>";

    $conn = connect();
    $bioprojectStmt = $conn->prepare($bioprojectQuery);

    array_unshift($values, $paramString);
    call_user_func_array(
        array($bioprojectStmt, "bind_param"),
        refValues($values)
    );
    $bioprojectStmt->execute();

    $rows = execute_and_fetch_assoc($bioprojectStmt);
    $rowsJSON = json_encode($rows);
    $bioprojectStmt->close();
    closeConnection($conn);
    echo $rowsJSON;
?>
