
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/detail_artiste.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<?php 

require_once 'Classes/autoloader.php';
autoloader::register();
require_once 'BD/Database.php';
$id = $_GET['id'];

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);
require 'header.php';

$artiste = $db->getArtisteById($id);
$albums = $db->getAlbumsByArtistId($id);
if (isset($_SESSION['user_id'])){
    $estAbonne = $db->estAbonne($_SESSION['user_id'], $id);
}
else{
    $estAbonne = FALSE;
}
$chansonsArtiste = $db->getChansonsArtiste($id);



// Initialise audioEnCoursDeLecture à null si elle n'existe pas déjà
if (!isset($_SESSION['audioEnCoursDeLecture'])) {
    $_SESSION['audioEnCoursDeLecture'] = null;
}

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
            <form action="Action/note.php" method="POST" >
                <input type="hidden" name="id_artiste" value="<?php echo $id; ?>">
                <input type="number" name="note" min="0" max="5">
                <input type="submit" value="Noter">
            </form>

        </div>
        <div class="chansons">
        <?php 
                for ($i = 0; $i < count($chansonsArtiste); $i++) {
                    $chanson = $chansonsArtiste[$i];
                    echo '<form action="Action/add_to_playlist.php" method="post">';
                    echo '<div class="chanson">';
                    echo '<p>'.$chanson['id'].'</p>';
                    echo '<p>'.$chanson['nom'].'</p>';
                    echo '<p>'.$chanson['duree'].' min</p>';
                    echo '</div>';
                    echo '<input type="hidden" name="chanson_id" value="'.$chanson['id'].'">';
                    echo '<input type="hidden" name="album_id" value="'.$chanson['album_id'].'">';
                    echo '<p class="add-to-playlist-btn" onclick="showPlaylists('.$i.')">Ajouter</p>';
                    echo '<audio id="audio'.$i.'">';
                    echo '   <source src="'.$chanson['son'].'" type="audio/mpeg">';
                    echo '</audio>';
                    echo '<p onclick="playAudio('.$i.')">Play</p>';
                    echo '<progress id="progressBar' . $i . '" max="100" value="0"></progress>';
                    echo '<div class="playlist-dropdown">';
                                
                                $playlists = $db->getPlaylists($_SESSION['user_id']); 
                                foreach ($playlists as $playlist) {
                                echo '<button type="submit" class="add-to-playlist-btn" value="'.$playlist['id'].'" name="playlist_id">'.$playlist['nom'].'</button>';
                                }
                                
                            echo '</div>';
                        echo '</div>';

                    echo '</form>';


                    // Remove the closing PHP tag
                    // echo '<svg width="151px" height="151px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M21.4086 9.35258C23.5305 10.5065 23.5305 13.4935 21.4086 14.6474L8.59662 21.6145C6.53435 22.736 4 21.2763 4 18.9671L4 5.0329C4 2.72368 6.53435 1.26402 8.59661 2.38548L21.4086 9.35258Z" fill="#1C274C"></path> </g></svg>';
                    
                }
        ?>
        </div>
</div>
<script>
    function showPlaylists($i) {
        var playlistDropdowns = document.getElementsByClassName("playlist-dropdown");
        playlistDropdowns[$i].classList.toggle("block");
        
    }
    var audioEnCoursDeLecture = "<?php echo $_SESSION['audioEnCoursDeLecture']; ?>";

    function playAudio(index) {
        var audioElement = document.querySelector('#audio' + index);
        var progressBar = document.querySelector('#progressBar' + index);

        audioElement.ontimeupdate = function () {
            // Mettre à jour la barre de progression en fonction de la position actuelle du son
            var progressValue = (audioElement.currentTime / audioElement.duration) * 100;
            progressBar.value = progressValue;
        };

        // Si un son est déjà en cours de lecture ou en pause, l'arrêter complètement
        if (audioEnCoursDeLecture && audioEnCoursDeLecture !== audioElement.id) {
            var previousAudio = document.getElementById(audioEnCoursDeLecture);
            if (previousAudio) {
                previousAudio.currentTime = 0; // Remettre le curseur au début
                previousAudio.pause();
            }
        }

        if (audioElement.paused) {

            audioElement.play();
            audioEnCoursDeLecture = audioElement.id;

            // Met à jour la variable de session PHP
            <?php $_SESSION['audioEnCoursDeLecture'] = "' + audioEnCoursDeLecture + '"; ?>;
        } else {
            audioElement.pause();
        }
    }
    
    


</script>
<h1>Les albums</h1>

<div class="albums">
    
    <?php  
    
            for ($i = 1; $i <= min(4, count($albums)); $i++) {
                $albumClass = new Album($albums[$i-1]['id'],$albums[$i-1]['nom'],$albums[$i-1]['date_sortie'],$albums[$i-1]['description'],$albums[$i-1]['artiste_id'],$albums[$i-1]['img']);
                $albumClass->afficherAlbum();
            }
        

    ?>

</div>
</main>

