<?php
    include 'db.php';

    $columnMap = array(
        "ds1" => "BioProject",
        "ds2" => "Grp",
        "ds3" => "PMID",
        "ds4" => "Country",
        "ds5" => "Email",
        "ds6" => "IsolationSourceBiome",
        "ds7" => "Link",
    );
    $typeMap = array(
        "ds1" => "S",
        "ds2" => "S",
        "ds3" => "S",
        "ds4" => "S",
        "ds5" => "S",
        "ds6" => "S",
        "ds7" => "S",
    );

    function convertType($text, $type) {
        if($type === "I") {
            return (strlen($text)>0) ? intval($text) : NULL;
        }
        if($type === "D") {
            if(strpos($text, "/") !== false)
                $date = date_create_from_format("m/d/Y", $text);
            else
                $date = date_create_from_format("Y-m-d", $text);
            return $date->format("Y-m-d");
        }
        if($type === "S") {
            return (strlen($text)>0) ? $text : NULL;
        }
    }

    function createParamTypeString() {
        global $typeMap;
        $typeString = "s";
        foreach($typeMap as $id=>$type) {
            if($type === "S")
                $typeString = $typeString."s";
            elseif($type === "D")
                $typeString = $typeString."s";
            elseif($type === "I")
                $typeString = $typeString."i";
        }

        return $typeString;
    }

    function createParamArray($paramText, $time) {
        global $typeMap;
        $params = array();
        array_push($params, $time);
        foreach($typeMap as $id=>$type) {
            array_push($params, convertType($paramText[$id], $type));
        }
        return $params;
    }

    function createQuery() {
        global $columnMap;
        $query = "";
        $cNameString = "TKey";
        $cNames = array_values($columnMap);
        for($i=0; $i<count($cNames); ++$i) {
            $cNameString = $cNameString.",".$cNames[$i];
        }
        $placeholderString = "?";
        for($i=0; $i<count($cNames); ++$i) {
            $placeholderString = $placeholderString.",?";
        }
        $query = "insert into data_submission (".$cNameString.") values (".$placeholderString.");";
        return $query;
    }

    function refValues($arr) {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }

    function get_submission_id() {
        $prefix = "mdpd_submit_";
        $t = microtime();
        $sec = explode(" ", $t)[0];
        $msec = explode(" ", $t)[1];
        $timestamp = ($sec*1000000 + $msec * 1000000)% 100000000000000000000000000000000;
        return $prefix . $timestamp;
    }

    function validate() {
        global $bioproject;
        global $grp;
        global $pmid;
        global $country;
        global $email;
        global $is_biome;
        global $link;

        return strlen($bioproject) > 0 && strlen($grp) > 0 && strlen($pmid) > 0 && strlen($country) > 0 && strlen($email) > 0 && strlen($is_biome) > 0 && strlen($link) > 0;
    }


    $submission_id = get_submission_id();
    $bioproject = (isset($_POST['ds1'])) ? $_POST['ds1'] : "";
    $grp = (isset($_POST['ds2'])) ? $_POST['ds2'] : "";
    $pmid = (isset($_POST['ds3'])) ? $_POST['ds3'] : "";
    $country = (isset($_POST['ds4'])) ? $_POST['ds4'] : "";
    $email = (isset($_POST['ds5'])) ? $_POST['ds5'] : "";
    $is_biome = (isset($_POST['ds6'])) ? $_POST['ds6'] : "";
    $link = (isset($_POST['ds7'])) ? $_POST['ds7'] : "";
    $data = array(
        "ds1"=>$bioproject,
        "ds2"=>$grp,
        "ds3"=>$pmid,
        "ds4"=>$country,
        "ds5"=>$email,
        "ds6"=>$is_biome,
        "ds7"=>$link
    );

    if (validate() == false) {
        $validation_failed = true;
        $message = "Validation failed !!!<br/>";
        ////TODO Redirect to error page
    } else {
        $validation_failed = false;
        $query = createQuery();
        $paramTypeString = createParamTypeString();
        // $timestamp = time();
        $params = createParamArray($data, $submission_id);
        //     echo $query."<br/>";
        //     echo $paramTypeString."<br/>";
        //     echo implode(", ", $params)."<br/>";
        $conn = connect();
        $stmt = $conn->prepare($query);
        // $stmt->bind_param($paramTypeString, ...$params);

        array_unshift($params, $paramTypeString);
        call_user_func_array(
            array($stmt, "bind_param"),
            refValues($params)
        );


        $result = $stmt->execute();
        //     echo $result;
        $stmt->close();
        closeConnection($conn);
        // echo implode("<br/>", $_POST),
        // $mail_subject = "MDPD Data Submission (" . $submission_id . ")";
        // $mail_message = "Dear Sir,\r\nA data has been submitted with the following details:\r\nBioProject:".$bioproject."\r\n".$grp."\r\n".$pmid."\r\n".$country."\r\n".$email."\r\n".$is_biome."\r\n".$link."\r\n";
        // $headers = "From: ttsudipto.php@gmail.com" . "\r\n" .
        //     "Reply-To: ttsudipto.php@gmail.com" . "\r\n" .
        //     "X-Mailer: PHP/" . phpversion();
        // $x = mail("ttsudipto@gmail.com", $mail_subject, $mail_message, $headers);
        // echo $x === true;
        // echo "foo";
        // echo $x === false;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Submit data - MDPD</title>
        <link rel="icon" href="resource/pulmonomics_lab_logo.png" type="image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel = "stylesheet" type = "text/css" href = "css/browse.css" />
<!--         <script type = "text/javascript" src = "js/analysis.js"></script> -->
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
                    <td class="nav"><a href="analysis.php" class="side_nav">Analysis</a></td>
                    <td class="nav"><a href="statistics.php" class="side_nav">Statistics</a></td>
                    <td class="nav"><a href="about.php" class="side_nav">About</a></td>
                    <td class="nav"><a href="help.html" class="side_nav">Help</a></td>
                    <td class="nav"><a href="submission.php" class="active">Submit data</a></td>
                    <td class="nav"><a href="team.html" class="side_nav">Team</a></td>
                </tr>
            </table>
            </center>
        </div>

        <!--<div class = "section_left" id="section_left">
        </div>-->

        <div class = "section_middle" style="font-size:1.2em;">
            <br/>
            <?php
                if ($validation_failed)
                    echo $message;
                else {
            ?>
                    <p>Thank you for submitting the data, we will review and update it on the database !!! </p>
                    <p>
                        The submitted details are as follows:
                        <ul>
                            <li><b>Submission ID: <i><?php echo $submission_id; ?></i></b></li>
                            <li>BioProject ID: <?php echo $bioproject; ?></li>
                            <li>Pulmonary disease name / Healthy: <?php echo $grp; ?></li>
                            <li>PMID/DOI: <?php echo $pmid; ?></li>
                            <li>Country: <?php echo $country; ?></li>
                            <li>Email ID: <?php echo $grp; ?></li>
                            <li>Isolation source / Body site: <?php echo $is_biome; ?></li>
                            <li>Link: <?php echo $link; ?></li>
                        </ul>
                    </p>
                    <p>For any further communication, please quote the "Submission ID".</p>
            <?php
                    // // echo implode("<br/>", $data);
                }
            ?>
        </div>
        <div style="clear:both">
            <br/><hr/>
            <p style="font-size:0.9em;text-align:center;">
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
