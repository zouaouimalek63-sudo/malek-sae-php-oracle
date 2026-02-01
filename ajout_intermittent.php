 <?php
include ("connection.inc.php");
include("securite.inc.php");
$message = "";

// Gestion de l'ajout d'un elfe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ID_INTERMITTENT']) && isset($_POST['PRENOM']) && isset($_POST['NOM']) && isset($_POST['ADRESSE'])) {
    $ID_INTERMITTENT = $_POST['ID_INTERMITTENT'];
    $nom = $_POST['PRENOM'];
    $dirige=$_POST['NOM'];
    $equipe = $_POST['ADRESSE'];

    $insert_stid = oci_parse($conn, "INSERT INTO intermittent (ID_INTERMITTENT, PRENOM,NOM, ADRESSE) VALUES (:ID_INTERMITTENT, :PRENOM,:NOM, :ADRESSE)");
    oci_bind_by_name($insert_stid, ":ID_INTERMITTENT", $ID_INTERMITTENT);
    oci_bind_by_name($insert_stid, ":PRENOM", $PRENOM);
    oci_bind_by_name($insert_stid, ":NOM", $NOM);
    oci_bind_by_name($insert_stid, ":ADRESSE", $ADRESSE);

    if (oci_execute($insert_stid)) {
        $message = "<div class='alert alert-success text-center'>Elfe ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Erreur lors de l'ajout de l'elfe.</div>";
    }

    oci_free_statement($insert_stid);
}

// Récupération de la liste des elfes
$stid = oci_parse($conn, 'SELECT * FROM intermittent order by ID_INTERMITTENT asc');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des intermittents</title>
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
    <h2 class="text-center">Ajouter un intermittent</h2>
    <?php echo $message; ?>

    <form method="POST" action="ajout_intermittent.php">
        <div class="mb-3">
            <label class="form-label">ID_INTERMITTENT</label>
            <input type="text" class="form-control" name="ID_INTERMITTENT" required>
        </div>
        <div class="mb-3">
            <label class="form-label">PRENOM</label>
            <input type="text" class="form-control" name="PRENOM" required>
        </div>
        <div class="mb-3">
            <label class="form-label">NOM</label>
            <input type="text" class="form-control" name="NOM" required>
        </div>
        <div class="mb-3">
            <label class="form-label">ADRESSE</label>
            <input type="text" class="form-control" name="ADRESSE" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>

    <button class="btn btn-primary btn-show">Afficher la liste des intermittent</button>

    <div class="table-container">
        <h2 class="text-center mt-3">Liste des intermittent</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID_INTERMITTENT</th>
                    <th>PRENOM</th>
                    <th>NOM</th>
                    <th>ADRESSE</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr>
                        <td><?php echo htmlentities($row['ID_INTERMITTENT'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['PRENOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ADRESSE'], ENT_QUOTES); ?></td>
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
