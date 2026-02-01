<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include ("connection.inc.php");

// Récupérer le nom de la table depuis l'URL
$table_name = isset($_GET['table']) ? strtoupper($_GET['table']) : 'EQUIPE';

// Gestion de la suppression
if (isset($_POST['delete_id']) && isset($_POST['table_name'])) {
    $id = $_POST['delete_id'];
    $table = $_POST['table_name'];
    
    // Construire la requête DELETE dynamiquement
    $pk_column = get_primary_key($conn, $table);
    $delete_stid = oci_parse($conn, "DELETE FROM $table WHERE $pk_column = :id");
    oci_bind_by_name($delete_stid, ":id", $id);
    oci_execute($delete_stid);
    oci_free_statement($delete_stid);
    
    header("location:info_table.php?table=$table");
    exit();
}

// Fonction pour obtenir la clé primaire d'une table
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

// Récupérer les colonnes de la table
$columns_query = oci_parse($conn, "SELECT * FROM $table_name WHERE ROWNUM = 1");
oci_execute($columns_query);
$num_columns = oci_num_fields($columns_query);
$column_names = [];
for ($i = 1; $i <= $num_columns; $i++) {
    $column_names[] = oci_field_name($columns_query, $i);
}
oci_free_statement($columns_query);

// Récupération de toutes les données
$stid = oci_parse($conn, "SELECT * FROM $table_name");
oci_execute($stid);
$all_rows = [];
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $all_rows[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des <?php echo $table_name; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .table-container {
            max-width: 95%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .clickable {
            cursor: pointer;
            color: #0d6efd;
            text-decoration: underline;
        }
        .clickable:hover {
            color: #0a58ca;
        }
        .search-box {
            margin-bottom: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2 class="text-center mb-4">Liste des <?php echo $table_name; ?></h2>
        
        <!-- Sélecteur de table -->
        <div class="mb-3">
            <label for="tableSelector" class="form-label">Sélectionner une table:</label>
            <select class="form-select" id="tableSelector" onchange="window.location.href='info_table.php?table='+this.value">
                <?php
                // Récupérer toutes les tables de l'utilisateur
                $tables_query = oci_parse($conn, "SELECT table_name FROM user_tables");
                oci_execute($tables_query);
                
                // Liste des tables à exclure
                $excluded_tables = [
                    'FLOTTE', 'ELFES', 'EQUIPE', 'ENTREPOT', 'JOUET', 'ATELIER', 
                    'MATIERES_PREMIERES', 'ENFANTS', 'RENNE', 'TOURNEE', 
                    'INTERMITTENT', 'TRAINEAU', 'LUTINS', 'MDP', 'SUBSTITUT'
                ];
                
                // Liste des tables autorisées (uniques)
                $allowed_tables = [];
                
                while ($table_row = oci_fetch_array($tables_query, OCI_ASSOC)) {
                    $current_table = $table_row['TABLE_NAME'];
                    
                    // Vérifier si la table doit être exclue
                    if (in_array($current_table, $excluded_tables)) {
                        continue;
                    }
                    
                    // Vérifier les doublons (par exemple EQUIPE et EQUIPES)
                    $normalized_name = preg_replace('/S$/', '', $current_table);
                    if (in_array($normalized_name, $allowed_tables)) {
                        continue;
                    }
                    
                    $allowed_tables[] = $normalized_name;
                    $selected = ($current_table == $table_name) ? 'selected' : '';
                    echo "<option value='$current_table' $selected>$current_table</option>";
                }
                oci_free_statement($tables_query);
                ?>
            </select>
        </div>
        
        <!-- Barre de recherche pour le filtrage en temps réel -->
        <div class="search-box">
            <div class="input-group">
                <input type="text" id="liveSearch" class="form-control" placeholder="Rechercher...">
                <span class="input-group-text">
                    <?php echo count($all_rows); ?> enregistrements
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <?php foreach ($column_names as $col_name): ?>
                            <th><?php echo $col_name; ?></th>
                        <?php endforeach; ?>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    <?php foreach ($all_rows as $row): ?>
                        <tr id="row_<?php echo $row[get_primary_key($conn, $table_name)]; ?>">
                            <?php foreach ($column_names as $col_name): ?>
                                <td>
                                    <?php 
                                    $value = $row[$col_name] ?? '';
                                    // Si la colonne est une clé étrangère (suppose que les FK ont un format ID_TABLE)
                                    if (preg_match('/^ID_/', $col_name)) {
                                        echo '<span class="clickable foreign-key" 
                                                  data-type="' . strtolower(substr($col_name, 3)) . '" 
                                                  data-id="' . htmlentities($value, ENT_QUOTES) . '">' . 
                                                  htmlentities($value, ENT_QUOTES) . '</span>';
                                    } else {
                                        echo htmlentities($value, ENT_QUOTES);
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="delete_id" value="<?php echo $row[get_primary_key($conn, $table_name)]; ?>">
                                    <input type="hidden" name="table_name" value="<?php echo $table_name; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal pour afficher les détails -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Détails</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailsContent">
                    <!-- Les détails seront chargés ici via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Filtrage en temps réel
        $("#liveSearch").on("keyup", function() {
            const value = $(this).val().toLowerCase();
            $("#dataTableBody tr").filter(function() {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1
                );
            });
        });

        // Gestion des clics sur les clés étrangères
        $(document).on('click', '.foreign-key', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            
            $.ajax({
                url: 'get_foreign_details.php',
                type: 'POST',
                data: { 
                    id: id,
                    type: type 
                },
                success: function(response) {
                    $('#detailsContent').html(response);
                    $('#detailsModalLabel').text('Détails ' + type.charAt(0).toUpperCase() + type.slice(1));
                    var detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                    detailsModal.show();
                },
                error: function() {
                    $('#detailsContent').html('<div class="alert alert-danger">Impossible de charger les détails.</div>');
                    var detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                    detailsModal.show();
                }
            });
        });
    });
    </script>
</body>
</html>

<?php
oci_free_statement($stid);
oci_close($conn);
?>
