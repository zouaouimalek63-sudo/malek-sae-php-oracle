<?php include("securite.inc.php");?>
<?php
include("connection.inc.php");

$message = "";

// Récupération des infos de l'elfe
$req_actuel = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stid = oci_parse($conn, "SELECT * FROM TOURNEE  WHERE Id_tournee = :id");
    oci_bind_by_name($stid, ":id", $id);
    oci_execute($stid);
    $req_actuel = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    if (!$req_actuel) {
        $message = "<div class='alert alert-danger text-center'>🎅 Aucun elfe trouvé avec cet ID.</div>";
    }
}

// Gestion de la modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $new_id = isset($_POST['Id_tournee']) && !empty($_POST['Id_tournee']) ? $_POST['Id_tournee'] : $id;
    $date_tr= isset($_POST['date_tr']) && !empty($_POST['date_tr']) ?$_POST['date_tr'] : $req_actuel['DATE_TR'];
    $Num_tournee = isset($_POST['Num_tournee']) && !empty($_POST['Num_tournee']) ? $_POST['Num_tournee'] : $req_actuel['NUM_TOURNEE'];
    $destination = isset($_POST['destination']) && !empty($_POST['destination']) ? $_POST['destination'] : $req_actuel['DESTINATION'];
    $ID_traineau = isset($_POST['ID_traineau']) && !empty($_POST['ID_traineau']) ? $_POST['ID_traineau'] : $req_actuel['ID_TRAINEAU'];

    $update_stid = oci_parse($conn, "UPDATE TOURNEE SET ID_TOURNEE = :new_id,DATE_TR=:date_tr ,NUM_TOURNEE = :Num_tournee, DESTINATION=:destination ,ID_TRAINEAU =:ID_traineau WHERE ID_TOURNEE = :id");
    oci_bind_by_name($update_stid, ":new_id", $new_id);
    oci_bind_by_name($update_stid, ":date_tr", $date_tr);
    oci_bind_by_name($update_stid, ":Num_tournee", $Num_tournee);
    oci_bind_by_name($update_stid, ":destination", $destination);
    oci_bind_by_name($update_stid, ":ID_traineau", $ID_traineau);    
    oci_bind_by_name($update_stid, ":id", $id);

    if (oci_execute($update_stid)) {
        $message = "<div class='alert alert-success text-center'>🎄 Elfe modifié avec succès !</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Erreur lors de la modification.</div>";
    }

    oci_free_statement($update_stid);
}

oci_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Elfe 🎅</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: url('noel.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 40px;
            color: #fff;
        }
        .container {
            max-width: 600px;
            background: rgba(255, 0, 0, 0.85); /* Fond rouge transparent */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            border: 3px solid gold;
        }
        h2 {
            color: gold;
            font-family: 'Comic Sans MS', cursive;
        }
        .form-label {
            color: white;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-warning {
            background-color: gold;
            border: none;
            color: black;
        }
        .hidden {
            display: none;
        }
        .btn-christmas {
            background-color: #228B22; /* Vert sapin */
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">🎄 Modifier un Elfe 🎄</h2>
    <?php echo $message; ?>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ID tournee (Obligatoire) 🎅</label>
            <input type="text" class="form-control" name="id" required value="<?php echo isset($id) ? htmlentities($id) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-christmas w-100">🔍 Rechercher</button>
    </form>

    <?php if ($req_actuel): ?>
        <!-- Formulaire de modification -->
        <form method="POST" class="mt-4">
            <input type="hidden" name="id" value="<?php echo htmlentities($req_actuel['ID_TOURNEE']); ?>">

            <div class="mb-3">
                <label class="form-label">ID_TOURNEE : <?php echo htmlentities($req_actuel['ID_TOURNEE']); ?> 🎅</label>
                <button type="button" class="btn btn-warning btn-sm" id="btn-modifier-id">Modifier l'ID</button>
            </div>

            <div class="mb-3 hidden" id="champs-new-id">
                <label class="form-label">🎁 Nouveau ID</label>
                <input type="text" class="form-control" name="Id_tournee">
            </div>

            <div class="mb-3">
                <label class="form-label">🎅 NUM_TOURNEE (Actuel : <?php echo htmlentities($req_actuel['NUM_TOURNEE']); ?>)</label>
                <input type="text" class="form-control" name="Num_tournee" >
            </div>

            <div class="mb-3">
                <label class="form-label">🎄 DATE_TR (Actuel : <?php echo htmlentities($req_actuel['DATE_TR']); ?>)</label>
                <input type="text" class="form-control" name="date_tr" placeholder="Laisser vide pour conserver l'ancien">
            </div>

            <div class="mb-3">
                <label class="form-label">🎄 DESTINATION (Actuel : <?php echo htmlentities($req_actuel['DESTINATION']); ?>)</label>
                <input type="text" class="form-control" name="destination" placeholder="Laisser vide pour conserver l'ancien">
            </div>

            <div class="mb-3">
                <label class="form-label">🎄 ID_TRAINEAU (Actuel : <?php echo htmlentities($req_actuel['ID_TRAINEAU']); ?>)</label>
                <input type="text" class="form-control" name="ID_traineau" placeholder="Laisser vide pour conserver l'ancien">
            </div>

            <button type="submit" name="modifier" class="btn btn-success w-100">🎄 Modifier</button>
        </form>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function () {
        $("#btn-modifier-id").click(function () {
            $("#champs-new-id").slideToggle();
        });
    });
</script>

</body>
</html>

