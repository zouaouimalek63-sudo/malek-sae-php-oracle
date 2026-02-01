<?php include("securite.inc.php"); ?>
<?php
include ("connection.inc.php");

if (isset($_POST['id_tournee'])) {
    $id_tournee = $_POST['id_tournee'];
    
    $query = oci_parse($conn, "SELECT * FROM Tournee WHERE ID_TOURNEE = :id");
    oci_bind_by_name($query, ":id", $id_tournee);
    oci_execute($query);
    $tournee = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS);
    
    if ($tournee) {
        echo '<table class="table">';
        echo '<tr><th>ID_TOURNEE</th><td>' . htmlentities($tournee['ID_TOURNEE'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>NUM_TOURNEE</th><td>' . htmlentities($tournee['NUM_TOURNEE'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>DATE_TR</th><td>' . htmlentities($tournee['DATE_TR'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>DESTINATION</th><td>' . htmlentities($tournee['DESTINATION'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>ID_TRAINEAU</th><td>' . htmlentities($tournee['ID_TRAINEAU'], ENT_QUOTES) . '</td></tr>';
        echo '</table>';
    } else {
        echo '<p>Aucune information trouvée pour cette tournée.</p>';
    }
    
    oci_free_statement($query);
    oci_close($conn);
} else {
    echo '<p>ID de la tournée non spécifié.</p>';
}
?>
