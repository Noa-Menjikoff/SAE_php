
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
    if (isset($_POST['ajouter_album'])) {
        $nom = $_POST['nom'];
        $dateSortie = $_POST['date_sortie'];
        $description = $_POST['description'];
        $artisteId = $_POST['artiste_id'];

        
        $img = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $img = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
        }

        $db->ajouterAlbum($nom, $dateSortie, $description, $artisteId, $img);
    }
}

require 'header.php';
$artistes = $db->getArtistes();
?>

<main>
    <h1>Ajouter un Album</h1>

    <form action="ajouter_album.php" method="post" enctype="multipart/form-data">
        <label for="nom">Nom de l'Album:</label>
        <input type="text" id="nom" name="nom" required>

        <label for="date_sortie">Date de sortie:</label>
        <input type="date" id="date_sortie" name="date_sortie" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="artiste_id">Artiste:</label>
        <select id="artiste_id" name="artiste_id" required>
            <?php foreach ($artistes as $artiste) { ?>
                <option value="<?php echo $artiste['id']; ?>"><?php echo $artiste['prenom']; ?></option>
            <?php } ?>
        </select>

        <label for="image">Image de l'Album:</label>
        <input type="file" id="image" name="image" accept="image/*">

        <input type="submit" name="ajouter_album" value="Ajouter l'Album">
    </form>
</main>
