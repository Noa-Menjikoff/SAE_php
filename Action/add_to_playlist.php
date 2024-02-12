<?php
session_start();
if (isset($_POST['playlist_id'])) {
    $chansonId = $_POST['chanson_id'];
    $albumId = $_POST['album_id'];
    $playlistId = $_POST['playlist_id'];

    require_once '../BD/Database.php';
    $dbPath ='../BD/sae.sqlite3';
    $db = new Database($dbPath);

    // Call your function to add the chanson to the playlist
    $db->ajouterChansonPlaylist($playlistId, $chansonId);

    // Redirect back to the album detail page or wherever you want
    header("Location: localhost:8000/detail_album.php?id=" . $albumId);
    exit();
}
?>
