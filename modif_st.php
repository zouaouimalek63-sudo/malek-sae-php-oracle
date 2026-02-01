<?php include("securite.inc.php");?>
<?php
include("connection.inc.php");

$message = "";

// Récupération des infos du sous traitant
$st_actuel = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idst'])) {
    $idst = $_POST['idst'];

    $stid = oci_parse($conn, "SELECT * FROM Sous_traitant WHERE ID_ST = :idst");
    oci_bind_by_name($stid, ":idst", $idst);
    oci_execute($stid);
    $st_actuel = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    if (!$st_actuel) {
        $message = "<div class='alert alert-danger text-center'>🎅 Aucun Sous traitant trouvé avec cet ID.</div>";
    }
}

// Gestion de la modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $idst = $_POST['idst'];
    $new_id = isset($_POST['new_id']) && !empty($_POST['new_id']) ? $_POST['new_id'] : $idst;
    $adresse = isset($_POST['adresse']) && !empty($_POST['adresse']) ? $_POST['adresse'] : $st_actuel['ADRESSE'];


    $update_stid = oci_parse($conn, "UPDATE Sous_traitant SET ID_ST = :new_id,ADRESSE=:adrresse WHERE ID_ST = :idst");
    oci_bind_by_name($update_stid, ":new_id", $new_id);
    oci_bind_by_name($update_stid, ":adresse", $adresse);

    if (oci_execute($update_stid)) {
        $message = "<div class='alert alert-success text-center'>🎄 Sous traitant modifié avec succès !</div>";
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
    <title>Modifier un Sous traitant 🎅</title>
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
    <h2 class="text-center">🎄 Modifier un Sous traitant 🎄</h2>
    <?php echo $message; ?>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ID Sous traitant (Obligatoire) 🎅</label>
            <input type="text" class="form-control" name="idst" required value="<?php echo isset($idst) ? htmlentities($idst) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-christmas w-100">🔍 Rechercher</button>
    </form>

    <?php if ($st_actuel): ?>
        <!-- Formulaire de modification -->
        <form method="POST" class="mt-4">
            <input type="hidden" name="idst" value="<?php echo htmlentities($st_actuel['ID_ST']); ?>">

            <div class="mb-3">
                <label class="form-label">ID Actuel : <?php echo htmlentities($st_actuel['ID_ST']); ?> 🎅</label>
                <button type="button" class="btn btn-warning btn-sm" id="btn-modifier-id">Modifier l'ID</button>
            </div>

            <div class="mb-3 hidden" id="champs-new-id">
                <label class="form-label">🎁 Nouveau ID</label>
                <input type="text" class="form-control" name="new_id">
            </div>

            <div class="mb-3">
                <label class="form-label">🎅 ADRESSE (Actuel : <?php echo htmlentities($st_actuel['ADRESSE']); ?>)</label>
                <input type="text" class="form-control" name="adresse" >
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

