<?php
// search.php

require_once '../BD/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchText = $_POST['search'];

    $dbPath ='../BD/sae.sqlite3'; 
    $db = new Database($dbPath);

    $artistes = $db->searchArtistes($searchText);
    $albums = $db->searchAlbums($searchText);
    $chansons = $db->searchChansons($searchText);

    echo '<h3>Résultats pour "' . $searchText . '"</h3>';

    echo '<h4>Artistes</h4>';
    echo '<div class="grid-container">';
    foreach ($artistes as $artiste) {
        echo '<div class="artiste"> ';     
                if ($artiste['photo']===NULL){
                    echo '<img src="IMG/default.png" alt="user">';
                }   
                else{
                    $imageData = $artiste['photo'];
                    echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
                }
            
            echo '<div class="contenu">';
                
               
                echo '<a href="detail_artiste.php?id='.$artiste['id'].'";>'.substr($artiste['prenom'], 0, 15).'</a>' ;
                if (strlen($artiste['prenom']) > 15) {
                    echo '...';
                }
                
            echo '</div>';
        echo '</div>';
    }
    echo '</div>';

    echo '<h4>Albums</h4>';
    echo '<div class="grid-container">';
    foreach ($albums as $album) {
        echo '<div class="album">';
                   
            if ($album['img']===NULL){
                echo '<img src="IMG/default.png" alt="user">';
            }   
            else{
                $imageData = $album['img'];
                echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
            }
        
            echo '<div class="contenu">';
            
                    echo '<a href="detail_album.php?id='.$album['id'].'";>'.substr($album['nom'], 0, 15).'</a>' ;
                    if (strlen($album['nom']) > 15) {
                        echo '...';
                    }
                
            echo '</div>';
        echo '</div>';  
    }
    echo '</div>';

    echo '<h4>Chansons</h4>';
    foreach ($chansons as $chanson) {
        echo '<p>' . $chanson['nom'] . '</p>';
    }

    $db->closeConnection();
}
?>
