<?php include("securite.inc.php"); ?>
<?php
include ("connection.inc.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stid = oci_parse($conn, "SELECT * FROM Equipe WHERE ID_EQUIPE = :id");
    oci_bind_by_name($stid, ":id", $id);
    oci_execute($stid);
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);

    if ($row) {
        echo '<table class="table table-bordered">';
        foreach ($row as $key => $value) {
            echo '<tr>';
            echo '<th>' . htmlentities($key, ENT_QUOTES) . '</th>';
            echo '<td>' . htmlentities($value, ENT_QUOTES) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<div class="alert alert-warning">Aucune information trouvée pour cette équipe</div>';
    }

    oci_free_statement($stid);
    oci_close($conn);
} else {
    echo '<div class="alert alert-danger">ID d\'équipe non spécifié</div>';
}
?>
