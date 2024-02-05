<?php

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/admin.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<?php 
require_once 'BD/Database.php';

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);

if($_SERVER['REQUEST_METHOD']=== "POST"){
    if (isset($_POST['supprimer_artiste'])) {
        $artiste_id = $_POST['artiste_id'];
        $db-> deleteArtiste($artiste_id);
    }
}
require 'header.php';

$artistes = $db->getArtistes();
$albums = $db->getAlbums();

?>
<main>
    <h1>Les Artistes</h1>

    <div class="container">
        <button class="btn-delete">Ajouter un artiste</button>
        <?php foreach ($artistes as $artiste) { ?>
            <form class="card" action="admin.php" method="post">
                <span class="artiste-name"><?php echo $artiste['prenom']; ?></span>
                <button class="btn-modify" onclick="window.location.href='modifier_artiste.php?id=<?php echo $artiste['id']; ?>'">Modifier</button>
                <input type="hidden" name="artiste_id" value="<?php echo $artiste['id']; ?>">
                <button type="submit" class="btn-delete" name="supprimer_artiste">Supprimer</button>
            </form>
        <?php } ?>
    </div>

    <h1>Les Albums</h1>


    <div class="container">
        <button class="btn-delete" onclick="">Ajouter un Album</button>
        <?php foreach ($albums as $album) { ?>
            <div class="card">
                <span class="album-name"><?php echo $album['nom']; ?></span>
                <button class="btn-modify" onclick="window.location.href='modifier_album.php?id=<?php echo $album['id']; ?>'">Modifier</button>
                <button class="btn-delete" onclick="">Supprimer</button>
            </div>
        <?php } ?>
    </div>
</main>




