 <?php
include ("connection.inc.php");
include("securite.inc.php");
$message = "";

// Gestion de l'ajout d'un elfe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idelfe']) && isset($_POST['nom']) && isset($_POST['equipe']) && isset($_POST['dirige'])) {
    $idelfe = $_POST['idelfe'];
    $nom = $_POST['nom'];
    $dirige=$_POST['dirige'];
    $equipe = $_POST['equipe'];

    $insert_stid = oci_parse($conn, "INSERT INTO Elfes (ID_ELFE, NOM,DIRIGE, ID_EQUIPE) VALUES (:idelfe, :nom,:dirige, :equipe)");
    oci_bind_by_name($insert_stid, ":idelfe", $idelfe);
    oci_bind_by_name($insert_stid, ":nom", $nom);
    oci_bind_by_name($insert_stid, ":dirige", $dirige);
    oci_bind_by_name($insert_stid, ":equipe", $equipe);

    if (oci_execute($insert_stid)) {
        $message = "<div class='alert alert-success text-center'>Elfe ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Erreur lors de l'ajout de l'elfe.</div>";
    }

    oci_free_statement($insert_stid);
}

// Récupération de la liste des elfes
$stid = oci_parse($conn, 'SELECT * FROM Elfes order by ID_ELFE asc');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Elfes</title>
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
    <h2 class="text-center">Ajouter un Elfe</h2>
    <?php echo $message; ?>

    <form method="POST" action="ajout_elfe.php">
        <div class="mb-3">
            <label class="form-label">ID Elfe</label>
            <input type="text" class="form-control" name="idelfe" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dirige</label>
            <input type="text" class="form-control" name="dirige" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Équipe</label>
            <input type="text" class="form-control" name="equipe" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>

    <button class="btn btn-primary btn-show">Afficher la liste des Elfes</button>

    <div class="table-container">
        <h2 class="text-center mt-3">Liste des Elfes</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID_ELFE</th>
                    <th>Nom</th>
                    <th>DIRIGE</th>
                    <th>ID_EQUIPE</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr>
                        <td><?php echo htmlentities($row['ID_ELFE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['DIRIGE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ID_EQUIPE'], ENT_QUOTES); ?></td>
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
