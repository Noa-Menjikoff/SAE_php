<?php
// Database.php

// require_once 'bd.php';

session_start();

class Database {
    private $conn;

    private $dbPath; 
    public function __construct($dbPath) {
        $this->dbPath = $dbPath;

        try {
            $this->conn = new PDO('sqlite:' . $this->dbPath);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected to the database successfully.<br>";
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
            $stmt->bindParam(':imageData', $imageData, PDO::PARAM_LOB); // Assuming BLOB data type
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

    public function getAlbums() {
        $stmt = $this->conn->prepare("SELECT * FROM Albums");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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


    public function getChansonsAlbum($idAlbum) {
        $stmt = $this->conn->prepare("SELECT * FROM Chansons WHERE album_id = :idAlbum");
        $stmt->bindParam(':idAlbum', $idAlbum, PDO::PARAM_INT);
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
            // Supprime les chansons liées à l'album
            $this->deleteSongsByAlbumId($idAlbum);
    
            // Supprime l'album
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

    public function closeConnection() {
        $this->conn = null;
    }



}
?>
