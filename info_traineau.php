<?php
include ("connection.inc.php");
include("securite.inc.php");
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_stid = oci_parse($conn, "DELETE FROM Traineau WHERE ID_TRAINEAU = :id");
    oci_bind_by_name($delete_stid, ":id", $id);
    oci_execute($delete_stid);
    oci_free_statement($delete_stid);
    oci_close($conn);
    header("location:info_traineau.php");
    exit();
}

// Récupération de toutes les données (pour le filtrage JS)
$stid = oci_parse($conn, 'SELECT * FROM Traineau');
oci_execute($stid);
$all_traineaux = [];
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    $all_traineaux[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Traineaux</title>
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
        <h2 class="text-center mb-4">Liste des Traineaux</h2>
        
        <!-- Barre de recherche pour le filtrage en temps réel -->
        <div class="search-box">
            <div class="input-group">
                <input type="text" id="liveSearch" class="form-control" placeholder="Rechercher un traîneau...">
            </div>
        </div>

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Capacite</th>
                    <th>Flotte</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody id="traineauxTableBody">
                <?php foreach ($all_traineaux as $row): ?>
                    <tr id="row_<?php echo $row['ID_TRAINEAU']; ?>">
                        <td><?php echo htmlentities($row['ID_TRAINEAU'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['CAPAC_TR'], ENT_QUOTES); ?></td>
                        <td>
                            <span class="clickable flotte-detail" 
                                  data-id="<?php echo htmlentities($row['ID_FLOTTE'], ENT_QUOTES); ?>">
                                <?php echo htmlentities($row['ID_FLOTTE'], ENT_QUOTES); ?>
                            </span>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $row['ID_TRAINEAU']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour afficher les détails de la flotte -->
    <div class="modal fade" id="flotteModal" tabindex="-1" aria-labelledby="flotteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flotteModalLabel">Détails de la Flotte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="flotteDetails">
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
            $("#traineauxTableBody tr").filter(function() {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1
                );
            });
        });

        // Lorsqu'on clique sur un ID de flotte
        $('.flotte-detail').click(function() {
            var idFlotte = $(this).data('id');
            
            // Charger les détails via AJAX
            $.ajax({
                url: 'get_flotte_details.php',
                type: 'POST',
                data: { id_flotte: idFlotte },
                success: function(response) {
                    $('#flotteDetails').html(response);
                    var flotteModal = new bootstrap.Modal(document.getElementById('flotteModal'));
                    flotteModal.show();
                },
                error: function() {
                    $('#flotteDetails').html('<p>Erreur lors du chargement des détails.</p>');
                    var flotteModal = new bootstrap.Modal(document.getElementById('flotteModal'));
                    flotteModal.show();
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
