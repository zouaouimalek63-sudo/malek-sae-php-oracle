<?php
session_start();
if (!isset($_SESSION['ID_LUTIN']) || empty($_SESSION['ID_LUTIN'])) {
    header("location:connexion.php");
    exit();
}
?>
