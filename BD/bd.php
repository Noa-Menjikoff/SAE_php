<?php

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
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        date_naissance DATE NOT NULL,
        date_mort DATE,
        pays VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) NOT NULL
    )");

    $file_db->exec("CREATE TABLE Albums (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) NOT NULL,
        date_sortie DATE NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) NOT NULL,
        artiste_id INT NOT NULL,
        FOREIGN KEY (artiste_id) REFERENCES Artistes(id)
    )");

    $file_db->exec("CREATE TABLE Chansons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) NOT NULL,
        duree INT NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) NOT NULL,
        album_id INT NOT NULL,
        FOREIGN KEY (album_id) REFERENCES Albums(id)
    )");

    $file_db->exec("CREATE TABLE Playlists (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) NOT NULL,
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

    // on ferme la connexion
    $file_db = null;

} catch (PDOException $ex) {
    echo $ex->getMessage();
}
?>
