<?php
include ("connection.inc.php");
include("securite.inc.php");
$message = "";

// Gestion de l'ajout d'une tournee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idtournee']) && isset($_POST['numtournee']) && isset($_POST['datetr']) && isset($_POST['destination']) 
&& isset($_POST['idtraineau'])) {

    $idtournee = $_POST['idtournee'];
    $numtournee = $_POST['numtournee'];
    $datetr=$_POST['datetr'];
    $destination = $_POST['destination'];
    $idtraineau = $_POST['idtraineau'];

    $insert_stid = oci_parse($conn, "INSERT INTO Tournee (ID_TOURNEE, NUM_TOURNEE,DATE_TR, DESTINATION, ID_TRAINEAU) VALUES (:idtournee, :numtournee,:datetr, :destination, :idtraineau)");
    oci_bind_by_name($insert_stid, ":idtournee", $idtournee);
    oci_bind_by_name($insert_stid, ":numtournee", $numtournee);
    oci_bind_by_name($insert_stid, ":datetr", $datetr);
    oci_bind_by_name($insert_stid, ":destination", $destination);
    oci_bind_by_name($insert_stid, ":idtraineau", $idtraineau);
    if (oci_execute($insert_stid)) {
        $message = "<div class='alert alert-success text-center'>Tournee ajouté avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Erreur lors de l'ajout du Tournee.</div>";
    }

    oci_free_statement($insert_stid);
}

// Récupération de la liste des tournee
$stid = oci_parse($conn, 'SELECT * FROM Tournee order by ID_TOURNEE asc');
oci_execute($stid);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tounées</title>
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
    <h2 class="text-center">Ajouter une Toournée</h2>
    <?php echo $message; ?>

    <form method="POST" action="ajout_tournee.php">
        <div class="mb-3">
            <label class="form-label">ID</label>
            <input type="text" class="form-control" name="idtournee" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Numero de tournée</label>
            <input type="text" class="form-control" name="numtournee" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date tournée</label>
            <input type="text" class="form-control" name="datetr" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Destination</label>
            <input type="text" class="form-control" name="destination" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Traineau</label>
            <input type="text" class="form-control" name="idtraineau" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Valider</button>
    </form>

    <button class="btn btn-primary btn-show">Afficher la liste des tournées</button>

    <div class="table-container">
        <h2 class="text-center mt-3">Liste des Tournées</h2>
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Destination</th>
                    <th>TRAINEAU</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) : ?>
                    <tr>
                        <td><?php echo htmlentities($row['ID_TOURNEE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['NUM_TOURNEE'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['DATE_TR'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlentities($row['DESTINATION'], ENT_QUOTES); ?></td>
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
