<?php
    include('db.php');

    $domain = $_GET['key'];

    $query = "select Taxa, NCBITaxaID, TaxaLevel from taxa where Domain=?;";
//     echo $query."<br/>".$domain."<br/>";

    $conn = connect();
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $domain);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/>";
//     $rows = $result->fetch_all(MYSQLI_ASSOC);
    $rows = execute_and_fetch_assoc($stmt);
    $stmt->close();
    closeConnection($conn);

    $taxa = array();
    for ($i=0; $i<count($rows); ++$i)
        if ($rows[$i]["TaxaLevel"] == "Genus")
            $taxa["g__".$rows[$i]["Taxa"]] = $rows[$i]["NCBITaxaID"];
        elseif ($rows[$i]["TaxaLevel"] == "Species")
            $taxa["s__".$rows[$i]["Taxa"]] = $rows[$i]["NCBITaxaID"];
        else
            $taxa[$rows[$i]["Taxa"]] = $rows[$i]["NCBITaxaID"];
    $taxaJSON = json_encode($taxa);
//     echo $taxaJSON;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Domain - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
        <link rel="stylesheet" type = "text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
        <script type = "text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
        <script type = "text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
        <script>
            var domain = '<?php echo $domain; ?>';
            var taxa = JSON.parse('<?php echo $taxaJSON; ?>')
            function createTree() {
                var treeData = [{'id': 'node_'+domain, 'parent': '#', 'text': domain, 'state': {'opened': true}}];
                for (var x in taxa) {
                    treeData.push({
                        'id': 'node_' + x,
                        'parent': 'node_' + domain,
                        'text': x + ' (NCBI ID: ' + taxa[x] + ')',
                        'a_attr': {
                            'href': 'taxa.php?key=' + encodeURIComponent(x.substr(3))
                        }
                    });
                }
                $('#taxa_tree').jstree({
                    'core' : {
                        'data' : treeData,
//                         "plugins" : [ "search" ]
                    }
                });
                $('#taxa_tree').bind("select_node.jstree", function (e, data) {
                    var href = data.node.a_attr.href;
                    window.open(href, '_blank');
                });
            }
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
                if (count($rows) < 1) {
                    echo "<center><p>Error !!! No taxa available in the database for Domain: ".$domain.".</p></center>";
                } else {
                    echo "<h3 style=\"margin:0; text-align:center;\">Domain: ".$domain."</h3>";
            ?>
                    <div id="taxa_tree" style="width:100%;"></div>
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
    <script>
        createTree();
    </script>
</html>
