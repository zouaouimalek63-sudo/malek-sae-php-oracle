<?php include("securite.inc.php"); ?>
<?php
include ("connection.inc.php");

if (isset($_POST['id_enfant'])) {
    $id_enfant = $_POST['id_enfant'];
    
    $query = oci_parse($conn, "SELECT * FROM Enfants WHERE ID_ENFANTS = :id");
    oci_bind_by_name($query, ":id", $id_enfant);
    oci_execute($query);
    $enfant = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS);
    
    if ($enfant) {
        echo '<table class="table">';
        echo '<tr><th>ID_ENFANTS</th><td>' . htmlentities($enfant['ID_ENFANTS'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>NOM</th><td>' . htmlentities($enfant['NOM'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>PRENOM</th><td>' . htmlentities($enfant['PRENOM'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>ADRESSE</th><td>' . htmlentities($enfant['ADRESSE'], ENT_QUOTES) . '</td></tr>';
        echo '<tr><th>ID_TOURNEE</th><td>' . htmlentities($enfant['ID_TOURNEE'], ENT_QUOTES) . '</td></tr>';
        echo '</table>';
    } else {
        echo '<p>Aucune information trouvée pour cet enfant.</p>';
    }
    
    oci_free_statement($query);
    oci_close($conn);
} else {
    echo '<p>ID de l\'enfant non spécifié.</p>';
}
?>
