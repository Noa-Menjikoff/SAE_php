<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/modifier.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Modifier un Artiste</title>
</head>

<?php
$id = $_GET['id'];

require_once 'BD/Database.php';

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);
require 'header.php';

$artiste = $db->getArtisteById($id);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $prenom = $_POST['prenom'];
    $description = $_POST['description'];

    $photo = $artiste['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo = base64_encode(file_get_contents($_FILES['photo']['tmp_name']));
    }

    $db->modifierArtiste($id, $prenom, $description, $photo);

    
    if (isset($_POST['genres'])) {
        $genresSelectionnes = $_POST['genres'];
        $db->modifierGenresArtiste($id, $genresSelectionnes);
    }
}

$artiste = $db->getArtisteById($id);

$genres = $db->getGenres();

$genresArtiste = $db->getGenresArtiste($id);
?>

<main>
    <div class="artistes-container">
        <div class="artiste-card">
            <span class="artiste-name"><?php echo $artiste['prenom']; ?></span>
        </div>
    </div>
    <form action="modifier_artiste.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <label for="prenom">Prénom de l'artiste :</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo $artiste['prenom']; ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" rows="4" required><?php echo $artiste['description']; ?></textarea>

        <label for="photo">Photo de l'artiste :</label>
        <input type="file" id="photo" name="photo" accept="image/*">

        <fieldset>
            <legend>Genres :</legend>
            <?php foreach ($genres as $genre) : ?>
                <div class="genre-checkbox">
                    <input type="checkbox" id="genre_<?php echo $genre['id']; ?>" name="genres[]" value="<?php echo $genre['id']; ?>" <?php echo (in_array($genre['id'], $genresArtiste)) ? 'checked' : ''; ?>>
                    <label for="genre_<?php echo $genre['id']; ?>"><?php echo $genre['nom']; ?></label>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <input type="submit" value="Modifier l'artiste">
    </form>
</main>