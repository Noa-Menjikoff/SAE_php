<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/ajouter.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>


<?php
require_once 'BD/Database.php';

$dbPath = 'BD/sae.sqlite3';
$db = new Database($dbPath);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['ajouter_artiste'])) {
        $prenom = $_POST['prenom'];
        $description = $_POST['description'];

        // Gestion de l'ajout de la photo (exemple simple, à adapter selon vos besoins)
        $photo = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $photo = file_get_contents($_FILES['photo']['tmp_name']);
        }

        // Appel de la fonction pour ajouter l'artiste
        $db->ajouterArtiste($prenom, $description, $photo);
    }
}

require 'header.php';
?>

<main>
    <h1>Ajouter un Artiste</h1>

    <form action="ajouter_artiste.php" method="post" enctype="multipart/form-data">
        <label for="prenom">Prénom de l'artiste :</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="photo">Photo de l'artiste :</label>
        <input type="file" id="photo" name="photo" accept="image/*">

        <button type="submit" name="ajouter_artiste">Ajouter l'artiste</button>
    </form>
</main>
