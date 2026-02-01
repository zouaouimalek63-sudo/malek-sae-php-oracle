<?php include("securite.inc.php");?>
<?php
    if(isset($_POST['ids']) && isset($_POST['mdp'])){
        include("connection.inc.php");
        $ids=$_POST['ids'];
        $mdp=$_POST['mdp'];
        session_start();
        $_SESSION['db_conn']=$conn; 
        $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
        $sql = "INSERT INTO lutin (id, mot_de_passe) VALUES (:id, :mdp)";
        $inserer = oci_parse($conn, $sql);
        oci_bind_by_name($inserer, ':id', $ids);
        oci_bind_by_name($inserer, ':mdp', $mdp_hache);
        if (!$inserer) {
            $e = oci_error($inserer);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        oci_execute($inserer);
    }
?>
