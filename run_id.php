<?php
    include('db.php');
    
    $runID = $_GET['key'];
    
    $query = "select ".implode(",", $allAttributes)." from meta where Run=?";
//     echo $query."<br/>";

    $conn = connect();
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $runID);
    $stmt->execute();
    $result = $stmt->get_result();
//     echo $result->num_rows." ".$result->field_count."<br/>";
//     $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    closeConnection($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home - MDPD</title>
        <link rel = "stylesheet" type = "text/css" href = "css/main.css" />
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
                    echo "<center><p>Error !!! Run ID: ".$runID." does not exist in the database.</p></center>";
                } else {
                    echo "<center><h3>Run ID: ".$runID."</h3></center><hr/><br/>";
                    echo "<table class=\"details\" border=\"1\">";
                    echo "<tr><th>Attribute</th><th>Value</th></tr>";
                    while ($row = $result->fetch_assoc()){
                        foreach ($attributeFriendlyNames as $name=>$fname) {
                            if ($name !== "Run") {
                                echo "<tr>";
                                echo "<td style=\"width:40%\">".$fname."</td>";
                                if ($name === "BioProject")
                                    echo "<td style=\"width:60%\"><a target=\"_blank\" href=\"bioproject_id.php?key=".$row[$name]."\">".$row[$name]."</a></td>";
                                else
                                    echo "<td style=\"width:60%\">".$row[$name]."</td>";
                                echo "</tr>";
                            }
                        }
                    }
                    echo "</table>";
                }
            ?>
            
        </div>
    </body>
</html>
