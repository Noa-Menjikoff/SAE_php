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
require 'header.php';

$artistes = $db->getArtistes();
?>

<div class="artistes-container">
    <?php foreach ($artistes as $artiste) { ?>
        <div class="artiste-card">
            <span class="artiste-name"><?php echo $artiste['prenom']; ?></span>
            <button class="btn-modify" onclick="window.location.href='modifier_artiste.php?id=<?php echo $artiste['id']; ?>'">Modifier</button>
            <button class="btn-delete" onclick="deleteArtiste(<?php echo $artiste['id']; ?>)">Supprimer</button>
        </div>
    <?php } ?>
</div>

