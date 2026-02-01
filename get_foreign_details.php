<?php
include ("connection.inc.php");

if (isset($_POST['id']) && isset($_POST['type'])) {
    $id = $_POST['id'];
    $type = strtoupper($_POST['type']);
    
    // Déterminer le nom de la table et la colonne ID
    $table_name = $type;
    if (!preg_match('/^ID_/', $table_name)) {
        $table_name = $type;
    }
    
    // Récupérer la clé primaire de la table
    $pk_column = get_primary_key($conn, $table_name);
    
    // Récupérer les données
    $query = "SELECT * FROM $table_name WHERE $pk_column = :id";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":id", $id);
    oci_execute($stid);
    
    $row = oci_fetch_array($stid, OCI_ASSOC);
    
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
        echo '<div class="alert alert-warning">Aucun détail trouvé pour cet ID.</div>';
    }
    
    oci_free_statement($stid);
    oci_close($conn);
    exit();
}

function get_primary_key($conn, $table) {
    $query = "SELECT cols.column_name
              FROM all_constraints cons, all_cons_columns cols
              WHERE cons.constraint_type = 'P'
              AND cons.constraint_name = cols.constraint_name
              AND cons.owner = cols.owner
              AND cols.table_name = :table_name";
    
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ":table_name", $table);
    oci_execute($stid);
    
    $row = oci_fetch_array($stid, OCI_ASSOC);
    oci_free_statement($stid);
    
    return $row['COLUMN_NAME'] ?? 'ID';
}
?>
