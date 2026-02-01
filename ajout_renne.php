<?php
include ("connection.inc.php");
include("securite.inc.php");
$message = "";

// Gestion de l'ajout d'un renne
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['puce']) && isset($_POST['nom']) && isset($_POST['couleurnez']) && isset($_POST['positionre']) 
&& isset($_POST['capacite']) && isset($_POST['idtraineau'])) {

    $puce = $_POST['puce'];
    $nom = $_POST['nom'];
    $couleurnez=$_POST['couleurnez'];
    $positionre = $_POST['positionre'];
    $capacite = $_POST['capacite'];
    $idtraineau = $_POST['idtraineau'];

    $insert_stid = oci_parse($conn, "INSERT INTO Renne (PUCE, NOM,COULEUR_NEZ, POSITION_RE, CAPACITE, ID_TRAINEAU) VALUES (:puce, :nom,:couleurnez, :positionre, :capacite, :idtraineau)");
    oci_bind_by_name($insert_stid, ":puce", $puce);
    oci_bind_by_name($insert_stid, ":nom", $nom);
    oci_bind_by_name($insert_stid, ":couleurnez", $couleurnez);
    oci_bind_by_name($insert_stid, ":positionre", $positionre);
    oci_bind_by_name($insert_stid, ":capacite", $capacite);
    oci_bind_by_name($insert_stid, ":idtraineau", $idtraineau);
    if (oci_execute($insert_stid)) {
        $message = "<div class='alert alert-success text-center'>Renne ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Erreur lors de l'ajout du Renne.</div>";
    }

    oci_free_statement($insert_stid);
}

// Récupération de la liste des rennes
$stid = oci_parse($conn, 'SELECT * FROM Renne order by PUCE asc');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rennes</title>
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
    <h2 class="text-center">Ajouter un Renne</h2>
    <?php echo $message; ?>

    <form method="POST" action="ajout_renne.php">
        <div class="mb-3">
            <label class="form-label">Puce</label>
            <input type="text" class="form-control" name="puce" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="mb-3">
            <label class="form-label">couleurnez</label>
            <input type="text" class="form-control" name="couleurnez" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Position</label>
            <input type="text" class="form-control" name="positionre" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Capacité</label>
            <input type="text" class="form-control" name="capacite" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Traineau</label>
            <input type="text" class="form-control" name="idtraineau" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>

    <button class="btn btn-primary btn-show">Afficher la liste des Rennes</button>

    <div class="table-container">
        <h2 class="text-center mt-3">Liste des Rennes</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Puce</th>
                    <th>Nom</th>
                    <th>COULEUR NEZ</th>
                    <th>POSITION</th>
                    <th>CAPACITE</th>
                    <th>TRAINEAU</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr>
                        <td><?php echo htmlentities($row['PUCE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NOM'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['COULEUR_NEZ'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['POSITION_RE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['CAPACITE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['ID_TRAINEAU'], ENT_QUOTES); ?></td>
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
