<?php include("securite.inc.php"); ?>
<?php
include("myparam.inc.php");
header('Content-Type: application/json');

if (!$conn = oci_connect(MYUSER, MYPASS, MYHOST)) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

$queryNumber = $_POST['query'];
$queries = [
    1 => "select ID_enfants,Adresse from Enfants e, Tournee t where e.ID_tournee=t.ID_tournee and t.date_tr=to_date('2025-12-22','YYYY-MM-DD')",
    2 => "select Prenom from Enfants e, jouet j, commande c where c.ID_jouet=j.ID_jouet and c.ID_enfants=e.ID_enfants and j.Type_j='Voiture' and e.Prenom LIKE 'P%' ORDER BY e.Prenom asc",
    3 => "select Type_j from jouet j, fabrique f, constituer_par c, Matieres_premieres m,Atelier a where j.ID_jouet=f.ID_jouet and f.ID_atelier=a.ID_atelier and c.ID_jouet=j.ID_jouet and c.ID_mp=m.ID_mp and m.nom IN ('Bois de sapin','Acier')",
    4 => "SELECT t.ID_traineau, SUM(l.poids) AS Poids_total_transporté FROM Traineau t, liste_jouet l WHERE t.ID_traineau = l.ID_traineau GROUP BY t.ID_traineau ORDER BY Poids_total_transporté DESC",
    5 => "select Upper(e.Nom) from Elfes e, Equipe Eq, Flotte f where f.ID_flotte=Eq.ID_flotte and Eq.ID_equipe=e.ID_equipe and e.dirige != 1 order by Upper(e.Nom) desc",
    6 => "select couleur_nez, SUM(capacite) from Renne GROUP BY couleur_nez HAVING SUM(capacite)>250",
    7 => "select position_re, COUNT(Puce) AS nbr_renne_non_chef from Renne r, Traineau t where r.couleur_nez!='rouge' and r.capacite<= 150 and r.Nom not like '%a' and r.Nom not like '%e' and r.Nom not like '%u' and r.Nom not like '%i' and r.Nom not like '%o' and r.ID_traineau=t.ID_traineau and T.capac_tr IN (select capac_tr from Traineau where capac_tr>845) group by r.position_re",
    8 => "select ID_traineau from Traineau where capac_tr BETWEEN 800 and 1200",
    9 => "select tr.ID_traineau, tr.capac_tr, T.destination, T.date_tr from Traineau tr LEFT JOIN Tournee T ON T.ID_traineau=tr.ID_traineau",
    10 => "select ID_atelier from fabrique where ID_jouet='J0003' intersect(select Id_atelier from fabrique where ID_jouet='J0004')",
    11 => "select ID_jouet from constituer_par c1 where not exists (select ID_mp from Matieres_premieres minus(select ID_mp from constituer_par c2 where c2.ID_jouet = c1.ID_jouet))",
    13 => "SELECT a.ID_atelier FROM Atelier a, Fabrique f WHERE a.ID_atelier = f.ID_atelier(+) AND (f.ID_jouet != 'J0001' OR f.ID_jouet IS NULL)",
    14 => "select COUNT(el.ID_elfe) as nbr_elfe, e.ID_entrepot from Entrepot e, Equipe eq,Elfes el where eq.ID_equipe=el.ID_equipe and eq.ID_entrepot=e.ID_entrepot group by e.ID_entrepot",
    15 => "SELECT t.ID_traineau, (SELECT SUM(l.poids) FROM liste_jouet l WHERE l.ID_traineau = t.ID_traineau) AS charge_traineau, t.capac_tr AS capacite_traineau, (SELECT SUM(r.capacite) FROM Renne r WHERE r.ID_traineau = t.ID_traineau) AS capac_total_renne FROM Traineau t",
    16 => "SELECT Type_j FROM (SELECT Type_j, COUNT(*) AS maxi FROM (SELECT j.ID_jouet, j.Type_j FROM jouet j WHERE ID_jouet IN (SELECT p.ID_jouet FROM Produit p UNION ALL SELECT f.ID_jouet FROM Fabrique f)) GROUP BY Type_j) WHERE maxi = (SELECT MAX(maxi) FROM (SELECT COUNT(*) AS maxi FROM (SELECT j.ID_jouet, j.Type_j FROM jouet j WHERE ID_jouet IN (SELECT p.ID_jouet FROM Produit p UNION ALL SELECT f.ID_jouet FROM Fabrique f)) GROUP BY Type_j))",
    17 => "SELECT NVL(f.ID_atelier, 'NULL') AS ID_atelier, e.ID_equipe, c.ID_liste, co.ID_mp, NVL(p.ID_st, 'NULL') AS ID_st FROM jouet j, Contient c, Constituer_par co, Produit p, Fabrique f, Equipe e WHERE j.ID_jouet = 'J0011' AND j.ID_jouet = c.ID_jouet(+) AND j.ID_jouet = co.ID_jouet(+) AND j.ID_jouet = p.ID_jouet(+) AND j.ID_jouet = f.ID_jouet(+) AND f.ID_atelier = e.ID_atelier(+)",
    18 => "select e.ID_elfe from Elfes e,Equipe eq where e.ID_equipe=eq.ID_equipe(+) and (eq.ID_equipe!='EQ001' or eq.Nom is NULL)",
    19 => "SELECT i.ID_intermittent, i.Adresse FROM Intermittent i WHERE i.ID_intermittent NOT IN (SELECT p.ID_intermittent FROM Participe p)",
    20 => "select avg(capac_tr) from traineau",
    21 => "select min(capacite) from renne",
    22 => "SELECT ID_atelier AS id, nom_A AS nom, 'Atelier' AS type FROM Atelier UNION SELECT ID_mp AS id, source AS origine, 'Matière Première' AS type FROM Matieres_premieres",
    23 => "select ID_enfants from Enfants where length(Adresse)<=20",
    24 => "select ID_atelier from fabrique where ID_jouet = 'J0001' UNION select ID_atelier from fabrique where ID_jouet ='J0002'"
];

if (!isset($queries[$queryNumber])) {
    echo json_encode(['error' => 'Invalid query number']);
    exit;
}

$stid = oci_parse($conn, $queries[$queryNumber]);
if (!$stid) {
    echo json_encode(['error' => 'Query parsing failed']);
    exit;
}

if (!oci_execute($stid)) {
    echo json_encode(['error' => 'Query execution failed']);
    exit;
}

$results = [];
$ncols = oci_num_fields($stid);

while ($row = oci_fetch_assoc($stid)) {
    $results[] = $row;
}

oci_free_statement($stid);
oci_close($conn);

echo json_encode(['results' => $results]);
?>
