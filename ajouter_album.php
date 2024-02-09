
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

        $id = $db->ajouterAlbum($nom, $dateSortie, $description, $artisteId, $img);
    }

    if (isset($_POST['genres'])) {
        $genresSelectionnes = $_POST['genres'];
        $db->modifierGenresAlbum($id, $genresSelectionnes);
    }


    if (isset($_POST['new_chansons'])) {
        $chansonsData = $_POST['new_chansons'];
        // var_dump($chansonsData);
        $cpt = 1;
        foreach ($chansonsData as $chansonData) {
            // var_dump($chansonData);
            // var_dump($chansonData['nom']);
            // var_dump($chansonData['duree']);
            // var_dump($chansonData['description']);
            $nomChanson = $chansonData['nom'];
            $dureeChanson = $chansonData['duree'];
            $descriptionChanson = isset($chansonData['description']) ? $chansonData['description'] : '';

            $db->ajouterChansonAlbum($nomChanson, $dureeChanson, $descriptionChanson, $id);
        }
    }

}

require 'header.php';
$artistes = $db->getArtistes();

$genres = $db->getGenres();
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

        <fieldset>
            <legend>Genres :</legend>
            <?php foreach ($genres as $genre) : ?>
                <div class="genre-checkbox">
                    <input type="checkbox" id="genre_<?php echo $genre['id']; ?>" name="genres[]" value="<?php echo $genre['id']; ?>">
                    <label for="genre_<?php echo $genre['id']; ?>"><?php echo $genre['nom']; ?></label>
                </div>
            <?php endforeach; ?>
        </fieldset>

        <fieldset>
            <legend>Chansons :</legend>
            <button type="button" id="toggleChansonBlock">Ajouter une chanson</button>
            <div id="chansons-container"></div>
        </fieldset>
        

        <input type="submit" name="ajouter_album" value="Ajouter l'Album">
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
                cancelButton.className = 'cancel-chanson';
                cancelButton.innerHTML = 'Annuler';
                cancelButton.addEventListener('click', function () {
                    chansonBlock.remove();
                });

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

        });
    </script>

</main>
