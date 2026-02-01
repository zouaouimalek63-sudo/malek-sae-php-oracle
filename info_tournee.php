<?php
include ("connection.inc.php");
include("securite.inc.php");
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_stid = oci_parse($conn, "DELETE FROM Tournee WHERE ID_tournee = :id");
    oci_bind_by_name($delete_stid, ":id", $id);
    oci_execute($delete_stid);
    oci_free_statement($delete_stid);
    oci_close($conn);
    header("location:info_tournee.php");
    exit();
}

// Requête pour récupérer les tournées
$stid = oci_parse($conn, 'SELECT * FROM Tournee');
oci_execute($stid);

// Fonction pour récupérer les détails d'un traîneau
function getTraineauDetails($conn, $id_traineau) {
    $query = oci_parse($conn, "SELECT * FROM Traineau WHERE ID_TRAINEAU = :id");
    oci_bind_by_name($query, ":id", $id_traineau);
    oci_execute($query);
    $details = oci_fetch_array($query, OCI_ASSOC+OCI_RETURN_NULLS);
    oci_free_statement($query);
    return $details;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des tournees</title>
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
    </style>
</head>
<body>
    <div class="table-container">
        <h2 class="text-center mb-4">Liste des Tournées</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Numero</th>
                    <th>DATE</th>
                    <th>Destination</th>
                    <th>Traineaux</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr id="row_<?php echo $row['ID_TOURNEE']; ?>">
                        <td><?php echo htmlentities($row['ID_TOURNEE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NUM_TOURNEE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['DATE_TR'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['DESTINATION'], ENT_QUOTES); ?></td>
                        <td>
                            <span class="clickable traineau-detail" 
                                  data-id="<?php echo htmlentities($row['ID_TRAINEAU'], ENT_QUOTES); ?>">
                                <?php echo htmlentities($row['ID_TRAINEAU'], ENT_QUOTES); ?>
                            </span>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $row['ID_TOURNEE']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour afficher les détails du traîneau -->
    <div class="modal fade" id="traineauModal" tabindex="-1" aria-labelledby="traineauModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="traineauModalLabel">Détails du Traîneau</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="traineauDetails">
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
        // Lorsqu'on clique sur un ID de traîneau
        $('.traineau-detail').click(function() {
            var idTraineau = $(this).data('id');
            
            // Charger les détails via AJAX
            $.ajax({
                url: 'get_traineau_details.php',
                type: 'POST',
                data: { id_traineau: idTraineau },
                success: function(response) {
                    $('#traineauDetails').html(response);
                    var traineauModal = new bootstrap.Modal(document.getElementById('traineauModal'));
                    traineauModal.show();
                },
                error: function() {
                    $('#traineauDetails').html('<p>Erreur lors du chargement des détails.</p>');
                    var traineauModal = new bootstrap.Modal(document.getElementById('traineauModal'));
                    traineauModal.show();
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
