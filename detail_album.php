
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/detail_album.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<?php 
require_once 'BD/Database.php';
$id = $_GET['id'];

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);
require 'header.php';

$album = $db->getAlbumById($id);
$chansons = $db->getChansonsAlbum($id);

?>

<main>
<div class="contenu-detail-album">
      <div class="image-album">
        <?php   
            if ($album['img']===NULL){
                echo '<img src="IMG/default.png" alt="user">';
            }   
            else{
                $imageData = $album['img'];
                echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
            }
        ?>
      </div>
      <div class="texte-artiste">
        <h1><?php echo $album['nom']; ?></h1>
      </div>
      <div class="chansons">
        <?php
            foreach ($chansons as $chanson) {
                echo '<div class="chanson">';
                echo '<p>'.$chanson['id'].'</p>';
                echo '<p>'.$chanson['nom'].'</p>';
                echo '<p>'.$chanson['duree'].' min</p>';
                echo '</div>';
            }
        ?>
      </div>
</div>

</main>

