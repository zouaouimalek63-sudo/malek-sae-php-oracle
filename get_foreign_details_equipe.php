<?php include("securite.inc.php"); ?>
<?php
include ("connection.inc.php");

if (isset($_POST['id']) && isset($_POST['type'])) {
    $id = $_POST['id'];
    $type = $_POST['type'];
    
    $query = '';
    $columns = [];
    
    // Définir la requête et les colonnes en fonction du type
    switch ($type) {
        case 'flotte':
            $query = "SELECT * FROM flotte WHERE ID_FLOTTE = :id";
            $columns = ['ID_FLOTTE', 'NOM'];
            break;
        case 'entrepot':
            $query = "SELECT * FROM entrepot WHERE ID_ENTREPOT = :id";
            $columns = ['ID_ENTREPOT', 'NOM', 'REGION'];
            break;
        case 'atelier':
            $query = "SELECT * FROM atelier WHERE ID_ATELIER = :id";
            $columns = ['ID_ATELIER', 'NOM_A', 'ID_SPECIALITE'];
            break;
        default:
            echo '<p>Type non reconnu.</p>';
            exit();
    }
    
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ":id", $id);
    oci_execute($stmt);
    $data = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS);
    
    if ($data) {
        echo '<table class="table">';
        foreach ($columns as $column) {
            echo '<tr>';
            echo '<th>' . htmlentities($column, ENT_QUOTES) . '</th>';
            echo '<td>' . htmlentities($data[$column] ?? 'N/A', ENT_QUOTES) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Aucune information trouvée pour cet ID.</p>';
    }
    
    oci_free_statement($stmt);
    oci_close($conn);
} else {
    echo '<p>Paramètres manquants.</p>';
}
?>
