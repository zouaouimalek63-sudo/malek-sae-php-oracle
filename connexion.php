<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$error_message = '';  // Déclaration de la variable d'erreur

if(isset($_POST['ids']) && isset($_POST['mdp'])){
    include("connection.inc.php");
    $ids=$_POST['ids'];
    $mdp=$_POST['mdp'];
    session_start();
    
    // Vérification de l'existence de l'ID
    $login_verif = oci_parse($conn, "SELECT ID_LUTIN FROM lutins WHERE ID_LUTIN = :old_l");
    oci_bind_by_name($login_verif, ":old_l", $ids);
    if (!$login_verif) {
        $e = oci_error($login_verif);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_execute($login_verif);
    $login_verif = oci_fetch_assoc($login_verif);
    
    // Si l'ID n'existe pas
    if (!$login_verif) {
        $error_message = 'Nom d\'utilisateur invalide.';
    } else {
        // Vérification du mot de passe
        $mdp_verif = oci_parse($conn, "SELECT MOT_DE_PASSE FROM lutins WHERE ID_LUTIN = :old_l");
        oci_bind_by_name($mdp_verif, ":old_l", $ids);
        if (!$mdp_verif) {
            $e = oci_error($mdp_verif);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        oci_execute($mdp_verif);
        $mdp_verif = oci_fetch_assoc($mdp_verif);
        
        // Si le mot de passe est incorrect
        if (hash('sha256', $mdp) !== $mdp_verif['MOT_DE_PASSE']) {
            $error_message = 'Mot de passe incorrect.';
        } else {
            $_SESSION['ID_LUTIN'] = $ids;
            $_SESSION['MOT_DE_PASSE'] = $mdp;
            header("Location: recherche.php");
            exit();
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
            background-color: #f1f1f1; /* Couleur de fond légère */
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('noel.jpeg'); /* Image de fond pour l'ambiance de Noël */
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
            background-color: rgba(255, 255, 255, 0.8); /* Fond semi-transparent */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            width: 400px; /* Augmenter la largeur du tableau */
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        /* Styles supplémentaires pour le formulaire */
        .form-container:hover {
            transform: translateY(-10px);
        }

        /* Champs de texte */
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 2px solid #c0392b; /* Bordure rouge de Noël */
            border-radius: 10px;
            font-size: 16px;
            color: #2c3e50;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        /* Lorsque le champ est sélectionné */
        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
            border-color: #e74c3c;
            outline: none;
        }

        /* Bouton de soumission */
        input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #c0392b; /* Rouge vif de Noël */
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
            right: 15px;
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

    </style>
</head>
<body>

    <!-- Logo textuel "Welcome to Chris Kindle" -->
    <div id="logo">
        Welcome to Chris Kindle
    </div>

    <div class="form-container">
        <h1>Connexion à l'administration</h1>

        <!-- Formulaire de connexion -->
        <form id="loginForm" action="" method="POST">
            <div class="error-message">
                <?php
                // Si une erreur de connexion survient, afficher un message
                if (isset($error_message)) {
                    echo $error_message;
                }
                ?>
            </div>

            <input type="text" id="username" name="ids" placeholder="Nom d'utilisateur" required>

            <!-- Champ Mot de Passe -->
            <div style="position: relative;">
                <input type="password" id="password" name="mdp" placeholder="Mot de passe" required>
                <span class="password-toggle" id="togglePassword" onclick="togglePassword()">
                    👁️
                </span>
            </div>

            <input type="submit" value="Se connecter">
        </form>

        <!-- Liens pour mot de passe oublié et création de compte -->
        <div class="links">
            
            <a href="premiere.php">Créer un compte</a>
        </div>
    </div>

    <script>
        // script.js
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', function(event) {
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                if (username === "" || password === "") {
                    event.preventDefault();
                    alert("Veuillez remplir tous les champs !");
                }
            });
        });

        // Fonction pour afficher/masquer le mot de passe
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');

            // Si le type est "password", on change en "text" pour afficher
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
