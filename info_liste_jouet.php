<?php
include ("connection.inc.php");
include("securite.inc.php");
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_stid = oci_parse($conn, "DELETE FROM liste_jouet WHERE ID_LISTE = :id");
    oci_bind_by_name($delete_stid, ":id", $id);
    oci_execute($delete_stid);
    oci_free_statement($delete_stid);
    oci_close($conn);
    header("location:info_liste_jouet.php");
    exit();
}

$stid = oci_parse($conn, 'SELECT * FROM liste_jouet order by ID_liste asc');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Liste de Jouets</title>
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
        <h2 class="text-center mb-4">Liste des Listes de Jouets</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Poids</th>
                    <th>Traineau</th>
                    <th>Enfant</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr id="row_<?php echo $row['ID_LISTE']; ?>">
                        <td><?php echo htmlentities($row['ID_LISTE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['POIDS'], ENT_QUOTES); ?></td>
                        <td>
                            <?php if (!empty($row['ID_TRAINEAU'])) : ?>
                                <span class="clickable traineau-detail" 
                                      data-id="<?php echo htmlentities($row['ID_TRAINEAU'], ENT_QUOTES); ?>">
                                    <?php echo htmlentities($row['ID_TRAINEAU'], ENT_QUOTES); ?>
                                </span>
                            <?php else : ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row['ID_ENFANTS'])) : ?>
                                <span class="clickable enfant-detail" 
                                      data-id="<?php echo htmlentities($row['ID_ENFANTS'], ENT_QUOTES); ?>">
                                    <?php echo htmlentities($row['ID_ENFANTS'], ENT_QUOTES); ?>
                                </span>
                            <?php else : ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $row['ID_LISTE']; ?>">
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

    <!-- Modal pour afficher les détails de l'enfant -->
    <div class="modal fade" id="enfantModal" tabindex="-1" aria-labelledby="enfantModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enfantModalLabel">Détails de l'Enfant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="enfantDetails">
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

        // Lorsqu'on clique sur un ID d'enfant
        $('.enfant-detail').click(function() {
            var idEnfant = $(this).data('id');
            
            $.ajax({
                url: 'get_enfant_details.php',
                type: 'POST',
                data: { id_enfant: idEnfant },
                success: function(response) {
                    $('#enfantDetails').html(response);
                    var enfantModal = new bootstrap.Modal(document.getElementById('enfantModal'));
                    enfantModal.show();
                },
                error: function() {
                    $('#enfantDetails').html('<p>Erreur lors du chargement des détails.</p>');
                    var enfantModal = new bootstrap.Modal(document.getElementById('enfantModal'));
                    enfantModal.show();
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
