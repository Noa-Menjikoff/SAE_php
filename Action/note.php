<?php
session_start();
require_once '../BD/Database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_artiste = $_POST['id_artiste'];
    $note = $_POST['note'];
    $userId = $_SESSION['user_id'];

    $dbPath ='../BD/sae.sqlite3'; 
    $db = new Database($dbPath);


    $db->ajouterNote($note, $userId, $id_artiste);

    $db->closeConnection();

    header("Location: ../detail_artiste.php?id=$id_artiste");
    exit;
}
?>


