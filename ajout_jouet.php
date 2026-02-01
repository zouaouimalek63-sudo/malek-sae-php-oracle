 <?php
include ("connection.inc.php");
include("securite.inc.php");
$message = "";

// Gestion de l'ajout d'une JOUET
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idjouet']) && isset($_POST['typej'])) {
    $idjouet = $_POST['idjouet'];
    $typej = $_POST['typej'];
    $insert_stid = oci_parse($conn, "INSERT INTO Jouet (ID_JOUET, TYPE_J) VALUES (:idjouet, :typej)");
    oci_bind_by_name($insert_stid, ":idjouet", $idjouet);
    oci_bind_by_name($insert_stid, ":typej", $typej);

    if (oci_execute($insert_stid)) {
        $message = "<div class='alert alert-success text-center'>Jouet ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Erreur lors de l'ajout du Jouet.</div>";
    }

    oci_free_statement($insert_stid);
}

// Récupération de la liste des JOUET
$stid = oci_parse($conn, 'SELECT * FROM Jouet order by ID_JOUET asc');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des ATELIERS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: url('noel.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .btn-show {
            display: block;
            width: 100%;
            margin-top: 20px;
        }
        .table-container {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Ajouter un Jouet</h2>
    <?php echo $message; ?>

    <form method="POST" action="ajout_jouet.php">
        <div class="mb-3">
            <label class="form-label">ID Jouet</label>
            <input type="text" class="form-control" name="idjouet" required>
        </div>
        <div class="mb-3">
            <label class="form-label">TYPE</label>
            <input type="text" class="form-control" name="typej" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>

    <button class="btn btn-primary btn-show">Afficher la liste des Jouets</button>

    <div class="table-container">
        <h2 class="text-center mt-3">Liste des Jouets</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID Jouet</th>
                    <th>Type</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr>
                        <td><?php echo htmlentities($row['ID_JOUET'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['TYPE_J'], ENT_QUOTES); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $(".btn-show").click(function () {
            $(".table-container").slideToggle();
        });
    });
</script>

</body>
</html>

<?php
oci_free_statement($stid);
oci_close($conn);
?>
