<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/album.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<?php 
require_once 'BD/Database.php';
require "Classes/autoloader.php";

autoloader::register();

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);
require 'header.php';

$albums = $db->getAlbums();
?>


<main>
      <h2 class="titre-page">Découvrez tout les artistes</h2>
      <div class="liste-album">
      <?php foreach ($albums as $album) {
        $albumC = new Album($album["id"], $album["nom"], $album["date_sortie"], $album["description"], $album["artiste_id"], $album["img"]);
        $albumC->afficherAlbum();
        } ?>
      </div>
</main>


