<?php include("securite.inc.php");?>
<?php
include("connection.inc.php");

$message = "";

// Récupération des infos de l'elfe
$req_actuel = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stid = oci_parse($conn, "SELECT * FROM renne  WHERE PUCE = :id");
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
    $new_id = isset($_POST['PUCE']) && !empty($_POST['PUCE']) ? $_POST['PUCE'] : $id;
    $NOM= isset($_POST['NOM']) && !empty($_POST['NOM']) ?$_POST['NOM'] : $req_actuel['NOM'];
    $COULEUR_NEZ = isset($_POST['COULEUR_NEZ']) && !empty($_POST['COULEUR_NEZ']) ? $_POST['COULEUR_NEZ'] : $req_actuel['COULEUR_NEZ'];
    $POSITION_RE = isset($_POST['POSITION_RE']) && !empty($_POST['POSITION_RE']) ? $_POST['POSITION_RE'] : $req_actuel['POSITION_RE'];
    $CAPACITE = isset($_POST['CAPACITE']) && !empty($_POST['CAPACITE']) ? $_POST['CAPACITE'] : $req_actuel['CAPACITE'];
    $ID_TRAINEAU = isset($_POST['ID_TRAINEAU']) && !empty($_POST['ID_TRAINEAU']) ? $_POST['ID_TRAINEAU'] : $req_actuel['ID_TRAINEAU'];

    $update_stid = oci_parse($conn, "UPDATE renne SET PUCE = :new_id,NOM=:NOM ,COULEUR_NEZ = :COULEUR_NEZ,POSITION_RE = :POSITION_RE , CAPACITE = :CAPACITE , ID_TRAINEAU = :ID_TRAINEAU WHERE PUCE = :id");
    oci_bind_by_name($update_stid, ":new_id", $new_id);
    oci_bind_by_name($update_stid, ":NOM", $NOM);
    oci_bind_by_name($update_stid, ":COULEUR_NEZ", $COULEUR_NEZ);
    oci_bind_by_name($update_stid, ":POSITION_RE", $POSITION_RE);
    oci_bind_by_name($update_stid, ":CAPACITE", $CAPACITE);
    oci_bind_by_name($update_stid, ":ID_TRAINEAU", $ID_TRAINEAU);
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
    <h2 class="text-center">🎄 Modifier un renne 🎄</h2>
    <?php echo $message; ?>

    <!-- Formulaire de recherche -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ID renne (Obligatoire) 🎅</label>
            <input type="text" class="form-control" name="id" required value="<?php echo isset($id) ? htmlentities($id) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-christmas w-100">🔍 Rechercher</button>
    </form>

    <?php if ($req_actuel): ?>
        <!-- Formulaire de modification -->
        <form method="POST" class="mt-4">
            <input type="hidden" name="id" value="<?php echo htmlentities($req_actuel['PUCE']); ?>">

            <div class="mb-3">
                <label class="form-label">ID Actuel : <?php echo htmlentities($req_actuel['PUCE']); ?> 🎅</label>
                <button type="button" class="btn btn-warning btn-sm" id="btn-modifier-id">Modifier l'ID</button>
            </div>

            <div class="mb-3 hidden" id="champs-new-id">
                <label class="form-label">🎁 Nouveau ID</label>
                <input type="text" class="form-control" name="PUCE">
            </div>

            <div class="mb-3">
                <label class="form-label">🎅 NOM (Actuel : <?php echo htmlentities($req_actuel['NOM']); ?>)</label>
                <input type="text" class="form-control" name="NOM" placeholder="Laisser vide pour conserver l'ancien" >
            </div>

            <div class="mb-3">
                <label class="form-label">🎄 COULEUR_NEZ (Actuel : <?php echo htmlentities($req_actuel['COULEUR_NEZ']); ?>)</label>
                <input type="text" class="form-control" name="COULEUR_NEZ" placeholder="Laisser vide pour conserver l'ancien">
            </div>
            
             <div class="mb-3">
                <label class="form-label">🎄 POSITION_RE (Actuel : <?php echo htmlentities($req_actuel['POSITION_RE']); ?>)</label>
                <input type="text" class="form-control" name="POSITION_RE" placeholder="Laisser vide pour conserver l'ancien">
            </div>
            
             <div class="mb-3">
                <label class="form-label">🎄 CAPACITE (Actuel : <?php echo htmlentities($req_actuel['CAPACITE']); ?>)</label>
                <input type="text" class="form-control" name="CAPACITE" placeholder="Laisser vide pour conserver l'ancien">
            </div>
            
             <div class="mb-3">
                <label class="form-label">🎄 ID_TRAINEAU (Actuel : <?php echo htmlentities($req_actuel['ID_TRAINEAU']); ?>)</label>
                <input type="text" class="form-control" name="ID_TRAINEAU" placeholder="Laisser vide pour conserver l'ancien">
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

