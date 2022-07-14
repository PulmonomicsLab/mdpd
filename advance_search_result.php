<?php
    include('db.php');

    $operatorMap = array("eq"=>"=", "ne"=>"<>", "lt"=>"<", "lte"=>"<=", "gt"=>">", "gte"=>">=");
    $keyTypes = array("AssayType"=>"C","Country"=>"C","Instrument"=>"C","Year"=>"Y","IsolationSource"=>"C","Disease"=>"C");
    $dbAttributeNameMap = array(
        "AssayType"=>"run.AssayType","Country"=>"run.Country","Instrument"=>"run.Instrument",
        "Year"=>"run.ReleaseYear","IsolationSource"=>"run.IsolationSource","Disease"=>"disease.Grp"
    );
    $possibleValues = array(
        "AssayType"=> array("0"=>"Amplicon", "1"=>"WGS"),
        
        "Country"=> array(
            '0'=> 'Australia', '1'=> 'Bangladesh', '2'=> 'Belgium', '3'=> 'Brazil',  
            '4'=> 'China', '5'=> 'Czech Republic', '6'=> 'Germany', '7'=> 'Hungary', 
            '8'=> 'India', '9'=> 'Italy', '10'=> 'Japan', '11'=> 'Mali', '12'=> 'Morocco', 
            '13'=> 'Nepal', '14'=> 'Netherlands', '15'=> 'Peru', '16'=> 'Poland', 
            '17'=> 'Russia', '18'=> 'South Africa', '19'=> 'South Korea', '20'=> 'Spain', 
            '21'=> 'Srilanka', '22'=> 'Switzerland', '23'=> 'Taiwan', '24'=> 'United Kingdom', 
            '25'=> 'USA'
        ),
        
        "Instrument"=> array(
            "0"=>"HiSeq 2000", "1"=>"HiSeq 2500", "2"=>"HiSeq 4000", "3"=>"MiSeq", 
            "4"=>"NovaSeq 6000", "5"=>"NextSeq 500","6"=>"NextSeq 550"
        ),
        
        "IsolationSource"=> array(
            '0'=> 'BAL', '1'=> 'Bronchial Brush', '2'=> 'Bronchial Mucosa', '3'=> 'Colon Mucus', 
            '4'=> 'Endotracheal Aspirate', '5'=> 'Lung Biopsy', '6'=> 'Lung Tissue',
            '7'=> 'Lung Tumour Tissue', '8'=> 'Sputum', '9'=> 'Stool', '10'=> 'Supraglottic Swab'
        ),
        
        "Disease"=> array(
            "0"=>"Asthma", "1"=>"COPD", "2"=>"COVID-19", "3"=>"Cystic Fibrosis", "4"=>"Lung cancer", 
            "5"=>"Pneumonia", "6"=>"Tuberculosis"
        )
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
        if($keyTypes[$key] === "N" || $keyTypes[$key] === "Y")
            return ($op === "eq" || $op === "ne" || $op === "lt" || $op === "lte" || $op === "gt" || $op === "gte") ? true : false;
    }

    function assertValue($val, $key) {
        global $possibleValues;
        global $keyTypes;
        if($keyTypes[$key] === "N")
            return is_numeric($val);
        if($keyTypes[$key] === "Y")
            return is_numeric($val) && (intval($val) >= 1900) && (intval($val) <= date("Y"));
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
                elseif ($keyTypes[$k] === "N" || $keyTypes[$k] === "Y")
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
            elseif($keyTypes[$k] === "N" || $keyTypes[$k] === "Y")
                $typeString = $typeString."i";
//             elseif($keyTypes[$k] === "D")
//                 $typeString = $typeString."s";
//             elseif($keyTypes[$k] === "F")
//                 $typeString = $typeString."d";
        }
        return $typeString;
    }
    
    function refValues($arr){
        $refs = array();
        for($i=0; $i<count($arr); ++$i)
            $refs[$i] = &$arr[$i];
        return $refs;
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
//     $query = "select ".implode(",", $viewAttributes)." from run,bioproject where (run.BioProject=bioproject.BioProject)AND".$pred.";";
    $query = "select ".implode(",", $viewAttributes)." from ((run inner join bioproject on run.BioProject=bioproject.BioProject) inner join disease on run.SubGroup=disease.SubGroup) where ".$pred." order by run.Run;";
//     echo $pred."<br/>";
//     echo $paramString."<br/>";
//     echo $query."<br/>";

    $conn = connect();
    $stmt = $conn->prepare($query);
    
    array_unshift($values, $paramString);
    call_user_func_array(
        array($stmt, "bind_param"),
        refValues($values)
    );
    $stmt->execute();

//     $stmt->bind_param($paramString, ...$values);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($stmt);
//     foreach($rows as $row) {
//         echo implode(",", $row)."<br/>";
//     }
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
                    <td class="nav"><a href="index.php" class="side_nav">Home</a></td>
                    <td class="nav"><a href="advance_search.html" class="active">Search</a></td>
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <p>Total number of entries found in the database = <?php echo count($rows);?></p>
            <table border="0" style="width:100%; border:4px solid #392d37;">
                <tr>
                    <td style="width:25%;">
                        Go to Page:&nbsp;
                        <input id="page_no_top" type="number" size="2" min="1" style="width:50px;" />&nbsp;
                        <button type="button" class="round" onclick="goto_page('result_display', 'page_no_top')" />Go</button>
                    </td>
                    <td style="width:15%;text-align:right;">
                        <button type="button" class="round" onclick="displayFirstPage('result_display')">First</button>&nbsp;&nbsp;
                        <button type="button" class="round" onclick="displayPrevPage('result_display')">Prev</button>&nbsp;&nbsp;
                    </td>
                    <td style="width:20%;">
                        <p id="pages_top" style="text-align:center;"></p>
                    </td>
                    <td style="width:15%;">
                        &nbsp;&nbsp;<button type="button" class="round" onclick="displayNextPage('result_display')">Next</button>&nbsp;&nbsp;
                        <button type="button" class="round" onclick="displayLastPage('result_display')">Last</button>
                    </td>
                    <td style="width:25%;"></td>
                </tr>
            </table>
            <div id="result_display">
            
            </div>
            <table border="0" style="width:100%; border:4px solid #392d37;">
                <tr>
                    <td style="width:25%;">
                        Go to Page:&nbsp;
                        <input id="page_no_bottom" type="number" size="2" min="1" style="width:50px;" />&nbsp;
                        <button type="button" class="round" onclick="goto_page('result_display', 'page_no_bottom')" />Go</button>
                    </td>
                    <td style="width:15%;text-align:right;">
                        <button type="button" class="round" onclick="displayFirstPage('result_display')">First</button>&nbsp;&nbsp;
                        <button type="button" class="round" onclick="displayPrevPage('result_display')">Prev</button>&nbsp;&nbsp;
                    </td>
                    <td style="width:20%;">
                        <p id="pages_bottom" style="text-align:center;"></p>
                    </td>
                    <td style="width:15%;">
                        &nbsp;&nbsp;<button type="button" class="round" onclick="displayNextPage('result_display')">Next</button>&nbsp;&nbsp;
                        <button type="button" class="round" onclick="displayLastPage('result_display')">Last</button>
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
