<?php
session_start();
require_once '../BD/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_artiste'])) {
        $idArtiste = $_POST['id_artiste'];

        $dbPath ='../BD/sae.sqlite3'; 
        $db = new Database($dbPath);

        $idUtilisateur = $_SESSION['user_id']; 

        $db->enleverAbonnement($idUtilisateur, $idArtiste);

        $db->closeConnection();

       header("Location: ../detail_artiste.php?id=$idArtiste");
        exit();
    } else {
        echo "ID de l'artiste non spécifié!";
    }
} else {
    header('Location: erreur.php');
    exit();
}
?>
