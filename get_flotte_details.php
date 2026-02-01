<?php include("securite.inc.php"); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include ("connection.inc.php");
if (isset($_POST['id_flotte'])) {
    $id_flotte = $_POST['id_flotte'];
    
    $query = oci_parse($conn, "SELECT * FROM Flotte WHERE ID_FLOTTE = :id");
    oci_bind_by_name($query, ":id", $id_flotte);
    oci_execute($query);
    $flotte = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS);
    
    if ($flotte) {
        echo '<table class="table">';
        echo '<tr><th>ID_FLOTTE</th><td>' . htmlentities($flotte['ID_FLOTTE'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>NOM</th><td>' . htmlentities($flotte['NOM'], ENT_QUOTES) . '</td></tr>';
        echo '</table>';
    } else {
        echo '<p>Aucune information trouvée pour cette flotte.</p>';
    }
    
    oci_free_statement($query);
    oci_close($conn);
} else {
    echo '<p>ID de la flotte non spécifié.</p>';
}
?>
