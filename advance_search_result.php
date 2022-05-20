<?php
    include('db.php');

    $operatorMap = array("eq"=>"=", "ne"=>"<>", "lt"=>"<", "lte"=>"<=", "gt"=>">", "gte"=>">=");
    $keyTypes = array("HostDisease"=>"S", "AssayType"=>"C", "Country"=>"C", "Continent"=>"C");
    $dbAttributeNameMap = array("HostDisease"=>"HostDisease", "AssayType"=>"AssayType", "Country"=>"geo_loc_name_country", "Continent"=>"geo_loc_name_country_continent");
    $possibleValues = array(
        "AssayType"=> array("0"=>"Amplicon","1"=>"WGS"),
        "Country"=> array("0"=>"Australia","1"=>"Canada","2"=>"China","3"=>"Denmark","4"=>"France","5"=>"Germany","6"=>"Hong Kong","7"=>"Hungary","8"=>"India","9"=>"Italy","10"=>"Japan","11"=>"Korea","12"=>"Mali","13"=>"Morocco","14"=>"Netherlands","15"=>"Taiwan","16"=>"UK","17"=>"USA"),
        "Continent"=> array("0"=>"Africa","1"=>"Asia","2"=>"Europe","3"=>"North America","4"=>"Oceania")
    );

    function assertLogicalOperator($lop) {
        return ($lop === "OR" || $lop === "AND") ? true : false;
    }

    function assertKey($key) {
        global $keyTypes;
        return array_key_exists($key, $keyTypes);
    }

    function assertOperator($op, $key) {
        global $keyTypes;
        if($keyTypes[$key] === "C")
            return ($op === "eq" || $op === "ne") ? true : false;
        if($keyTypes[$key] === "S")
            return ($op === "eq" || $op === "ne") ? true : false;
        if($keyTypes[$key] === "N")
            return ($op === "eq" || $op === "ne" || $op === "lt" || $op === "lte" || $op === "gt" || $op === "gte") ? true : false;
    }

    function assertValue($val, $key) {
        global $possibleValues;
        global $keyTypes;
        if($keyTypes[$key] === "N")
            return is_numeric($val);
        if($keyTypes[$key] === "C")
            return array_key_exists($val, $possibleValues[$key]);
        if($keyTypes[$key] === "S")
            return true;
    }
    
    function validate($predicateCount, $data, &$logicalOperators, &$keys, &$operators, &$values) {
        global $keyTypes;
        global $possibleValues;
        for($i=0; $i<$predicateCount; ++$i) {
            $pass = false;
            $k = $_POST["k".strval($i)];
            $op = $_POST["op".strval($i)];
            $val = $_POST["v".strval($i)];
//             echo $lo.",".assertLogicalOperator($lo)."<br/>";
//             echo $k.",".assertKey($k)."<br/>";
//             echo $op.",".assertOperator($op,$k)."<br/>";
//             echo $val.",".assertValue($val, $k)."<br/>";
            if(assertKey($k) && assertOperator($op, $k) && assertValue($val, $k)) {
                array_push($keys, $k);
                array_push($operators, $op);
                if ($keyTypes[$k] === "C")
                    array_push($values, $possibleValues[$k][$val]);
                elseif ($keyTypes[$k] === "N")
                    array_push($values, intval($val));
                else
                    array_push($values, $val);
                
                if($i > 0) {
                    $lo = $_POST["lo".strval($i)];
                    if(assertLogicalOperator($lo)) {
                        array_push($logicalOperators, $lo);
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }
        
        return true;
    }
    function createPredicateString($keys, $operators, $logicalOperators) {
        global $operatorMap;
        global $dbAttributeNameMap;
        $pred = "(".$dbAttributeNameMap[$keys[0]].$operatorMap[$operators[0]]."?)";
        for ($i=1; $i<count($keys); ++$i) {
            $currPred = "(".$dbAttributeNameMap[$keys[$i]].$operatorMap[$operators[$i]]."?)";
            $pred = "(".$pred.$logicalOperators[$i-1].$currPred.")";
        }
        return $pred;
    }
    function createParamString($keys) {
        global $keyTypes;
        $typeString = "";
        foreach($keys as $k) {
            if($keyTypes[$k] === "S")
                $typeString = $typeString."s";
            elseif($keyTypes[$k] === "C")
                $typeString = $typeString."s";
            elseif($keyTypes[$k] === "N")
                $typeString = $typeString."i";
//             elseif($keyTypes[$k] === "D")
//                 $typeString = $typeString."s";
//             elseif($keyTypes[$k] === "F")
//                 $typeString = $typeString."d";
        }
        return $typeString;
    }

    $predicateCount = $_POST["total_count"];
    $logicalOperators = array();
    $keys = array();
    $operators = array();
    $values = array();
    
    if (validate($predicateCount, $_POST, $logicalOperators, $keys, $operators, $values) == false) {
        echo "Validation failed !!!<br/>";
        ////TODO Redirect to error page
    }
    
//     echo implode(",", $logicalOperators)."<br/>".implode(",", $keys)."<br/>".implode(",", $operators)."<br/>".implode(",", $values)."<br/>";
    
    $pred = createPredicateString($keys, $operators, $logicalOperators);
    $paramString = createParamString($keys);
    $query = "select ".implode(",", $viewAttributes)." from meta where ".$pred.";";
//     echo $pred."<br/>";
//     echo $paramString."<br/>";
//     echo $query."<br/>";

    $conn = connect();
    $stmt = $conn->prepare($query);
    $stmt->bind_param($paramString, ...$values);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rowsJSON = json_encode($rows);
    //     echo $result->num_rows." ".$result->field_count."<br/>";
    
    $stmt->close();
    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/advance_search_result.js"></script>
        <script>
            var dataJSON = '<?php echo $rowsJSON; ?>';
            initializeData(dataJSON);
        </script>
    </head>
    <body>
        <div class = "section_header">
            <center><p class="title">MDPD - Microbiome Database of Pulmonary Diseases</p></center>
        </div>

        <div class = "section_menu">
            <center>
            <table cellpadding="3px">
                <tr class="nav">
                    <td class="nav"><a href="#" class="active">Home</a></td>
                    <td class="nav"><a href="about.html" class="side_nav">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <p>Total number of entries found in the database = <?php echo count($rows);?></p>
            <table border="1" style="width:100%;">
                <tr>
                    <td style="width:25%;">
                        Go to Page:&nbsp;
                        <input id="page_no_top" type="number" size="2" min="1" />&nbsp;
                        <button type="button" onclick="goto_page('result_display', 'page_no_top')" />Go</button>
                    </td>
                    <td style="width:15%;text-align:right;">
                        <button type="button" onclick="displayFirstPage('result_display')">First</button>&nbsp;&nbsp;
                        <button type="button" onclick="displayPrevPage('result_display')">Prev</button>&nbsp;&nbsp;
                    </td>
                    <td style="width:20%;">
                        <p id="pages_top" style="text-align:center;"></p>
                    </td>
                    <td style="width:15%;">
                        &nbsp;&nbsp;<button type="button" onclick="displayNextPage('result_display')">Next</button>&nbsp;&nbsp;
                        <button type="button" onclick="displayLastPage('result_display')">Last</button>
                    </td>
                    <td style="width:25%;"></td>
                </tr>
            </table>
            <div id="result_display">
            
            </div>
            <table border="1" style="width:100%;">
                <tr>
                    <td style="width:25%;">
                        Go to Page:&nbsp;
                        <input id="page_no_bottom" type="number" size="2" min="1" />&nbsp;
                        <button type="button" onclick="goto_page('result_display', 'page_no_bottom')" />Go</button>
                    </td>
                    <td style="width:15%;text-align:right;">
                        <button type="button" onclick="displayFirstPage('result_display')">First</button>&nbsp;&nbsp;
                        <button type="button" onclick="displayPrevPage('result_display')">Prev</button>&nbsp;&nbsp;
                    </td>
                    <td style="width:20%;">
                        <p id="pages_bottom" style="text-align:center;"></p>
                    </td>
                    <td style="width:15%;">
                        &nbsp;&nbsp;<button type="button" onclick="displayNextPage('result_display')">Next</button>&nbsp;&nbsp;
                        <button type="button" onclick="displayLastPage('result_display')">Last</button>
                    </td>
                    <td style="width:25%;"></td>
                </tr>
            </table>
        </div>
    </body>
    <script>
        displayFirstPage('result_display');
    </script>
</html>
