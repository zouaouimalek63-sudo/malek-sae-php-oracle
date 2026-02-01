 <?php
include ("connection.inc.php");
include("securite.inc.php");
$message = "";

// Gestion de l'ajout d'une mp
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idliste']) && isset($_POST['poids']) && isset($_POST['idtraineau']) && isset($_POST['idenfant'])) {
    $idliste = $_POST['idliste'];
    $poids = $_POST['poids'];
    $idtraineau=$_POST['idtraineau'];
    $idenfant=$_POST['idenfant'];
    $insert_stid = oci_parse($conn, "INSERT INTO LISTE_JOUET (ID_LISTE, POIDS, ID_TRAINEAU, ID_ENFANTS) VALUES (:ID_LISTE, :poids, :idtraineau, :ID_ENFANTS)");
    oci_bind_by_name($insert_stid, ":ID_LISTE", $idliste);
    oci_bind_by_name($insert_stid, ":poids", $poids);
    oci_bind_by_name($insert_stid, ":idtraineau", $idtraineau);
    oci_bind_by_name($insert_stid, ":ID_ENFANTS", $idenfant);
    if (oci_execute($insert_stid)) {
        $message = "<div class='alert alert-success text-center'>Liste ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Erreur lors de l'ajout de la Liste.</div>";
    }

    oci_free_statement($insert_stid);
}

// Récupération de la liste des liste
$stid = oci_parse($conn, 'SELECT * FROM liste_jouet order by ID_LISTE asc');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des LISTES DE JOUETS</title>
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
    <h2 class="text-center">Ajouter une lISTE DE JOUETS</h2>
    <?php echo $message; ?>

    <form method="POST" action="ajout_liste_jouet.php">
        <div class="mb-3">
            <label class="form-label">ID LISTE</label>
            <input type="text" class="form-control" name="idliste" required>
        </div>
        <div class="mb-3">
            <label class="form-label">poids</label>
            <input type="text" class="form-control" name="poids" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Traineau</label>
            <input type="text" class="form-control" name="idtraineau" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Enfant</label>
            <input type="text" class="form-control" name="idenfant" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>

    <button class="btn btn-primary btn-show">Afficher la liste des Listes de jouets</button>

    <div class="table-container">
        <h2 class="text-center mt-3">Listes de jouets</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>poids</th>
                    <th>traineau</th>
                    <th>Enfant</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr>
                        <td><?php echo htmlentities($row['ID_LISTE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['POIDS'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ID_TRAINEAU'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ID_ENFANTS'], ENT_QUOTES); ?></td>
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
