
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/detail_artiste.css">
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

$artiste = $db->getArtisteById($id);
$albums = $db->getAlbumsByArtistId($id);
?>

<main>
<div class="contenu-detail-artiste">
      <div class="image-artiste">
        <?php   
            if ($artiste['photo']===NULL){
                echo '<img src="IMG/default.png" alt="user">';
            }   
            else{
                $imageData = $artiste['photo'];
                echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
            }
        ?>
      </div>
      <div class="texte-artiste">
        <h1><?php echo $artiste['prenom']; ?></h1>
        <p class="description-artiste">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores, non explicabo. Recusandae fugiat eaque excepturi, aliquid enim repellat! Quaerat ipsum eius consequatur fuga illum tempore nobis vel tempora delectus nostrum?</p>

      </div>
</div>
<h1>Les albums</h1>

<div class="albums">
    
    <?php  
        foreach($albums as $album){
            echo '<a href="detail_album.php?id='.$album["id"].'">';
            echo '<div class="album">';
            echo '<h2>' . $album['nom'] . '</h2>';
            echo '<p>Date: ' . $album['date_sortie'] . '</p>';
            echo '<p>Description: ' . $album['description'] . '</p>';
            echo '</div>';
            echo '</a>';
        }
    ?>

</div>
</main>

