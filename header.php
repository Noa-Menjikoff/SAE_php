<?php
// session_start();

require_once 'BD/Database.php';
$dbPath = 'BD/sae.sqlite3';
$db = new Database($dbPath);
if (isset($_SESSION['user'])){
    $playlist = $db->getPlaylists($_SESSION['user_id']);
}
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_unset();

    session_destroy();
    
    header('Location: index.php');
    exit();
}


echo '<header>';


    echo '<nav>';
    if (!isset($_SESSION['user']['image_profil']) ){
        echo '<a href="profil.php"><img src="IMG/default.png" alt="user"></a>';
    }else{
        $imageData = $_SESSION['user']['image_profil'];
        echo '<a href="profil.php"><img src="data:image;base64,' . $imageData . '" alt="user"></a>';
    }
        echo '<ul>';
            echo '<li><a href="accueil.php"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>Accueil</a></li>';
            echo '<li><a href="artistes.php">Artistes</a></li>';
            echo '<li><a href="albums.php">Album</a></li>';
            if (!isset($_SESSION['user'])){
                echo '<li><a href="login.php">Connexion</a></li>';
            }
            else{
                echo '<li><a href="?action=deconnexion">Déconnexion</a></li>';
            }
            if (isset($_SESSION['adm'])){
                if($_SESSION['adm']===TRUE)
                    echo '<li><a href="admin.php">Admin</a></li>';
            }

        echo '</ul>';
        echo '<div class="bibli">';
        echo '<h2>Bibliothèque</h2>';
        echo '<form action="Action/creer_playlist.php" method="POST">';
            echo '<input type="text" name="playlist_name" placeholder="Playlist Name">';
            echo '<button type="submit">Create Playlist</button>';
        echo '</form>';
        if (isset($playlist)){
            echo '<ul class="playlists">';
            foreach ($playlist as $p){
                echo '<li><a href="playlist.php?id='.$p['id'].'">'.$p['nom'].'</a></li>';
            }
            echo '</ul>';
        }

        
        echo '</div>';
    echo '</nav>';





echo '</header>';


?>