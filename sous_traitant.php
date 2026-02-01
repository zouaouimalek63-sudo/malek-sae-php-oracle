<?php include("securite.inc.php");?>
 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des sous-Traitants</title>
    <style>
        /* Mise en page générale */
        body {
            font-family: Arial, sans-serif;
            background-image: url('noel.jpeg'); /* Image de fond */
            background-size: cover;
            background-position: center;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* Conteneur principal */
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        /* Titre */
        h1 {
            color: #c0392b;
            margin-bottom: 20px;
        }

        /* Grille des boutons */
        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }

        /* Boutons */
        .btn {
            padding: 15px;
            font-size: 18px;
            background: #c0392b;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            background: #e74c3c;
            transform: translateY(-3px);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Gestion des Sous-Traitants</h1>

        <div class="buttons-grid">
            <button class="btn" onclick="window.location.href='info_st.php'">Informations</button>
            <button class="btn" onclick="window.location.href='ajout_st.php'">Ajout</button>
            <button class="btn" onclick="window.location.href='modif_st.php'">Modification</button>
            <button class="btn" onclick="window.location.href='suppr_st.php'">Suppression</button>
        </div>
    </div>

</body>
</html>
