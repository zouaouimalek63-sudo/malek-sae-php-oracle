<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$error_message = ""; // Initialisation de la variable d'erreur

if(isset($_POST['ids']) && isset($_POST['mdp']) && isset($_POST['code_secret'])){
    include("connection.inc.php");
    $mdpasse = $_POST['code_secret'];
    $ids = $_POST['ids'];
    $mdp = $_POST['mdp'];
    
    // Vérification si l'identifiant est déjà pris
    $verif_id = oci_parse($conn, "SELECT COUNT(*) FROM lutins WHERE ID_LUTIN = :id");
    oci_bind_by_name($verif_id, ':id', $ids);
    oci_execute($verif_id);
    $row_count = oci_fetch_array($verif_id)[0];
    
    if($row_count > 0) {
        $error_message = "Cet identifiant est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // Vérification du code secret
        $code_verif = oci_parse($conn, "SELECT MOT_DE_PASSE from mdp");
        oci_execute($code_verif);
        $row = oci_fetch_assoc($code_verif);
        
        if (!$row) {
            $error_message = "Erreur lors de la vérification du code secret.";
        } elseif (hash('sha256', $mdpasse) !== $row['MOT_DE_PASSE']) {
            $error_message = "Code secret incorrect.";
        } else {   
            $mdp_hache = hash('sha256', $mdp);
            $sql = "INSERT INTO lutins (ID_lutin, Mot_de_passe) VALUES (:id, :mdp)";
            $inserer = oci_parse($conn, $sql);
            oci_bind_by_name($inserer, ':id', $ids);
            oci_bind_by_name($inserer, ':mdp', $mdp_hache);
            
            if (!$inserer) {
                $e = oci_error($inserer);
                $error_message = "Erreur de préparation de la requête: ".htmlentities($e['message'], ENT_QUOTES);
            } else {
                $result = oci_execute($inserer);
                if (!$result) {
                    $e = oci_error($inserer);
                    $error_message = "Erreur lors de la création du compte: ".htmlentities($e['message'], ENT_QUOTES);
                } else {
                    header("location:connexion.php");
                    exit();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Chris Kindle</title>
    <style>
        /* Mise en page générale */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('noel.jpeg');
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        /* Logo textuel */
        #logo {
            position: absolute;
            top: 20px;
            left: 30px;
            font-size: 3em;
            font-weight: bold;
            color: #e74c3c;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            font-family: 'Courier New', Courier, monospace;
        }

        /* Formulaire de connexion */
        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        /* Styles supplémentaires pour le formulaire */
        .form-container:hover {
            transform: translateY(-10px);
        }

        /* Champs de texte */
        input[type="text"], input[type="password"], input[type="email"], input[type="code_secret"] {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 2px solid #c0392b;
            border-radius: 10px;
            font-size: 16px;
            color: #2c3e50;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        /* Lorsque le champ est sélectionné */
        input[type="text"]:focus, input[type="password"]:focus, input[type="code_secret"]:focus {
            border-color: #e74c3c;
            outline: none;
        }

        /* Bouton de soumission */
        input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #c0392b;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Effet au survol du bouton */
        input[type="submit"]:hover {
            background-color: #e74c3c;
        }

        /* Message d'erreur */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: rgba(231, 76, 60, 0.1);
            border-radius: 5px;
            border: 1px solid #e74c3c;
        }

        /* Message de succès */
        .success-message {
            color: #27ae60;
            font-size: 14px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: rgba(39, 174, 96, 0.1);
            border-radius: 5px;
            border: 1px solid #27ae60;
        }

        /* Ajouter une touche de Noël avec des couleurs */
        h1 {
            color: #2c3e50;
            font-size: 2em;
        }

        /* Animation d'entrée pour le formulaire */
        .form-container {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Icône œil pour afficher/masquer le mot de passe */
        .password-toggle {
            position: absolute;
            right: 1px;
            top: 35px;
            cursor: pointer;
            color: #c0392b;
        }

        /* Lien mot de passe oublié et créer un compte */
        .links {
            margin-top: 15px;
        }

        .links a {
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin-top: 10px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        /* Code secret, placé à droite sous le mot de passe */
        .code-secret-container {
            position: relative;
            display: flex;
            justify-content: flex-end;
        }

        input[type="text"], input[type="password"], input[type="code_secret"] {
            width: 95%;
        }
    </style>
</head>
<body>

    <div id="logo">
        Welcome to Chris Kindle
    </div>

    <div class="form-container">
        <h1>Création de compte</h1>

        <!-- Formulaire de connexion -->
        <form id="loginForm" action="" method="POST">
            <?php if(!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <input type="text" id="username" name="ids" placeholder="Nom d'utilisateur" required value="<?php echo isset($_POST['ids']) ? htmlspecialchars($_POST['ids']) : ''; ?>">

            <!-- Champ Mot de Passe -->
            <div style="position: relative;">
                <input type="password" id="password" name="mdp" placeholder="Mot de passe" required>
                <span class="password-toggle" id="togglePassword" onclick="togglePassword()">
                    👁️
                </span>
            </div>

            <!-- Champ Code Secret -->
            <div class="code-secret-container">
                <input type="text" id="code_secret" name="code_secret" placeholder="Code secret" required>
            </div>

            <input type="submit" value="Valider">
        </form>
    </div>

    <script>
        // Fonction pour afficher/masquer le mot de passe
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.textContent = "🙈"; // Icône pour masquer
            } else {
                passwordField.type = "password";
                toggleIcon.textContent = "👁️"; // Icône pour afficher
            }
        }
    </script>

</body>
</html>
