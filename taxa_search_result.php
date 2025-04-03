<?php
    include('db.php');

    $taxa = (isset($_POST['key'])) ? $_POST['key'] : "";

    $taxaQuery = "select Taxa, NCBITaxaID, Domain, TaxaLevel from taxa where Taxa like ?;";
//     echo $taxaQuery."<br/>".$taxa."<br/>";

    $conn = connect();

    $taxaStmt = $conn->prepare($taxaQuery);
    $param = "%".$taxa."%";
    $taxaStmt->bind_param("s", $param);
//     $taxaStmt->execute();
//     $taxaResult = $taxaStmt->get_result();
//     echo $taxaResult->num_rows." ".$taxaResult->field_count."<br/>";
//     $taxaRows = $taxaResult->fetch_all(MYSQLI_ASSOC);
    $taxaRows = execute_and_fetch_assoc($taxaStmt);
    $taxaStmt->close();
    $taxaJSON = json_encode($taxaRows);

    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Taxa search result - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <script type = "text/javascript" src = "js/taxa_search_result.js"></script>
        <script>
            var dataJSON = '<?php echo $taxaJSON; ?>';
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
                    <td class="nav"><a href="analysis.php" class="side_nav">Analysis</a></td>
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
                if (count($taxaRows) < 1)
                    echo "<br/><center>No taxa found in the database containing \"".$taxa."\".</center>";
                else {
            ?>
                    <?php echo "<p>Total number of taxa found in the database containing \"$taxa\" = ". count($taxaRows)."</p>";?>
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
                &#169; 2025 Bose Institute. All rights reserved. For queries, please contact Dr. Sudipto Saha
                (<a style="color:#003325;" href="mailto:ssaha4@jcbose.ac.in">ssaha4@jcbose.ac.in</a>,
                <a style="color:#003325;" href="mailto:ssaha4@gmail.com">ssaha4@gmail.com</a>).
            </p>
        </div>
    </body>
</html>
