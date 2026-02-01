<?php include("securite.inc.php"); ?>
<?php
include ("connection.inc.php");

if (isset($_POST['id_traineau'])) {
    $id_traineau = $_POST['id_traineau'];
    
    $query = oci_parse($conn, "SELECT * FROM Traineau WHERE ID_TRAINEAU = :id");
    oci_bind_by_name($query, ":id", $id_traineau);
    oci_execute($query);
    $traineau = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS);
    
    if ($traineau) {
        echo '<table class="table">';
        foreach ($traineau as $key => $value) {
            echo '<tr>';
            echo '<th>' . htmlentities($key, ENT_QUOTES) . '</th>';
            echo '<td>' . htmlentities($value, ENT_QUOTES) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Aucune information trouvée pour ce traîneau.</p>';
    }
    
    oci_free_statement($query);
    oci_close($conn);
} else {
    echo '<p>ID du traîneau non spécifié.</p>';
}
?>
