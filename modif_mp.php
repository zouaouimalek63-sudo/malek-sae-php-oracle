<?php include("securite.inc.php");?>
<?php
include("connection.inc.php");

$message = "";

// Récupération des infos de la matiere premiere
$mp_actuel = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idmp'])) {
    $idmp = $_POST['idmp'];

    $stid = oci_parse($conn, "SELECT * FROM Matieres_premieres WHERE ID_MP = :idmp");
    oci_bind_by_name($stid, ":idmp", $idmp);
    oci_execute($stid);
    $mp_actuel = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    if (!$mp_actuel) {
        $message = "<div class='alert alert-danger text-center'>🎅 Aucune Matiere Première trouvé avec cet ID.</div>";
    }
}

// Gestion de la modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $idateier = $_POST['idmp'];
    $new_id = isset($_POST['new_id']) && !empty($_POST['new_id']) ? $_POST['new_id'] : $idmp;
    $source = isset($_POST['source']) && !empty($_POST['source']) ? $_POST['source'] : $mp_actuel['SOURCE'];
    $nom = isset($_POST['nom']) && !empty($_POST['nom']) ? $_POST['nom'] : $mp_actuel['NOM'];

    $update_stid = oci_parse($conn, "UPDATE Matieres_premieres SET ID_MP = :new_id, SOURCE=:source ,NOM= :nom WHERE ID_MP = :idmp");
    oci_bind_by_name($update_stid, ":new_id", $new_id);
    oci_bind_by_name($update_stid, ":source", $source);
    oci_bind_by_name($update_stid, ":nom", $nom);

    if (oci_execute($update_stid)) {
        $message = "<div class='alert alert-success text-center'>🎄 Matiere premiere modifié avec succès !</div>";
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
    <title>Modifier une Matiere Premiere 🎅</title>
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
    <h2 class="text-center">🎄 Modifier une Matiere Premiere 🎄</h2>
    <?php echo $message; ?>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ID MP (Obligatoire) 🎅</label>
            <input type="text" class="form-control" name="idmp" required value="<?php echo isset($idmp) ? htmlentities($idmp) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-christmas w-100">🔍 Rechercher</button>
    </form>

    <?php if ($mp_actuel): ?>
        <!-- Formulaire de modification -->
        <form method="POST" class="mt-4">
            <input type="hidden" name="idmp" value="<?php echo htmlentities($mp_actuel['ID_MP']); ?>">

            <div class="mb-3">
                <label class="form-label">ID Actuel : <?php echo htmlentities($mp_actuel['ID_MP']); ?> 🎅</label>
                <button type="button" class="btn btn-warning btn-sm" id="btn-modifier-id">Modifier l'ID</button>
            </div>

            <div class="mb-3 hidden" id="champs-new-id">
                <label class="form-label">🎁 Nouveau ID</label>
                <input type="text" class="form-control" name="new_id">
            </div>

            <div class="mb-3">
                <label class="form-label">🎅 Source (Actuel : <?php echo htmlentities($mp_actuel['SOURCE']); ?>)</label>
                <input type="text" class="form-control" name="noma" >
            </div>

            <div class="mb-3">
                <label class="form-label">🎄 Specialité (Actuel : <?php echo htmlentities($mp_actuel['NOM']); ?>)</label>
                <input type="text" class="form-control" name="idspecialite" placeholder="Laisser vide pour conserver l'ancien">
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

