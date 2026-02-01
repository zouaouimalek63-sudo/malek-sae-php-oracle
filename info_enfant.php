<?php
include ("connection.inc.php");
include("securite.inc.php");
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_stid = oci_parse($conn, "DELETE FROM enfants WHERE ID_ENFANTS =:id");
    oci_bind_by_name($delete_stid, ":id", $id);
    oci_execute($delete_stid);
    oci_free_statement($delete_stid);
    oci_close($conn);
    header("location:info_enfant.php");
    exit();
}

$stid = oci_parse($conn, 'SELECT * FROM enfants');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Enfants</title>
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
        /* Style ajouté uniquement pour le champ de recherche */
        .search-box {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2 class="text-center mb-4">Liste des Enfants</h2>
        
        <!-- Début de l'ajout - Moteur de recherche -->
        <div class="search-box">
            <input type="text" id="liveSearch" class="form-control" placeholder="Rechercher un enfant...">
        </div>
        <!-- Fin de l'ajout -->

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID_ENFANTS</th>
                    <th>NOM</th>
                    <th>PRENOM</th>
                    <th>ADRESSE</th>
                    <th>ID_TOURNEE</th>
                    <th>OPTION</th>
                </tr>
            </thead>
            <tbody id="enfantsTableBody">
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr id="row_<?php echo $row['ID_ENFANTS']; ?>">
                        <td><?php echo htmlentities($row['ID_ENFANTS'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['PRENOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ADRESSE'], ENT_QUOTES); ?></td>
                        <td>
                            <?php if (!empty($row['ID_TOURNEE'])) : ?>
                                <span class="clickable tournee-detail" 
                                      data-id="<?php echo htmlentities($row['ID_TOURNEE'], ENT_QUOTES); ?>">
                                    <?php echo htmlentities($row['ID_TOURNEE'], ENT_QUOTES); ?>
                                </span>
                            <?php else : ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $row['ID_ENFANTS']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal pour afficher les détails de la tournée -->
    <div class="modal fade" id="tourneeModal" tabindex="-1" aria-labelledby="tourneeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tourneeModalLabel">Détails de la Tournée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="tourneeDetails">
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
        // Début de l'ajout - Filtrage en temps réel
        $("#liveSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#enfantsTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        // Fin de l'ajout

        // Lorsqu'on clique sur un ID de tournée
        $('.tournee-detail').click(function() {
            var idTournee = $(this).data('id');
            
            // Charger les détails via AJAX
            $.ajax({
                url: 'get_tournee_details.php',
                type: 'POST',
                data: { id_tournee: idTournee },
                success: function(response) {
                    $('#tourneeDetails').html(response);
                    var tourneeModal = new bootstrap.Modal(document.getElementById('tourneeModal'));
                    tourneeModal.show();
                },
                error: function() {
                    $('#tourneeDetails').html('<p>Erreur lors du chargement des détails.</p>');
                    var tourneeModal = new bootstrap.Modal(document.getElementById('tourneeModal'));
                    tourneeModal.show();
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
