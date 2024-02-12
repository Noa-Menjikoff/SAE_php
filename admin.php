

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<?php 
require_once 'BD/Database.php';
require "Classes/autoloader.php";
autoloader::register();
require 'header.php';

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);

if($_SERVER['REQUEST_METHOD']=== "POST"){
    if (isset($_POST['supprimer_artiste'])) {
        $artiste_id = $_POST['artiste_id'];
        $db-> deleteArtiste($artiste_id);
    }
    if (isset($_POST['supprimer_album'])) {
        $album_id = $_POST['album_id'];
        $db-> deleteAlbum($album_id);
    }
}

$artistes = $db->getArtistes();
$albums = $db->getAlbums();

?>
<main>
    <h1 class="titre-page">Admin</h1>

    <div id="base">
        <h2>Tous les artistes</h2>

        <button class="buttonAdd" onclick="window.location.href='ajouter_artiste.php'">Ajouter un artiste</button>
        <div class="grid-container">

            <?php foreach ($artistes as $artiste) {
                $artiseC = new Artiste($artiste["id"], $artiste["prenom"], $artiste["description"], $artiste["photo"]);
                $artiseC->afficherArtisteAdmin();
            } ?>
        </div>

    <h2>Les Albums</h2>

    <button class="buttonAdd" onclick="window.location.href='ajouter_album.php'">Ajouter un Album</button>

    <div class="grid-container">
        <?php foreach ($albums as $album) { 
            $albumC = new Album($album["id"], $album["nom"], $album["date_sortie"], $album["description"], $album["artiste_id"], $album["img"]);
            $albumC->afficherAlbumAdmin();
         } ?>
    </div>
</main>




