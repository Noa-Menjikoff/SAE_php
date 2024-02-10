<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/ajouter.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
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


    if (isset($_POST['new_chansons'])) {
        $chansonsData = $_POST['new_chansons'];
        $cpt = 1;
        foreach ($chansonsData as $chansonData) {
            $nomChanson = $chansonData['nom'];
            $dureeChanson = $chansonData['duree'];
            $descriptionChanson = isset($chansonData['description']) ? $chansonData['description'] : '';

            $db->ajouterChansonAlbum($nomChanson, $dureeChanson, $descriptionChanson, $id);
        }
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
        <div class="InfoPersoGenres" >
            <div class="InfoPerso" >
                <div id="NameBox">
                    <label for="nom">Nom de l'album :</label>
                    <input type="text" id="nom" name="nom" value="<?php echo $album['nom']; ?>" required>
                </div>

                <div id="DateBox">
                    <label for="date_sortie">Date de sortie :</label>
                    <input type="date" id="date_sortie" name="date_sortie" value="<?php echo $album['date_sortie']; ?>" required>
                </div>

                <div id="DescriptionBox">
                    <label for="description">Description :</label>
                    <textarea id="description" name="description" rows="4" required><?php echo $album['description']; ?></textarea>
                </div>

                <div id="ImageBox">
                    <label for="image">Image de l'album :</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            <fieldset>
                <legend>Genres :</legend>
                <ul class="ks-cboxtags">
                <?php foreach ($genres as $genre) : ?>
                    <li>
                        <input type="checkbox" id="checkbox<?php echo $genre['id']; ?>" name="genres[]" value="<?php echo $genre['id']; ?>" <?php echo (in_array($genre['id'], $genresAlbum)) ? 'checked' : ''; ?>>
                        <label for="checkbox<?php echo $genre['id']; ?>"><?php echo $genre['nom']; ?></label>
                    </li>
                <?php endforeach; ?>
            </fieldset>
        </div>

        <fieldset>
            <legend>Chansons :</legend>
                <button type="button" id="toggleChansonBlock">Ajouter une chanson</button>
            <div id="chansons-container">
            <?php foreach ($chansons as $chanson) : ?>
                <div class="chanson-item">
                    <input type="text" name="chanson[<?php echo $chanson['id']; ?>][nom]" value="<?php echo $chanson['nom']; ?>">
                    <input type="text" name="chanson[<?php echo $chanson['id']; ?>][duree]" value="<?php echo $chanson['duree']; ?>">
                    <textarea name="chanson_<?php echo $chanson['id']; ?>" rows="4"> <?php echo $chanson['description']; ?></textarea>
                    <button type="button" class="remove-chanson Button" data-chanson-id="<?php echo $chanson['id']; ?>"><img class="imgButton" src="IMG/redCrossV2.png" alt="Supprimer"></button>
                </div>
            <?php endforeach; ?>
            </div>
        </fieldset>

        <input class="buttonAdd" type="submit" value="Sauvegarder les modifications">
    </form>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
        
        var chansonsContainer = document.getElementById('chansons-container');

        let cpt = 1

        function createChansonBlock() {
                var chansonBlock = document.createElement('div');
                chansonBlock.className = 'chanson-item';

                var inputNom = document.createElement('input');
                inputNom.type = 'text';
                inputNom.name = 'new_chansons['+cpt+'][nom]';
                inputNom.placeholder = 'Nom de chanson';
                inputNom.required = true;

                var inputDuree = document.createElement('input');
                inputDuree.type = 'text';
                inputDuree.name = 'new_chansons['+cpt+'][duree]';
                inputDuree.placeholder = 'Duree de chanson';
                inputDuree.required = true;

                var textareaDescription = document.createElement('textarea');
                textareaDescription.name = 'new_chansons['+cpt+'][description]';
                textareaDescription.rows = '4';
                textareaDescription.placeholder = 'Description';

                cpt += 1;

                

                var cancelButton = document.createElement('button');
                cancelButton.type = 'button';
                cancelButton.className = 'cancel-chanson Button';
                cancelButton.addEventListener('click', function () {
                    chansonBlock.remove();
                });

                var imgButtonAnnuler = document.createElement('img');
                imgButtonAnnuler.className = 'imgButton';
                imgButtonAnnuler.src = 'IMG/minus.png';
                imgButtonAnnuler.alt = 'Annuler';

                cancelButton.appendChild(imgButtonAnnuler);

                chansonBlock.appendChild(inputNom);
                chansonBlock.appendChild(inputDuree);
                chansonBlock.appendChild(textareaDescription);
                chansonBlock.appendChild(cancelButton);

                return chansonBlock;
            }

            var toggleButton = document.getElementById('toggleChansonBlock');
            toggleButton.addEventListener('click', function () {
                var chansonBlock = createChansonBlock();
                chansonBlock.id = 'chansonBlock';
                chansonsContainer.insertBefore(chansonBlock, chansonsContainer.firstChild);
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
