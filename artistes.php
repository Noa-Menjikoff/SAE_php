<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/artistes.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<body>

<?php 
require_once 'BD/Database.php';
require "Classes/autoloader.php";

autoloader::register();

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);
require 'header.php';

$artistes = $db->getArtistes();

?>

<main>

    <h2 class="titre-page">Découvrez tous les artistes</h2>
    <div class="liste-artiste">
        <?php foreach ($artistes as $artiste) {
            $artiseC = new Artiste($artiste["id"], $artiste["prenom"], $artiste["description"], $artiste["photo"]);
            $artiseC->afficherArtiste();
        }?>
    </div>
</main>

</body>
</html>
