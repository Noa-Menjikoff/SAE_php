<?php
    require_once 'BD/Database.php';

    $dbPath ='BD/sae.sqlite3'; 
    $db = new Database($dbPath);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/accueil.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#search-bar").on("input", function () {
                var searchText = $(this).val();

                // Effectuer une requête Ajax pour récupérer les résultats
                $.ajax({
                    type: "POST",
                    url: "Action/search.php", // Créez un fichier search.php pour gérer la recherche
                    data: { search: searchText },
                    success: function (response) {
                        // Mettez à jour la section des résultats avec les données de recherche
                        $("#search-results").html(response);
                        $("#base").addClass("cacher");
                    }
                });
            });
        });
    </script>
</head>
<body>
    <?php 
        $artistes = $db->getArtistes();
        $albums = $db->getAlbums();
        $musics = $db->getMusics();
        require 'header.php';
    ?>
<main>
        <div class="search-bar">
            <input type="text" id="search-bar" placeholder="Rechercher...">
        </div>

        <div id="search-results"></div>
        <div id="base">
            <h2>Découvrez des artistes</h2>
            <div class="grid-container">

                <?php for ($i = 1; $i <= min(6, count($artistes)); $i++) { ?>
                    <div class="artiste">
                        <?php             
                            if ($artistes[$i-1]['photo']===NULL){
                                echo '<img src="IMG/default.png" alt="user">';
                            }   
                            else{
                                $imageData = $artistes[$i-1]['photo'];
                                echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
                            }
                        ?>
                        <div class="contenu">
                            
                            <?php 
                            echo '<a href="detail_artiste.php?id='.$artistes[$i-1]['id'].'";>'.substr($artistes[$i-1]['prenom'], 0, 15).'</a>' ;
                            if (strlen($artistes[$i-1]['prenom']) > 15) {
                                echo '...';
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <h2>Découvrez des albums</h2>
            <div class="grid-container">
                <?php for ($i = 1; $i <= min(6, count($albums)); $i++) { ?>
                    <div class="album">
                        <?php             
                            if ($albums[$i-1]['img']===NULL){
                                echo '<img src="IMG/default.png" alt="user">';
                            }   
                            else{
                                $imageData = $albums[$i-1]['img'];
                                echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
                            }
                        ?>
                        <div class="contenu">
                        <?php 
                                echo '<a href="detail_album.php?id='.$albums[$i-1]['id'].'";>'.substr($albums[$i-1]['nom'], 0, 15).'</a>' ;
                                if (strlen($albums[$i-1]['prenom']) > 15) {
                                    echo '...';
                                }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <h2>Découvrez des musiques</h2>
            <div class="grid-container">
                <?php foreach ($musics as $music) { ?>
                    <div class="music">
                        <?php             
        
                        ?>
                        <div class="contenu">
                        <?php 
                                echo '<p>'.$music['nom'].'</p>' ;
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>                    
            
        </div>
    

            

    </main>
</body>
</html>
