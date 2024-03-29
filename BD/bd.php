<?php

require_once 'vendor/autoload.php';  // Inclure l'autoloader de Composer

use Symfony\Component\Yaml\Yaml;
try {
    // le fichier de BD s'appellera questions.sqlite3
    $file_db = new PDO('sqlite:BD/sae.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $file_db->exec("DROP TABLE IF EXISTS Commentaires");
    $file_db->exec("DROP TABLE IF EXISTS Notes");
    $file_db->exec("DROP TABLE IF EXISTS PlaylistChansons");
    $file_db->exec("DROP TABLE IF EXISTS Playlists");
    $file_db->exec("DROP TABLE IF EXISTS Chansons");
    $file_db->exec("DROP TABLE IF EXISTS Albums");
    $file_db->exec("DROP TABLE IF EXISTS Albums_Genres");
    $file_db->exec("DROP TABLE IF EXISTS Artistes_Genres");
    $file_db->exec("DROP TABLE IF EXISTS Genres");
    $file_db->exec("DROP TABLE IF EXISTS Artistes");
    $file_db->exec("DROP TABLE IF EXISTS Utilisateurs");

    $file_db->exec("CREATE TABLE Utilisateurs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(255) NOT NULL,
        image_profil BLOB DEFAULT NULL,
        password VARCHAR(255) NOT NULL
    )");

    $file_db->exec("CREATE TABLE Artistes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        photo BLOB DEFAULT NULL,
        prenom VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL
    )");

    $file_db->exec("CREATE TABLE Genres (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) 
    )");

    $file_db->exec("CREATE TABLE Artistes_Genres (
        artiste_id INT ,    
        genre_id INT ,
        FOREIGN KEY (artiste_id) REFERENCES Artistes(id),
        FOREIGN KEY (genre_id) REFERENCES Genres(id)
    )");

    $file_db->exec("CREATE TABLE Albums_Genres (
        album_id INT,
        genre_id INT,
        FOREIGN KEY (album_id) REFERENCES Albums(id),
        FOREIGN KEY (genre_id) REFERENCES Genres(id)
    )");

    $file_db->exec("CREATE TABLE Albums (
        id INTEGER PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        date_sortie DATE NOT NULL,
        description TEXT NOT NULL,
        artiste_id INT NOT NULL,
        FOREIGN KEY (artiste_id) REFERENCES Artistes(id)
    )");


    $file_db->exec("CREATE TABLE Chansons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) NOT NULL,
        duree INT NOT NULL,
        description TEXT NOT NULL,
        album_id INT NOT NULL,
        FOREIGN KEY (album_id) REFERENCES Albums(id)
    )");

    $file_db->exec("CREATE TABLE Playlists (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        utilisateur_id INT NOT NULL,
        FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id)
    )");

    $file_db->exec("CREATE TABLE PlaylistChansons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        playlist_id INT NOT NULL,
        chanson_id INT NOT NULL,
        FOREIGN KEY (playlist_id) REFERENCES Playlists(id),
        FOREIGN KEY (chanson_id) REFERENCES Chansons(id)
    )");

    $file_db->exec("CREATE TABLE Notes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        note INT NOT NULL,
        utilisateur_id INT NOT NULL,
        chanson_id INT NOT NULL,
        FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id),
        FOREIGN KEY (chanson_id) REFERENCES Chansons(id)
    )");

    $file_db->exec("CREATE TABLE Commentaires (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        commentaire TEXT NOT NULL,
        utilisateur_id INT NOT NULL,
        chanson_id INT NOT NULL,
        FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id),
        FOREIGN KEY (chanson_id) REFERENCES Chansons(id)
    )");



function insertDataFromYAML($file_db, $yamlFile)
{
    $yamlData = Yaml::parseFile($yamlFile);

    foreach ($yamlData as $entry) {
        $id = $entry['entryId'];
        $prenom = $entry['parent'];
        $yearA = $entry['releaseYear'];
        $description = $entry['title'];

        // Check if the artist exists in the table Artistes
        $stmtCheckArtist = $file_db->prepare("SELECT id FROM Artistes WHERE prenom = :prenom");
        $stmtCheckArtist->bindParam(':prenom', $prenom);
        $stmtCheckArtist->execute();
        $existingArtist = $stmtCheckArtist->fetch(PDO::FETCH_ASSOC);

        if ($existingArtist) {
            // If the artist exists, use their ID
            $artistID = $existingArtist['id'];
        } else {
            // If the artist doesn't exist, insert them into the table Artistes
            $stmtInsertArtist = $file_db->prepare("INSERT INTO Artistes (prenom) VALUES (:prenom)");
            $stmtInsertArtist->bindParam(':prenom', $prenom);
            $stmtInsertArtist->execute();

            // Get the ID of the newly inserted artist
            $artistID = $file_db->lastInsertId();
        }

        // Insert the album into the table Albums
        $stmtInsertAlbum = $file_db->prepare("INSERT INTO Albums (id, nom, date_sortie, description, artiste_id) VALUES (:id, :nom, :date_sortie, :description, :artiste_id)");
        $stmtInsertAlbum->bindParam(':id', $id);
        $stmtInsertAlbum->bindParam(':nom', $prenom);
        $stmtInsertAlbum->bindParam(':date_sortie', $yearA);
        $stmtInsertAlbum->bindParam(':description', $description);
        $stmtInsertAlbum->bindParam(':artiste_id', $artistID);
        $stmtInsertAlbum->execute();

        // Insert genres into the table Genres and associate them with the album in Albums_Genres
        foreach ($entry['genre'] as $genre) {
            // Check if the genre exists in the table Genres
            $stmtCheckGenre = $file_db->prepare("SELECT id FROM Genres WHERE nom = :nom");
            $stmtCheckGenre->bindParam(':nom', $genre);
            $stmtCheckGenre->execute();
            $existingGenre = $stmtCheckGenre->fetch(PDO::FETCH_ASSOC);

            if ($existingGenre) {
                // If the genre exists, use its ID
                $genreID = $existingGenre['id'];
            } else {
                // If the genre doesn't exist, insert it into the table Genres
                $stmtInsertGenre = $file_db->prepare("INSERT INTO Genres (nom) VALUES (:nom)");
                $stmtInsertGenre->bindParam(':nom', $genre);
                $stmtInsertGenre->execute();

                // Get the ID of the newly inserted genre
                $genreID = $file_db->lastInsertId();
            }

            // Associate the album with the genre in the table Albums_Genres
            $stmtInsertAlbumGenre = $file_db->prepare("INSERT INTO Albums_Genres (album_id, genre_id) VALUES (:album_id, :genre_id)");
            $stmtInsertAlbumGenre->bindParam(':album_id', $id);
            $stmtInsertAlbumGenre->bindParam(':genre_id', $genreID);
            $stmtInsertAlbumGenre->execute();
        }
    }

    echo "Data successfully inserted into the database!";
}



insertDataFromYAML($file_db, __DIR__ . '/extrait.yml');

    // on ferme la connexion
    $file_db = null;

} catch (PDOException $ex) {
    echo $ex->getMessage();
}
?>
