<?php include("securite.inc.php");?>
<?php
include("connection.inc.php");

$message = "";

// Récupération des infos de l'atelier
$atelier_actuel = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idatelier'])) {
    $idatelier = $_POST['idatelier'];

    $stid = oci_parse($conn, "SELECT * FROM Atelier WHERE ID_ATELIER = :idatelier");
    oci_bind_by_name($stid, ":idatelier", $idatelier);
    oci_execute($stid);
    $atelier_actuel = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    if (!$atelier_actuel) {
        $message = "<div class='alert alert-danger text-center'>🎅 Aucun atelier trouvé avec cet ID.</div>";
    }
}

// Gestion de la modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $idateier = $_POST['idatelier'];
    $new_id = isset($_POST['new_id']) && !empty($_POST['new_id']) ? $_POST['new_id'] : $idatelier;
    $noma = isset($_POST['noma']) && !empty($_POST['noma']) ? $_POST['noma'] : $atelier_actuel['NOM_A'];
    $idspecialite = isset($_POST['idspecialite']) && !empty($_POST['idspecialite']) ? $_POST['idspecialite'] : $atelier_actuel['ID_SPECIALITE'];

    $update_stid = oci_parse($conn, "UPDATE Atelier SET ID_ATELIER = :new_id,NOM_A=:noma ,ID_SPECIALITE = :idspecialite WHERE ID_ATELIER = :idatelier");
    oci_bind_by_name($update_stid, ":new_id", $new_id);
    oci_bind_by_name($update_stid, ":noma", $noma);
    oci_bind_by_name($update_stid, ":idspecialite", $idspecialite);

    if (oci_execute($update_stid)) {
        $message = "<div class='alert alert-success text-center'>🎄 Atelier modifié avec succès !</div>";
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
    <title>Modifier un Atelier 🎅</title>
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
    <h2 class="text-center">🎄 Modifier un Atelier 🎄</h2>
    <?php echo $message; ?>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ID Atelier (Obligatoire) 🎅</label>
            <input type="text" class="form-control" name="idatelier" required value="<?php echo isset($idatelier) ? htmlentities($idateier) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-christmas w-100">🔍 Rechercher</button>
    </form>

    <?php if ($atelier_actuel): ?>
        <!-- Formulaire de modification -->
        <form method="POST" class="mt-4">
            <input type="hidden" name="idatelier" value="<?php echo htmlentities($atelier_actuel['ID_ATELIER']); ?>">

            <div class="mb-3">
                <label class="form-label">ID Actuel : <?php echo htmlentities($atelier_actuel['ID_ATELIER']); ?> 🎅</label>
                <button type="button" class="btn btn-warning btn-sm" id="btn-modifier-id">Modifier l'ID</button>
            </div>

            <div class="mb-3 hidden" id="champs-new-id">
                <label class="form-label">🎁 Nouveau ID</label>
                <input type="text" class="form-control" name="new_id">
            </div>

            <div class="mb-3">
                <label class="form-label">🎅 Nom (Actuel : <?php echo htmlentities($atelier_actuel['NOM_A']); ?>)</label>
                <input type="text" class="form-control" name="noma" >
            </div>

            <div class="mb-3">
                <label class="form-label">🎄 Specialité (Actuel : <?php echo htmlentities($atelier_actuel['ID_SPECIALITE']); ?>)</label>
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

