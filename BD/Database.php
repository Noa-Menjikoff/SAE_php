<?php
// Database.php

// require_once 'bd.php';


class Database {
    private $conn;

    private $dbPath; 
    public function __construct($dbPath) {
        $this->dbPath = $dbPath;

        try {
            $this->conn = new PDO('sqlite:' . $this->dbPath);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }

    public function getUserParNomUtilisateur($nomUtilisateur) {
        $stmt = $this->conn->prepare("SELECT * FROM Utilisateurs WHERE username = :nom_utilisateur");
        $stmt->bindParam(':nom_utilisateur', $nomUtilisateur, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function enregistrerUtilisateur($nomUtilisateur, $motDePasse) {
        $stmt = $this->conn->prepare("INSERT INTO Utilisateurs (username, password) VALUES (:nom_utilisateur, :mot_de_passe)");
        $stmt->bindParam(':nom_utilisateur', $nomUtilisateur, PDO::PARAM_STR);
        $stmt->bindParam(':mot_de_passe', $motDePasse, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateProfileImage($username, $imageData) {
        $sql = "UPDATE Utilisateurs SET image_profil = :imageData WHERE username = :username";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':imageData', $imageData, PDO::PARAM_LOB); // Assuming BLOB data type
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur de mise à jour de l'image du profil : " . $e->getMessage();
        }
    }

    public function updateArtisteImage($id, $imageData) {
        $sql = "UPDATE Artistes SET photo = :imageData WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':imageData', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur de mise à jour de l'image du profil : " . $e->getMessage();
        }
    }

    public function updateAlbumImage($id, $imageData) {
        $sql = "UPDATE Albums SET img = :imageData WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':imageData', $imageData, PDO::PARAM_LOB); // Assuming BLOB data type
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur de mise à jour de l'image du profil : " . $e->getMessage();
        }
    }

    public function getProfileImageByUsername($username) {
        $sql = "SELECT image_profil FROM Utilisateurs WHERE username = :username";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(array(':username' => $username));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'image du profil : " . $e->getMessage();
        }
    }



    public function getArtistes() {
        $stmt = $this->conn->prepare("SELECT * FROM Artistes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterArtiste($prenom, $description, $photo)
    {
        $sql = "INSERT INTO Artistes (prenom, description, photo) VALUES (:prenom, :description, :photo)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'artiste : " . $e->getMessage();
            return false;
        }
    }

    public function modifierArtiste($id, $prenom, $description, $photo){
        $sql = "UPDATE Artistes SET prenom = :prenom, description = :description, photo = :photo WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la modification de l'artiste : " . $e->getMessage();
            return false;
        }
    }


    public function getAlbums() {
        $stmt = $this->conn->prepare("SELECT * FROM Albums");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterAlbum($nom, $dateSortie, $description, $artisteId, $img){
        $sql = "INSERT INTO Albums (nom, date_sortie, description, artiste_id, img) VALUES (:nom, :date_sortie, :description, :artiste_id, :img)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':date_sortie', $dateSortie, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':artiste_id', $artisteId, PDO::PARAM_INT);
            $stmt->bindParam(':img', $img, PDO::PARAM_LOB);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'album : " . $e->getMessage();
            return false;
        }
    }

    public function modifierAlbum($id, $nom, $dateSortie, $description, $img){
        $sql = "UPDATE Albums SET nom = :nom, date_sortie = :date_sortie, description = :description, img = :img WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':date_sortie', $dateSortie, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':img', $img, PDO::PARAM_LOB);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la modification de l'album : " . $e->getMessage();
        }
    }



    public function getMusics() {
        $stmt = $this->conn->prepare("SELECT * FROM Chansons");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteChanson($chansonID)
{
    try {
        $stmt = $this->conn->prepare("DELETE FROM Chansons WHERE id = :chanson_id");
        $stmt->bindParam(':chanson_id', $chansonID, PDO::PARAM_INT);
        $stmt->execute();

        // $stmtPlaylistChansons = $this->conn->prepare("DELETE FROM PlaylistChansons WHERE chanson_id = :chanson_id");
        // $stmtPlaylistChansons->bindParam(':chanson_id', $chansonID, PDO::PARAM_INT);
        // $stmtPlaylistChansons->execute();

        return true;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la chanson : " . $e->getMessage();
        return false;
    }
}
    
    public function getAlbumById($idAlbum) {
        $stmt = $this->conn->prepare("SELECT * FROM Albums WHERE id = :id_album");
        $stmt->bindParam(':id_album', $idAlbum, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteArtiste($idArtiste) {
        $sql = "DELETE FROM Artistes WHERE id = :id_artiste";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_artiste', $idArtiste, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'artiste : " . $e->getMessage();
        }
    }

    public function getArtisteById($idArtiste) {
        $stmt = $this->conn->prepare("SELECT * FROM Artistes WHERE id = :id_artiste");
        $stmt->bindParam(':id_artiste', $idArtiste, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getChansonsAlbum($albumId){
        $sql = "SELECT * FROM Chansons WHERE album_id = :album_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':album_id', $albumId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getAlbumsByArtistId($idArtiste) {
        $stmt = $this->conn->prepare("SELECT * FROM Albums WHERE artiste_id = :artiste_id");
        $stmt->bindParam(':artiste_id', $idArtiste, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAlbum($idAlbum) {
        $this->conn->beginTransaction();
    
        try {
            
            $this->deleteSongsByAlbumId($idAlbum);
    
            
            $sql = "DELETE FROM Albums WHERE id = :id_album";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_album', $idAlbum, PDO::PARAM_INT);
            $stmt->execute();
    
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            echo "Erreur lors de la suppression de l'album : " . $e->getMessage();
            return false;
        }
    }
    
    public function deleteSongsByAlbumId($idAlbum) {
        $sql = "DELETE FROM Chansons WHERE album_id = :id_album";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_album', $idAlbum, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression des chansons de l'album : " . $e->getMessage();
            return false;
        }
    }

    public function ajouterAbonnement($idUtilisateur, $idArtiste) {
        $sql = "INSERT INTO Abonnements (utilisateur_id, artiste_id) VALUES (:id_utilisateur, :id_artiste)";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':id_artiste', $idArtiste, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'abonnement : " . $e->getMessage();
        }
    }

    public function estAbonne($idUtilisateur, $idArtiste) {
        $sql = "SELECT COUNT(*) AS count FROM Abonnements WHERE utilisateur_id = :id_utilisateur AND artiste_id = :id_artiste";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':id_artiste', $idArtiste, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
        } catch (PDOException $e) {
            echo "Erreur lors de la vérification de l'abonnement : " . $e->getMessage();
            return false;
        }
    }

    public function enleverAbonnement($idUtilisateur, $idArtiste){
        $sql = "DELETE FROM Abonnements WHERE utilisateur_id = :id_utilisateur AND artiste_id = :id_artiste";
    
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':id_artiste', $idArtiste, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'abonnement : " . $e->getMessage();
        }
    }

    public function getChansonsArtiste($idArtiste) {
        $stmt = $this->conn->prepare("SELECT Chansons.* FROM Chansons
            INNER JOIN Albums ON Chansons.album_id = Albums.id
            WHERE Albums.artiste_id = :idArtiste");
        $stmt->bindParam(':idArtiste', $idArtiste, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ajouterChansonAlbum($nom, $duree, $description, $album_id){
    $sql = "INSERT INTO Chansons (nom, duree, description, album_id) VALUES (:nom, :duree, :description, :album_id)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':duree', $duree, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':album_id', $album_id, PDO::PARAM_INT);
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la chanson à l'album : " . $e->getMessage();
            return false;
        }
    }

    public function searchArtistes($searchText) {
        $query = "SELECT * FROM Artistes WHERE prenom LIKE :searchText";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':searchText', '%' . $searchText . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchAlbums($searchText) {
        $query = "SELECT * FROM Albums WHERE nom LIKE :searchText";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':searchText', '%' . $searchText . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchChansons($searchText) {
        $query = "SELECT * FROM Chansons WHERE nom LIKE :searchText";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':searchText', '%' . $searchText . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function ajouterPlaylist($idUtilisateur, $nomPlaylist) {
        $sql = "INSERT INTO Playlists (utilisateur_id, nom) VALUES (:id_utilisateur, :nom)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nomPlaylist, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la playlist : " . $e->getMessage();
        }
    }
    public function getPlaylists($idUtilisateur) {
        $sql = "SELECT * FROM Playlists WHERE utilisateur_id = :id_utilisateur";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des playlists : " . $e->getMessage();

    public function getGenres(){
        $sql = "SELECT * FROM Genres";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenresArtiste($idArtiste){
        $sql = "SELECT * FROM Genres
                JOIN Artistes_Genres ON Genres.id = Artistes_Genres.genre_id
                WHERE Artistes_Genres.artiste_id = :idArtiste";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idArtiste', $idArtiste, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function modifierGenresArtiste($artisteId, $genresSelectionnes){
        try {
            $stmtDeleteGenres = $this->conn->prepare("DELETE FROM Artistes_Genres WHERE artiste_id = :artiste_id");
            $stmtDeleteGenres->bindParam(':artiste_id', $artisteId);
            $stmtDeleteGenres->execute();

            foreach ($genresSelectionnes as $genreId) {
                $stmtInsertGenre = $this->conn->prepare("INSERT INTO Artistes_Genres (artiste_id, genre_id) VALUES (:artiste_id, :genre_id)");
                $stmtInsertGenre->bindParam(':artiste_id', $artisteId);
                $stmtInsertGenre->bindParam(':genre_id', $genreId);
                $stmtInsertGenre->execute();
            }

            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la modification des genres de l'artiste : " . $e->getMessage();
            return false;
        }
    }


    public function getChansonsPlaylist($playlistId) {
        $stmt = $this->conn->prepare("SELECT Chansons.* FROM Chansons
            INNER JOIN PlaylistChansons ON Chansons.id = PlaylistChansons.chanson_id
            WHERE PlaylistChansons.playlist_id = :playlistId");
        $stmt->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImageAlbumBySong($songId) {
        $stmt = $this->conn->prepare("SELECT Albums.image FROM Albums
            INNER JOIN Chansons ON Chansons.album_id = Albums.id
            WHERE Chansons.id = :songId");
        $stmt->bindParam(':songId', $songId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterChansonPlaylist($playlistId, $chansonId) {
        $sql = "INSERT INTO PlaylistChansons (playlist_id, chanson_id) VALUES (:playlistId, :chansonId)";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
            $stmt->bindParam(':chansonId', $chansonId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la chanson à la playlist : " . $e->getMessage();
        }
    }
    
    public function supprimerChansonPlaylist($idPlaylist, $idChanson) {
        $sql = "DELETE FROM PlaylistChansons WHERE playlist_id = :idPlaylist AND chanson_id = :idChanson";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':idPlaylist', $idPlaylist, PDO::PARAM_INT);
            $stmt->bindParam(':idChanson', $idChanson, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de la chanson de la playlist : " . $e->getMessage();
        }
    }
    

    public function getGenresAlbum($idAlbum){
        $sql = "SELECT * FROM Genres
                JOIN Albums_Genres ON Genres.id = Albums_Genres.genre_id
                WHERE Albums_Genres.album_id = :idAlbum";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idAlbum', $idAlbum, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    public function modifierGenresAlbum($albumId, $genresSelectionnes){
        try {
            $stmtDeleteGenres = $this->conn->prepare("DELETE FROM Albums_Genres WHERE album_id = :album_id");
            $stmtDeleteGenres->bindParam(':album_id', $albumId);
            $stmtDeleteGenres->execute();

            foreach ($genresSelectionnes as $genreId) {
                $stmtInsertGenre = $this->conn->prepare("INSERT INTO Albums_Genres (album_id, genre_id) VALUES (:album_id, :genre_id)");
                $stmtInsertGenre->bindParam(':album_id', $albumId);
                $stmtInsertGenre->bindParam(':genre_id', $genreId);
                $stmtInsertGenre->execute();
            }

            return true;
        } catch (PDOException $e) {
            echo "Erreur lors de la modification des genres de l'album : " . $e->getMessage();
            return false;
        }
    }



    public function closeConnection() {
        $this->conn = null;
    }



}
?>
