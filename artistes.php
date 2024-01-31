<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/artistes.css">
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


<main>
      <h2 class="titre-page">Découvrez tout les artistes</h2>
      <div class="liste-artiste">
      <?php foreach ($artistes as $artiste) { ?>
            <div class="artiste">
            <?php             
                if ($artiste['photo']===NULL){
                    echo '<img src="IMG/default.png" alt="user">';
                }   
                else{
                    $imageData = $artiste['photo'];
                    echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
                }
            ?>
              <div class="contenu">
                <a href="detail_artiste.php?id=<?php echo $artiste['id']; ?>"><h3 class="test-arrow"><span><?php echo $artiste['prenom']; ?></span></h3></a>
                <p><?php echo $artiste['prenom']; ?></p>
              </div>
            </div>
            <?php } ?>
      </div>
</main>






