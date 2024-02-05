<?php
require_once '../BD/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_artiste'])) {
        $idArtiste = $_POST['id_artiste'];

        $dbPath ='../BD/sae.sqlite3'; 
        $db = new Database($dbPath);

        $idUtilisateur = $_SESSION['user_id']; 

        $db->ajouterAbonnement($idUtilisateur, $idArtiste);

        $db->closeConnection();

        echo "Abonnement ajouté avec succès!";
    } else {
        echo "ID de l'artiste non spécifié!";
    }
} else {
    header('Location: erreur.php');
    exit();
}
?>
