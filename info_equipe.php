<?php
include ("connection.inc.php");
include("securite.inc.php");
// Gestion de la suppression
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_stid = oci_parse($conn, "DELETE FROM equipe WHERE ID_EQUIPE = :id");
    oci_bind_by_name($delete_stid, ":id", $id);
    oci_execute($delete_stid);
    oci_free_statement($delete_stid);
    header("location:info_equipe.php");
    exit();
}

// Récupération de toutes les données (pour le filtrage JS)
$stid = oci_parse($conn, "SELECT * FROM equipe");
oci_execute($stid);
$all_equipes = [];
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $all_equipes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Equipes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .table-container {
            max-width: 800px;
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
    </style>
</head>
<body>
    <div class="table-container">
        <h2 class="text-center mb-4">Liste des Equipes</h2>
        
        <!-- Barre de recherche pour le filtrage en temps réel -->
        <div class="search-box">
            <div class="input-group">
                <input type="text" id="liveSearch" class="form-control" placeholder="Rechercher...">
            </div>
        </div>

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID_EQUIPE</th>
                    <th>NOM</th>
                    <th>ID_FLOTTE</th>
                    <th>ID_ENTREPOT</th>
                    <th>ID_ATELIER</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody id="equipeTableBody">
                <?php foreach ($all_equipes as $row): ?>
                    <tr id="row_<?php echo $row['ID_EQUIPE']; ?>">
                        <td><?php echo htmlentities($row['ID_EQUIPE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NOM'], ENT_QUOTES); ?></td>
                        <td>
                            <span class="clickable foreign-key" 
                                  data-type="flotte" 
                                  data-id="<?php echo htmlentities($row['ID_FLOTTE'], ENT_QUOTES); ?>">
                                <?php echo htmlentities($row['ID_FLOTTE'], ENT_QUOTES); ?>
                            </span>
                        </td>
                        <td>
                            <span class="clickable foreign-key" 
                                  data-type="entrepot" 
                                  data-id="<?php echo htmlentities($row['ID_ENTREPOT'], ENT_QUOTES); ?>">
                                <?php echo htmlentities($row['ID_ENTREPOT'], ENT_QUOTES); ?>
                            </span>
                        </td>
                        <td>
                            <span class="clickable foreign-key" 
                                  data-type="atelier" 
                                  data-id="<?php echo htmlentities($row['ID_ATELIER'], ENT_QUOTES); ?>">
                                <?php echo htmlentities($row['ID_ATELIER'], ENT_QUOTES); ?>
                            </span>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $row['ID_EQUIPE']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
            $("#equipeTableBody tr").filter(function() {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1
                );
            });
        });

        // Gestion des clics sur les clés étrangères
        $('.foreign-key').click(function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            
            $.ajax({
                url: 'get_foreign_details_equipe.php',
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
