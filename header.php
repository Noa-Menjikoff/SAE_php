<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_unset();

    session_destroy();
    
    header('Location: index.php');
    exit();
}


echo '<header>';

    echo '<nav>';
        echo '<ul>';
            echo '<li><a href="accueil.php">Accueil</a></li>';
            echo '<li><a href="artistes.php">Artistes</a></li>';
            echo '<li><a href="">Album</a></li>';
            if($_SESSION['user']===NULL)
                echo '<li><a href="login.php">Connexion</a></li>';
            else
                echo '<li><a href="?action=deconnexion">Déconnexion</a></li>';

        echo '</ul>';
    echo '</nav>';

    if ($_SESSION['user']['image_profil']===NULL){
        echo '<a href="profil.php"><img src="IMG/default.png" alt="user"></a>';
    }   
    else{
        $imageData = $_SESSION['user']['image_profil'];
        echo '<a href="profil.php"><img src="data:image;base64,' . $imageData . '" alt="user"></a>';
    
    }

echo '</header>';


?>