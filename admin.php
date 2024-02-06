

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
    if (isset($_POST['supprimer_album'])) {
        $album_id = $_POST['album_id'];
        $db-> deleteAlbum($album_id);
    }
}
require 'header.php';

$artistes = $db->getArtistes();
$albums = $db->getAlbums();

?>
<main>
    <h1>Les Artistes</h1>

    <div class="container">
        <button class="btn-delete" onclick="window.location.href='ajouter_artiste.php'">Ajouter un artiste</button>
        <?php foreach ($artistes as $artiste) { ?>
            <form class="card" action="admin.php" method="post">
                <span class="artiste-name"><?php echo $artiste['prenom']; ?></span>
                
                <input type="hidden" name="artiste_id" value="<?php echo $artiste['id']; ?>">
                <button type="submit" class="btn-delete" name="supprimer_artiste">Supprimer</button>
            </form>
            <button class="btn-modify" onclick="window.location.href='modifier_artiste.php?id=<?php echo $artiste['id']; ?>'">Modifier</button>
        <?php } ?>
    </div>

    <h1>Les Albums</h1>


    <div class="container">
        <button class="btn-delete" onclick="">Ajouter un Album</button>
        <?php foreach ($albums as $album) { ?>
            <form class="card" action="admin.php" method="post">
                <span class="album-name"><?php echo $album['nom']; ?></span>
                
                <input type="hidden" name="album_id" value="<?php echo $album['id']; ?>">
                <button type="submit" class="btn-delete" name="supprimer_album">Supprimer</button>
            </form>
            <button class="btn-modify" onclick="window.location.href='modifier_album.php?id=<?php echo $album['id']; ?>'">Modifier</button>
        <?php } ?>
    </div>
</main>




