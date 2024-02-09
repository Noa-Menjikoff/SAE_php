
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
if (!isset($_SESSION['audioEnCoursDeLecture'])) {
  $_SESSION['audioEnCoursDeLecture'] = null;
}
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
      <!-- Add this form inside your foreach loop for displaying chansons -->
      <?php for ($i = 0; $i < count($chansons); $i++) { $chanson = $chansons[$i];?>
        <form action="Action/add_to_playlist.php" method="post">
            <div class="chanson">
                <p><?php echo $chanson['id']; ?></p>
                <p><?php echo $chanson['nom']; ?></p>
                <p><?php echo $chanson['duree']; ?> min</p>
                <input type="hidden" name="chanson_id" value="<?php echo $chanson['id']; ?>">
                <input type="hidden" name="album_id" value="<?php echo $chanson['album_id']; ?>">
                <p class="add-to-playlist-btn" onclick="showPlaylists(<?php echo $i ?>)">Ajouter</p>
                <?php 
                  echo '<audio id="audio'.$i.'">';
                  echo '   <source src="'.$chanson['son'].'" type="audio/mpeg">';
                  echo '</audio>';
                  echo '<p onclick="playAudio('.$i.')">Play</p>';
                  echo '<progress id="progressBar' . $i . '" max="100" value="0"></progress>';
                  ?>
                <div class="playlist-dropdown">
                    <?php
                    $playlists = $db->getPlaylists($_SESSION['user_id']); 
                    foreach ($playlists as $playlist) {
                      echo '<button type="submit" class="add-to-playlist-btn" value="'.$playlist['id'].'" name="playlist_id">'.$playlist['nom'].'</button>';
                    }
                    ?>
                </div>
            </div>
        </form>
      <?php } ?>
      
</div>

<script>
    function showPlaylists(index) {
        var playlistDropdowns = document.getElementsByClassName("playlist-dropdown");
        playlistDropdowns[index].classList.toggle("block");
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

</main>

