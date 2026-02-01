<?php include("securite.inc.php"); ?>
<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include("connection.inc.php");
	$mdp='0000';
	$mdp_hache = hash('sha256', $mdp);
	$sql = "INSERT INTO mdp (mot_de_passe) VALUES (:mdp)";
	$inserer = oci_parse($conn, $sql);
	oci_bind_by_name($inserer, ':mdp', $mdp_hache);
	if (!$inserer) {
            $e = oci_error($inserer);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        oci_execute($inserer);
?>
