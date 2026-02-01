<?php include("securite.inc.php");?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface de Requêtes Noël</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('noel.jpeg');
            background-size: cover;
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .query-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid #d4af37;
            position: relative;
            overflow: hidden;
        }
        
        .query-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            background-color: rgba(255, 248, 220, 0.95);
        }
        
        .query-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #d4af37, #c9b037, #d4af37);
        }
        
        .query-card h3 {
            color: #c41e3a;
            margin-top: 0;
            font-size: 18px;
            border-bottom: 1px dashed #d4af37;
            padding-bottom: 10px;
        }
        
        .query-card p {
            color: #555;
            font-size: 14px;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: auto;
        }
        
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 900px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border: 3px solid #d4af37;
            position: relative;
        }
        
        .close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #c41e3a;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th {
            background-color: #c41e3a;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #d4af37;
        }
        
        .header p {
            font-size: 1.2em;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .snowflake {
            position: fixed;
            color: #fff;
            font-size: 1em;
            pointer-events: none;
            animation: fall linear infinite;
            z-index: -1;
        }
        
        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎄 quelques informations de plus 🎄</h1>
    </div>
    
    <div class="container">
        <div class="query-card" onclick="runQuery(1)">
            <h3>Enfants concernés par les tournées du 22/12/2025</h3>
            <p>Affiche les noms et adresses des enfants qui recevront des cadeaux lors des tournées commençant le 22 décembre 2025.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(2)">
            <h3>Enfants avec prénom en 'P' commandant des voitures</h3>
            <p>Liste les enfants dont le prénom commence par 'P' et qui ont commandé des jouets de type voiture.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(3)">
            <h3>Jouets en bois ou métal</h3>
            <p>Montre les types de jouets fabriqués en bois de sapin ou en acier par les ateliers.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(4)">
            <h3>Poids transporté par traîneau</h3>
            <p>Calcule la somme des poids des jouets transportés par chaque traîneau, du plus lourd au plus léger.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(5)">
            <h3>Elfes d'entretien non-chefs</h3>
            <p>Affiche en majuscules les noms des elfes d'entretien qui ne sont pas chefs et qui gèrent la flotte.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(6)">
            <h3>Capacité des rennes par couleur de nez</h3>
            <p>Donne la somme des capacités des rennes groupés par couleur de nez (pour les groupes dépassant 250kg).</p>
        </div>
        
        <div class="query-card" onclick="runQuery(7)">
            <h3>Rennes chefs avec restrictions</h3>
            <p>Liste les rennes chefs dont la capacité ne dépasse pas 150kg et dont le nom ne finit pas par une voyelle.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(8)">
            <h3>Traineaux de capacité moyenne</h3>
            <p>Affiche les traîneaux dont la capacité est comprise entre 800kg et 1200kg.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(9)">
            <h3>Traineaux et leurs tournées</h3>
            <p>Montre tous les traîneaux avec leur capacité et les dates/destinations de leurs tournées (y compris ceux sans tournée).</p>
        </div>
        
        <div class="query-card" onclick="runQuery(10)">
            <h3>Ateliers fabriquant J0003 et J0004</h3>
            <p>Identifie les ateliers qui ont fabriqué à la fois le jouet J0003 et le jouet J0004.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(11)">
            <h3>Jouets utilisant toutes les matières</h3>
            <p>Trouve les jouets constitués de toutes les matières premières disponibles.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(13)">
            <h3>Ateliers n'ayant pas fabriqué J0001</h3>
            <p>Liste les ateliers qui n'ont pas fabriqué le jouet J0001.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(14)">
            <h3>Elfes par entrepôt</h3>
            <p>Compte le nombre d'elfes qui gèrent chaque entrepôt.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(15)">
            <h3>Charge des traîneaux</h3>
            <p>Calcule la charge de chaque traîneau et la compare à sa capacité et à la somme des capacités de ses rennes.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(16)">
            <h3>Jouet le plus produit</h3>
            <p>Identifie le type de jouet le plus fréquemment produit.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(17)">
            <h3>Chaîne de production d'un jouet</h3>
            <p>Remonte toute la chaîne de production pour un jouet spécifique (J0011 dans l'exemple).</p>
        </div>
        
        <div class="query-card" onclick="runQuery(18)">
            <h3>Elfes hors équipe EQ001</h3>
            <p>Affiche les elfes qui n'appartiennent pas à l'équipe EQ001 (y compris ceux sans équipe).</p>
        </div>
        
        <div class="query-card" onclick="runQuery(19)">
            <h3>Intermittents inactifs</h3>
            <p>Montre les intermittents qui ne participent à aucune tournée.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(20)">
            <h3>Capacité moyenne des traîneaux</h3>
            <p>Calcule la capacité moyenne de tous les traîneaux.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(21)">
            <h3>Capacité minimale des rennes</h3>
            <p>Trouve la capacité minimale parmi tous les rennes.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(22)">
            <h3>Ateliers et matières premières</h3>
            <p>Combine dans une seule liste les ateliers et les matières premières avec leur type.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(23)">
            <h3>Enfants avec adresse courte</h3>
            <p>Liste les enfants dont l'adresse ne dépasse pas 20 caractères.</p>
        </div>
        
        <div class="query-card" onclick="runQuery(24)">
            <h3>Ateliers fabriquant J0001 ou J0002</h3>
            <p>Montre les ateliers qui ont fabriqué soit le jouet J0001, soit le jouet J0002.</p>
        </div>
    </div>
    
    <div id="queryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Résultats de la requête</h2>
            <div id="queryResults"></div>
        </div>
    </div>
    
    <script>
        function createSnowflakes() {
            const snowflakesCount = 30;
            for (let i = 0; i < snowflakesCount; i++) {
                const snowflake = document.createElement('div');
                snowflake.innerHTML = '❄';
                snowflake.classList.add('snowflake');
                snowflake.style.left = Math.random() * 100 + 'vw';
                snowflake.style.animationDuration = Math.random() * 3 + 2 + 's';
                snowflake.style.opacity = Math.random();
                snowflake.style.fontSize = (Math.random() * 10 + 10) + 'px';
                document.body.appendChild(snowflake);
            }
        }
        
        function runQuery(queryNumber) {
            // Show loading state
            document.getElementById('modalTitle').textContent = `Requête ${queryNumber} - Chargement...`;
            document.getElementById('queryResults').innerHTML = '<p>Chargement des résultats...</p>';
            document.getElementById('queryModal').style.display = 'block';
            
            // AJAX request to fetch query results
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'execute_query.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        displayResults(queryNumber, response);
                    } else {
                        document.getElementById('queryResults').innerHTML = 
                            '<p>Erreur lors de la récupération des résultats.</p>';
                    }
                }
            };
            xhr.send(`query=${queryNumber}`);
        }
        
        function displayResults(queryNumber, data) {
            const modalTitle = document.getElementById('modalTitle');
            const resultsDiv = document.getElementById('queryResults');
            
            // Update title with query description
            const queryCard = document.querySelector(`.query-card:nth-child(${queryNumber}) h3`);
            modalTitle.textContent = queryCard.textContent;
            
            if (data.error) {
                resultsDiv.innerHTML = `<p>Erreur: ${data.error}</p>`;
                return;
            }
            
            if (data.results.length === 0) {
                resultsDiv.innerHTML = '<p>Aucun résultat trouvé pour cette requête.</p>';
                return;
            }
            
            // Create table
            let tableHtml = '<table><thead><tr>';
            
            // Table headers
            for (let col in data.results[0]) {
                tableHtml += `<th>${col}</th>`;
            }
            tableHtml += '</tr></thead><tbody>';
            
            // Table rows
            data.results.forEach(row => {
                tableHtml += '<tr>';
                for (let col in row) {
                    tableHtml += `<td>${row[col]}</td>`;
                }
                tableHtml += '</tr>';
            });
            
            tableHtml += '</tbody></table>';
            resultsDiv.innerHTML = tableHtml;
        }
        
        function closeModal() {
            document.getElementById('queryModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('queryModal');
            if (event.target === modal) {
                closeModal();
            }
        }
        
        // Create snowflakes when page loads
        window.onload = function() {
            createSnowflakes();
        };
    </script>
</body>
</html>
