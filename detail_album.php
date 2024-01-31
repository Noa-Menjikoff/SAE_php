
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/detail_artiste.css">
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

?>

<main>
<div class="contenu-detail-artiste">
      <div class="image-artiste">
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
        <p class="description-artiste">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores, non explicabo. Recusandae fugiat eaque excepturi, aliquid enim repellat! Quaerat ipsum eius consequatur fuga illum tempore nobis vel tempora delectus nostrum?</p>

      </div>
</div>

</main>

