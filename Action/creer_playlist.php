<?php

// Include the Database.php file
require_once '../BD/Database.php';
$dbPath = '../BD/sae.sqlite3'; 
$db = new Database($dbPath);
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the playlist name from the POST data
    $playlistName = $_POST['playlist_name'];

    // Add the playlist to the database
    if (isset($_SESSION['user'])){
        $db->ajouterPlaylist($_SESSION['user_id'],$playlistName);
    }
    else{
        echo '<script>alert("Vous devez avoir un compte pour créer une playlist.");</script>';
    }

    // Redirect to a success page
    header('Location: ../index.php');
    exit;
    }
    ?>
