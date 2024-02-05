
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
$estAbonne = $db->estAbonne($_SESSION['user_id'], $id);
$chansonsArtiste = $db->getChansonsArtiste($id);
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
            <?php
                if (!$estAbonne){
                    echo'<form action="Action/abonner_artiste.php" method="post">';
                    echo '<input type="hidden" name="id_artiste" value="'. $id. '">';
                    echo '<input type="submit" value="S abonner">';
                    echo '</form>';
                }
                else{
                    echo'<form action="Action/desabonner_artiste.php" method="post">';
                    echo '<input type="hidden" name="id_artiste" value="'. $id. '">';
                    echo '<input type="submit" value="Désabonner">';
                    echo '</form>';
                }
            ?>

        </div>
        <div class="chansons">
        <?php 
                foreach ($chansonsArtiste as $chanson) {
                    echo '<div class="chanson">';
                    echo '<p>'.$chanson['id'].'</p>';
                    echo '<p>'.$chanson['nom'].'</p>';
                    echo '<p>'.$chanson['duree'].' min</p>';
                    echo '</div>';
                }
        ?>
        </div>
</div>
<h1>Les albums</h1>

<div class="albums">
    
    <?php  
    
            for ($i = 1; $i <= min(4, count($albums)); $i++) {
                echo '<a href="detail_album.php?id='.$albums[$i-1]["id"].'">';            
    
                
                echo '<div class="album">';
                if ($albums[$i-1]['img']===NULL){
                    echo '<img src="IMG/default.png" alt="user">';
                }   
                else{
                    $imageData = $albums[$i-1]['img'];
                    echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
                }
                echo '<div class="infos">';
                    echo '<h3 class="album-nom">' . substr($albums[$i-1]['nom'], 0, 15);
                    if (strlen($albums[$i-1]['nom']) > 15) {
                        echo '...';
                    }
                    echo '</h3>';
                    echo '<p>Date: ' . $albums[$i-1]['date_sortie'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</a>';
            }
        

    ?>

</div>
</main>

