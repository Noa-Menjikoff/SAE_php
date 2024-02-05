DROP TABLE IF EXISTS Commentaires;
DROP TABLE IF EXISTS Notes;
DROP TABLE IF EXISTS PlaylistChansons;
DROP TABLE IF EXISTS Playlists;
DROP TABLE IF EXISTS Chansons;
DROP TABLE IF EXISTS Albums;
DROP TABLE IF EXISTS Artistes;
DROP TABLE IF EXISTS Utilisateurs;


CREATE TABLE Utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    image_profil LONGBLOB DEFAULT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Artistes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    date_naissance DATE NOT NULL,
    date_mort DATE,
    pays VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL
);

CREATE TABLE Albums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    date_sortie DATE NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    artiste_id INT NOT NULL,
    FOREIGN KEY (artiste_id) REFERENCES Artistes(id)
);

CREATE TABLE Chansons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    duree INT NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    album_id INT NOT NULL,
    FOREIGN KEY (album_id) REFERENCES Albums(id)
);


CREATE TABLE Playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    utilisateur_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id)
);


CREATE TABLE PlaylistChansons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    playlist_id INT NOT NULL,
    chanson_id INT NOT NULL,
    FOREIGN KEY (playlist_id) REFERENCES Playlists(id),
    FOREIGN KEY (chanson_id) REFERENCES Chansons(id)
);


CREATE TABLE Notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note INT NOT NULL,
    utilisateur_id INT NOT NULL,
    chanson_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id),
    FOREIGN KEY (chanson_id) REFERENCES Chansons(id)
);

CREATE TABLE Commentaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire TEXT NOT NULL,
    utilisateur_id INT NOT NULL,
    chanson_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES Utilisateurs(id),
    FOREIGN KEY (chanson_id) REFERENCES Chansons(id)
);

CREATE TABLE NOA( 
num INT AUTO_INCREMENT PRIMARY KEY,
ferme_ta_session VARCHAR2 NOT NULL,
);
