<?php
include ("connection.inc.php");
include("securite.inc.php");
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $delete_stid = oci_parse($conn, "DELETE FROM intermittent WHERE ID_INTERMITTENT = :id");
    oci_bind_by_name($delete_stid, ":id", $id);
    oci_execute($delete_stid);
    oci_free_statement($delete_stid);
    oci_close($conn);
    header("location:info_intermittent.php");
    exit();
}

$stid = oci_parse($conn, 'SELECT * FROM intermittent');
oci_execute($stid);
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
    </style>
</head>
<body>
    <div class="table-container">
        <h2 class="text-center mb-4">Liste des Traineaux</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID_INTERMITTENT</th>
                    <th>PRENOM</th>
                    <th>NOM</th>
                    <th>ADRESSE</th>
                    <th>Option</th>

                </tr>
            </thead>
            <tbody>

                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr id="row_<?php echo $row['ID_INTERMITTENT']; ?>">
                        <td><?php echo htmlentities($row['ID_INTERMITTENT'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['PRENOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ADRESSE'], ENT_QUOTES); ?></td>
                        <td>
                        <form method="post">
                                <input type="hidden" name="delete_id" value="<?php echo $row['ID_INTERMITTENT']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
oci_free_statement($stid);
oci_close($conn);
?>
