<?php
    include('db.php');

    $operatorMap = array("eq"=>"=", "ne"=>"<>", "lt"=>"<", "lte"=>"<=", "gt"=>">", "gte"=>">=");
    $keyTypes = array("Disease"=>"C", "AssayType"=>"C", "Biome"=>"C", "LibraryLayout"=>"C",
                      "Country"=>"C", "Year"=>"Y");
    $dbAttributeNameMap = array(
        "Disease"=>"disease.Grp", "AssayType"=>"run.AssayType", "Biome"=>"run.Biome",
        "LibraryLayout"=>"run.LibraryLayout", "Country"=>"run.Country", "Year"=>"run.Year"
    );
    $possibleValues = array(
        "Disease"=> array(
            "0"=>"Acute Respiratory Distress Syndrome (ARDS)", "1"=>"Asthma", "2"=>"Asthma-COPD Overlap (ACO)",
            "3"=>"Bronchiectasis", "4"=>"Bronchiolitis", "5"=>"Bronchitis", "6"=>"Chronic Obstructive Pulmonary Disease (COPD)",
            "7"=>"COPD-Bronchiectasis Association (CBA)", "8"=>"COVID-19", "9"=>"Cystic Fibrosis", "10"=>"Healthy",
            "11"=>"Idiopathic Pulmonary Fibrosis (IPF)", "12"=>"Interstitial Lung Disease (ILD)", "13"=>"Lung Cancer",
            "14"=>"Other Pulmonary Infections", "15"=>"Pneumonia", "16"=>"Pneumonitis", "17"=>"Pulmonary Hypertension",
            "18"=>"Sarcoidosis", "19"=>"Tuberculosis"
        ),

        "AssayType"=> array("0"=>"Amplicon-16S", "1"=>"Amplicon-ITS", "2"=>"WMS"),

        "Biome"=>array(
            "0"=>"Anus", "1"=>"Gut", "2"=>"Large Intestine", "3"=>"Lower Respiratory Tract", "4"=>"Lung",
            "5"=>"Nasal", "6"=>"Oral", "7"=>"Rectum", "8"=>"Stomach", "9"=>"Upper Respiratory Tract"
        ),

        "LibraryLayout"=> array("0"=>"PAIRED", "1"=>"SINGLE"),
        
        "Country"=> array(
            "0"=>"Argentina", "1"=>"Australia", "2"=>"Austria", "3"=>"Bangladesh", "4"=>"Belgium", "5"=>"Brazil",
            "6"=>"Canada", "7"=>"Chile", "8"=>"China", "9"=>"Colombia", "10"=>"Czechia", "11"=>"Denmark", "12"=>"Egypt",
            "13"=>"Ethiopia", "14"=>"France", "15"=>"Gambia", "16"=>"Germany", "17"=>"Ghana", "18"=>"Greece", "19"=>"Hong Kong",
            "20"=>"Hungary", "21"=>"India", "22"=>"Ireland", "23"=>"Israel", "24"=>"Italy", "25"=>"Japan", "26"=>"Jordan",
            "27"=>"Kuwait", "28"=>"Kyrgyzstan", "29"=>"Luxembourg", "30"=>"Malaysia", "31"=>"Mali", "32"=>"Mexico",
            "33"=>"Morocco", "34"=>"Nepal", "35"=>"Netherlands", "36"=>"New Zealand", "37"=>"Norway", "38"=>"Panama",
            "39"=>"Peru", "40"=>"Poland", "41"=>"Portugal", "42"=>"Russia", "43"=>"Singapore", "44"=>"South Africa",
            "45"=>"South Korea", "46"=>"Spain", "47"=>"Sri Lanka", "48"=>"Sweden", "49"=>"Switzerland", "50"=>"Taiwan",
            "51"=>"Turkey", "52"=>"Uganda", "53"=>"United Kingdom", "54"=>"USA"
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
            return is_numeric($val) && (intval($val) >= 2012) && (intval($val) <= 2024);
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
//     echo count($rows)."<br/>";
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
                    <td class="nav"><a href="browse.php" class="side_nav">Browse</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="about.php" class="side_nav">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left"></div>-->
        
        <div class = "section_middle">
            <?php
                if (count($rows) < 1)
                    echo "<br/><center>No entries found in the database for the given query.</center>";
                else {
            ?>
                    <div id="download_div" style="width:100%; text-align:center; margin-top:20px;">
                        <a id="download_button" onclick="createDownloadLink()" download="search_result.csv">
                            <button type="button" style="margin:2px;">Download table</button>
                        </a>
                    </div>
                    <p>Total number of entries found in the database = <?php echo count($rows);?></p>
                    <table border="0" style="width:100%; border:3px solid #004d99;">
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
                    <table border="0" style="width:100%; border:3px solid #004d99;">
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
                    <script>
                        displayFirstPage('result_display');
                    </script>
            <?php
                }
            ?>
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2023 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
