<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/modifier.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Modifier un Album</title>
</head>
<?php
$id = $_GET['id'];

require_once 'BD/Database.php';

$dbPath ='BD/sae.sqlite3'; 
$db = new Database($dbPath);
require 'header.php';

$album = $db->getAlbumById($id);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nom = $_POST['nom'];
    $date_sortie = $_POST['date_sortie'];
    $description = $_POST['description'];

    $image = $album['img'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = base64_encode(file_get_contents($_FILES['image']['tmp_name']));
    }

    $db->modifierAlbum($id, $nom, $date_sortie, $description, $image);

    if (isset($_POST['genres'])) {
        $genresSelectionnes = $_POST['genres'];
        $db->modifierGenresAlbum($id, $genresSelectionnes);
    }

    if (isset($_POST['new_chanson_nom'])) {
        $nom = $_POST['new_chanson_nom'];
        $duree = $_POST['new_chanson_duree'];
        $description = $_POST['new_chanson_description'];
        $album_id = $id; 

        $db->ajouterChansonAlbum($nom, $duree, $description, $album_id);
    }

    if (isset($_POST['delete_chansons'])) {
        $chansonsToDelete = $_POST['delete_chansons'];
        foreach ($chansonsToDelete as $chansonID) {
            $db->deleteChanson($chansonID);
        }
    }

}

$album = $db->getAlbumById($id);

$genres = $db->getGenres();
$genresAlbum = $db->getGenresAlbum($id);

$chansons = $db->getChansonsAlbum($id);

?>

<main>
    <div class="albums-container">
        <div class="album-card">
            <span class="album-name"><?php echo $album['nom']; ?></span>
        </div>
    </div>
    <form action="modifier_album.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <label for="nom">Nom de l'album :</label>
        <input type="text" id="nom" name="nom" value="<?php echo $album['nom']; ?>" required>

        <label for="date_sortie">Date de sortie :</label>
        <input type="date" id="date_sortie" name="date_sortie" value="<?php echo $album['date_sortie']; ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" rows="4" required><?php echo $album['description']; ?></textarea>

        <label for="image">Image de l'album :</label>
        <input type="file" id="image" name="image" accept="image/*">

        <fieldset>
            <legend>Genres :</legend>
            <?php foreach ($genres as $genre) : ?>
                <div class="genre-checkbox">
                    <input type="checkbox" id="genre_<?php echo $genre['id']; ?>" name="genres[]" value="<?php echo $genre['id']; ?>" <?php echo (in_array($genre['id'], $genresAlbum)) ? 'checked' : ''; ?>>
                    <label for="genre_<?php echo $genre['id']; ?>"><?php echo $genre['nom']; ?></label>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <fieldset>
            <legend>Chansons :</legend>
                <button type="button" id="toggleChansonBlock">Ajouter une chanson</button>
                <!-- <div class="chanson-item" id="chansonBlock" style="display: none;">
                    <input type="text" name="new_chanson_nom" value="" placeholder="Nom de chanson" required>
                    <input type="text" name="new_chanson_duree" value="" placeholder="Duree de chanson" required>
                    <textarea name="new_chanson_description" rows="4" placeholder="Description"></textarea>
                </div> -->
            <div id="chansons-container">
            <?php foreach ($chansons as $chanson) : ?>
                <div class="chanson-item">
                    <input type="text" name="chansons[<?php echo $chanson['id']; ?>][nom]" value="<?php echo $chanson['nom']; ?>">
                    <input type="text" name="chansons[<?php echo $chanson['id']; ?>][duree]" value="<?php echo $chanson['duree']; ?>">
                    <textarea name="chansons_<?php echo $chanson['id']; ?>" rows="4"> <?php echo $chanson['description']; ?></textarea>
                    <button type="button" class="remove-chanson" data-chanson-id="<?php echo $chanson['id']; ?>">Supprimer</button>
                </div>
            <?php endforeach; ?>
            </div>
        </fieldset>

        <input type="submit" value="Sauvegarder les modifications">
    </form>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
        var toggleButton = document.getElementById('toggleChansonBlock');

        toggleButton.addEventListener('click', function () {
            var existingChansonBlock = document.getElementById('chansonBlock');

            if (!existingChansonBlock) {
                var chansonBlock = document.createElement('div');
                chansonBlock.className = 'chanson-item';
                chansonBlock.id = 'chansonBlock';

                var inputNom = document.createElement('input');
                inputNom.type = 'text';
                inputNom.name = 'new_chanson_nom';
                inputNom.placeholder = 'Nom de chanson';
                inputNom.required = true;

                var inputDuree = document.createElement('input');
                inputDuree.type = 'text';
                inputDuree.name = 'new_chanson_duree';
                inputDuree.placeholder = 'Duree de chanson';
                inputDuree.required = true;

                var textareaDescription = document.createElement('textarea');
                textareaDescription.name = 'new_chanson_description';
                textareaDescription.rows = '4';
                textareaDescription.placeholder = 'Description';

                chansonBlock.appendChild(inputNom);
                chansonBlock.appendChild(inputDuree);
                chansonBlock.appendChild(textareaDescription);

                chansonsContainer = document.getElementById('chansons-container')

                chansonsContainer.insertBefore(chansonBlock, chansonsContainer.firstChild);

                toggleButton.innerHTML = 'Annuler';
            } else {
                existingChansonBlock.remove();

                toggleButton.innerHTML = 'Ajouter une chanson';
            }
        });


            var removeButtons = document.getElementsByClassName('remove-chanson');
            for (var i = 0; i < removeButtons.length; i++) {
                removeButtons[i].addEventListener('click', function () {
                    var chansonID = this.getAttribute('data-chanson-id');
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette chanson ?')) {
                        
                        var hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'delete_chansons[]';
                        hiddenInput.value = chansonID;
                        document.getElementById('chansons-container').appendChild(hiddenInput);

                        this.parentNode.remove();
                    }
                });
            }
        });
    </script>
</main>
