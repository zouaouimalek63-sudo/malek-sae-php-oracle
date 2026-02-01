<?php include("securite.inc.php"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Chris Kindle</title>
    <style>
        /* Mise en page générale */
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            color: #fff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-image: url('noel.jpeg');
            background-size: cover;
            background-position: center;
            overflow-x: hidden;
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

        /* Bouton Paramètres */
        #settings-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 100;
        }

        #settings-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: rotate(30deg);
        }

        /* Panneau latéral des paramètres */
        #settings-panel {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.2);
            transition: right 0.3s;
            z-index: 99;
            padding: 20px;
            box-sizing: border-box;
            color: #2c3e50;
        }

        #settings-panel.show {
            right: 0;
        }

        #settings-panel h2 {
            color: #c0392b;
            border-bottom: 2px solid #c0392b;
            padding-bottom: 10px;
        }

        #settings-panel a {
            display: block;
            padding: 10px;
            margin: 10px 0;
            background: #f1f1f1;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.2s;
        }

        #settings-panel a:hover {
            background: #c0392b;
            color: white;
        }

        /* Contenu principal */
        .main-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 100px 20px 50px;
        }

        /* Bouton Recherche */
        .search-btn {
            background-color: #c0392b;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            margin-bottom: 30px;
            transition: all 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .search-btn:hover {
            background-color: #e74c3c;
            transform: translateY(-3px);
        }

        /* Champ de recherche */
        #search-box {
            width: 80%;
            max-width: 500px;
            padding: 15px;
            margin: 20px 0;
            border: 2px solid #c0392b;
            border-radius: 10px;
            font-size: 16px;
            display: none;
        }

        /* Grille des options */
        .options-container {
            width: 90%;
            max-width: 1200px;
            margin-top: 30px;
        }

        .entity-section {
            margin-bottom: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .entity-title {
            color: #c0392b;
            margin-top: 0;
            border-bottom: 2px solid #c0392b;
            padding-bottom: 10px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .action-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #2c3e50;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .action-card:hover {
            background: #e9ecef;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .action-card h3 {
            margin: 0 0 10px 0;
            color: #c0392b;
        }

        .action-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .main-content {
            animation: fadeIn 0.6s ease-out;
        }

        /* Boutons flottants */
        .floating-buttons {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 98;
        }

        .floating-btn {
            padding: 12px 20px;
            border-radius: 30px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .info-btn {
            background-color: #3498db;
            color: white;
        }

        .faq-btn {
            background-color: #2ecc71;
            color: white;
        }

        .floating-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        }

        .floating-btn:active {
            transform: translateY(1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-grid {
                grid-template-columns: 1fr;
            }
            
            #logo {
                font-size: 2em;
                top: 15px;
                left: 15px;
            }

            .floating-buttons {
                bottom: 20px;
                right: 20px;
            }
            
            .floating-btn {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <!-- Logo textuel -->
    <div id="logo">
        Welcome to Chris Kindle
    </div>

    <!-- Bouton Paramètres -->
    <button id="settings-btn">⚙️</button>

    <!-- Panneau Paramètres -->
    <div id="settings-panel">
        <h2>Paramètres</h2>
        <a href="modif.php">Modifier coordonnées</a>
        <a href="deconnexion.php">Déconnexion</a>
    </div>

    <!-- Contenu principal -->
    <div class="main-content">
        <button class="search-btn" id="toggleSearch">Recherche</button>

        <input type="text" id="search-box" placeholder="Entrez votre recherche...">

        <div class="options-container">
            <!-- Section Elfe -->
            <div class="entity-section">
                <h2 class="entity-title">Elfe</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_elfe.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouvel elfe</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_elfe.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un elfe existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_elfe.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Enfant -->
            <div class="entity-section">
                <h2 class="entity-title">Enfant</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_enfant.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouvel enfant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_enfant.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un enfant existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_enfant.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Traineau -->
            <div class="entity-section">
                <h2 class="entity-title">Traineau</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_traineau.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouveau traineau</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_traineau.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un traineau existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_traineau.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Renne -->
            <div class="entity-section">
                <h2 class="entity-title">Renne</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_renne.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouveau renne</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_renne.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un renne existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_renne.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Tournée -->
            <div class="entity-section">
                <h2 class="entity-title">Tournée</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_tournee.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter une nouvelle tournée</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_tournee.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier une tournée existante</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_tournee.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Equipe -->
            <div class="entity-section">
                <h2 class="entity-title">Equipe</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_equipe.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter une nouvelle équipe</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_equipe.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier une équipe existante</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_equipe.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Entrepôt -->
            <div class="entity-section">
                <h2 class="entity-title">Entrepôt</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_entrepot.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouvel entrepôt</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_entrepot.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un entrepôt existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_entrepot.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Intermittent -->
            <div class="entity-section">
                <h2 class="entity-title">Intermittent</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_intermittent.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouvel intermittent</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_intermittent.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un intermittent existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_intermittent.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Jouet -->
            <div class="entity-section">
                <h2 class="entity-title">Jouet</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_jouet.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouveau jouet</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_jouet.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un jouet existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_jouet.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Liste Jouet -->
            <div class="entity-section">
                <h2 class="entity-title">Liste Jouet</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_liste_jouet.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter une nouvelle liste</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_liste_jouet.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier une liste existante</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_liste_jouet.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Matières Premières -->
            <div class="entity-section">
                <h2 class="entity-title">Matières Premières</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_mp.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter une nouvelle matière</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_mp.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier une matière existante</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_mp.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Flotte -->
            <div class="entity-section">
                <h2 class="entity-title">Flotte</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_flotte.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter une nouvelle flotte</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_flotte.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier une flotte existante</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_flotte.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Atelier -->
            <div class="entity-section">
                <h2 class="entity-title">Atelier</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_atelier.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouvel atelier</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_atelier.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un atelier existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_atelier.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>

            <!-- Section Sous-traitant -->
            <div class="entity-section">
                <h2 class="entity-title">Sous-traitant</h2>
                <div class="action-grid">
                    <div class="action-card" onclick="window.location.href='ajout_st.php'">
                        <div class="action-icon">➕</div>
                        <h3>Ajouter</h3>
                        <p>Ajouter un nouveau sous-traitant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='modif_st.php'">
                        <div class="action-icon">✏️</div>
                        <h3>Modifier</h3>
                        <p>Modifier un sous-traitant existant</p>
                    </div>
                    <div class="action-card" onclick="window.location.href='info_st.php'">
                        <div class="action-icon">ℹ️</div>
                        <h3>Informations</h3>
                        <p>Consulter les informations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Boutons flottants -->
    <div class="floating-buttons">
        <button class="floating-btn info-btn" onclick="window.location.href='info_table.php'">
            ℹ️ PLUS D'informations
        </button>
        <button class="floating-btn faq-btn" onclick="window.location.href='complim.php'">
            ❓ Foire à questions
        </button>
    </div>

    <script>
        // Gestion du panneau paramètres
        document.getElementById('settings-btn').addEventListener('click', function() {
            document.getElementById('settings-panel').classList.toggle('show');
        });

        // Gestion de la recherche
        document.getElementById('toggleSearch').addEventListener('click', function() {
            const searchBox = document.getElementById('search-box');
            searchBox.style.display = searchBox.style.display === 'block' ? 'none' : 'block';
            if (searchBox.style.display === 'block') {
                searchBox.focus();
            }
        });

        // Recherche dans les options
        document.getElementById('search-box').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const sections = document.querySelectorAll('.entity-section');
            
            sections.forEach(section => {
                const title = section.querySelector('.entity-title').textContent.toLowerCase();
                const cards = section.querySelectorAll('.action-card');
                
                if (title.includes(searchTerm)) {
                    section.style.display = 'block';
                    cards.forEach(card => card.style.display = 'block');
                } else {
                    // Vérifier si une action correspond
                    let hasMatch = false;
                    cards.forEach(card => {
                        const cardText = card.textContent.toLowerCase();
                        if (cardText.includes(searchTerm)) {
                            card.style.display = 'block';
                            hasMatch = true;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    
                    section.style.display = hasMatch ? 'block' : 'none';
                }
            });
        });

        // Redirection si la recherche correspond exactement à une option
        document.getElementById('search-box').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = e.target.value.toLowerCase().trim();
                const actions = {
                    'ajouter elfe': 'ajout_elfe.php',
                    'modifier elfe': 'modif_elfe.php',
                    'info elfe': 'info_elfe.php',
                    'ajouter enfant': 'ajout_enfant.php',
                    'modifier enfant': 'modif_enfant.php',
                    'info enfant': 'info_enfant.php',
                    'info traineau': 'info_traineau.php',
                    'modifier traineau': 'modif_traineau.php',
                    'ajouter traineau': 'ajout_traineau.php',
                    'info renne': 'info_renne.php',
                    'modifier renne': 'modif_renne.php',
                    'ajouter renne': 'ajout_renne.php',
                    'info tournee': 'info_tournee.php',
                    'modifier tournee': 'modif_tournee.php',
                    'ajouter tournee': 'ajout_tournee.php',
                    'info equipe': 'info_equipe.php',
                    'modifier equipe': 'modif_equpe.php',
                    'ajouter equipe': 'ajout_equipe.php',
                    'info entrepot': 'info_entrepot.php',
                    'modifier entrepot': 'modif_entrepot.php',
                    'ajouter entrepot': 'ajout_entrepot.php',
                    'info intermittent': 'info_intermittent.php',
                    'modifier intermittent': 'modif_intermittent.php',
                    'ajouter intermittent': 'ajout_intermittent.php',
                    'info jouet': 'info_jouet.php',
                    'modifier jouet': 'modif_jouet.php',
                    'ajouter jouet': 'ajout_jouet.php',
                    'info liste_jouet': 'info_liste_jouet.php',
                    'modifier liste_jouet': 'modif_liste_jouet.php',
                    'ajouter liste_jouet': 'ajout_liste_jouet.php',
                    'info mp': 'info_mp.php',
                    'modifier mp': 'modif_mp.php',
                    'ajouter mp': 'ajout_mp.php',
                    'info flotte': 'info_flotte.php',
                    'modifier flotte': 'modif_flotte.php',
                    'ajouter flotte': 'ajout_flotte.php',
                    'info atelier': 'info_atelier.php',
                    'modifier atelier': 'modif_atelier.php',
                    'ajouter atelier': 'ajout_atelier.php',
                    'info st': 'info_st.php',
                    'modifier st': 'modif_st.php',
                    'ajouter st': 'ajout_st.php',

                    // Ajouter toutes les autres combinaisons possibles
                };

                if (actions[searchTerm]) {
                    window.location.href = actions[searchTerm];
                }
            }
        });
    </script>
</body>
</html>
