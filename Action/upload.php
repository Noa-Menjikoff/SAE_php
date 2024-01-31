<?php
// upload.php
require_once '../BD/Database.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['user']['username'];
    $table = $_POST['table']; // Récupérer la valeur de l'input

    $imageData = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
    $dbPath ='../BD/sae.sqlite3'; 
    $database = new Database($dbPath);

    if ($table == 'Artistes') {
        $id = $_POST['id'];
        $result = $database->updateArtisteImage($id, $imageData);
    } else if ($table == 'Utilisateurs') {
        $result = $database->updateProfileImage($username, $imageData);
    } elseif ($table == 'Albums') {
        $id = $_POST['id'];
        $result = $database->updateAlbumImage($id, $imageData);
    }
        

        if ($result > 0) {
            echo "<script>alert('L\'image a été téléchargée et mise à jour avec succès dans la base de données.');</script>";
            $_SESSION['user']['image_profil'] = $imageData;
            echo "<script>setTimeout(function(){ window.location.href = '../profil.php'; }, 100);</script>";
        } else {
            echo "<script>alert('Erreur lors de la mise à jour de l\'image dans la base de données.');</script>";
            // Vous pouvez également rediriger vers la page profil en cas d'erreur si nécessaire
            // echo "<script>setTimeout(function(){ window.location.href = 'profil.php'; }, 1000);</script>";
        }
        

        $database->closeConnection();
    
} else {
    echo "Requête non autorisée.";
}
?>
