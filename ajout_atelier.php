<?php
include ("connection.inc.php");
include("securite.inc.php");
$message = "";

// Gestion de l'ajout d'un atelier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idatelier']) && isset($_POST['noma']) && isset($_POST['idspecialite'])) {
    $idatelier = $_POST['idatelier'];
    $noma = $_POST['noma'];
    $idspecialite=$_POST['idspecialite'];
    $insert_stid = oci_parse($conn, "INSERT INTO Atelier (ID_ATELIER, NOM_A, ID_SPECIALITE) VALUES (:idatelier, :noma, :idspecialite)");
    oci_bind_by_name($insert_stid, ":idspecialite", $idspecialite);
    oci_bind_by_name($insert_stid, ":noma", $noma);
    oci_bind_by_name($insert_stid, ":idspecialite", $idspecialite);

    if (oci_execute($insert_stid)) {
        $message = "<div class='alert alert-success text-center'>atelier ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Erreur lors de l'ajout de l'atelier.</div>";
    }

    oci_free_statement($insert_stid);
}

// Récupération de la liste des ateliers
$stid = oci_parse($conn, 'SELECT * FROM atelier order by ID_ATELIER asc');
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
    <h2 class="text-center">Ajouter un ATELIER</h2>
    <?php echo $message; ?>

    <form method="POST" action="ajout_atelier.php">
        <div class="mb-3">
            <label class="form-label">ID Atelier</label>
            <input type="text" class="form-control" name="idatelier" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="noma" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Specialité</label>
            <input type="text" class="form-control" name="idspecialite" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>

    <button class="btn btn-primary btn-show">Afficher la liste des ATELIERS</button>

    <div class="table-container">
        <h2 class="text-center mt-3">Liste des ATELIER</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Specialité</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr>
                        <td><?php echo htmlentities($row['ID_ATELIER'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NOM_A'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ID_SPECIALITE'], ENT_QUOTES); ?></td>
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
