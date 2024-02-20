

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/playlist.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<?php 
session_start();
require_once 'BD/Database.php';
require_once 'Classes/autoloader.php';
autoloader::register();

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);
require 'header.php';
$idPlaylist = $_GET['id'];
$chansons = $db->getChansonsPlaylist($idPlaylist);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idChanson = $_POST['chanson_id'];
    $db->supprimerChansonPlaylist($idPlaylist, $idChanson);
    header('Location: playlist.php?id='.$idPlaylist);

}
?>

<main>
    <div class="chansons">
        <?php
            foreach ($chansons as $chanson) {
                $chansonClass = new Chanson($chanson['id'],$chanson['nom'],$chanson['duree'],$chanson['album_id']);
                echo '<div class="chanson">';
                $chansonClass->afficherChanson();
                echo '<form method="POST" action="playlist.php?id='.$idPlaylist.'">';
                echo '<input type="hidden" name="chanson_id" value="'.$chanson['id'].'">';
                echo '<button type="submit">Supprimer</button>';
                echo '</form>';
                echo '</div>';
            }
        ?>
    </div>
</main>