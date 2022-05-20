<?php
    include('db.php');
    
    $bioprojectID = $_GET['key'];
    
    $query = "select ".implode(",", $viewAttributes)." from meta where BioProject=?";
//     echo $query."<br/>";

    $conn = connect();
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $bioprojectID);
    $stmt->execute();
    $result = $stmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/>";
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rowsJSON = json_encode($rows);
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
            <?php
                if ($result->num_rows < 1) {
                    echo "<center><p>Error !!! BioProject ID: ".$bioprojectID." does not exist in the database.</p></center>";
                } else {
                    echo "<center><h3>BioProject ID: ".$bioprojectID."</h3></center>";
            ?>
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
            <?php
                }
            ?>
        </div>
    </body>
    <script>
        displayFirstPage('result_display');
    </script>
</html>
