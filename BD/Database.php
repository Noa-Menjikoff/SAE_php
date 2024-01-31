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

    public function deleteArtiste($idArtiste) {
        $stmt = $this->conn->prepare("DELETE FROM Artistes WHERE id = :id_artiste");
        $stmt->bindParam(':id_artiste', $idArtiste, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function getArtisteById($idArtiste) {
        $stmt = $this->conn->prepare("SELECT * FROM Artistes WHERE id = :id_artiste");
        $stmt->bindParam(':id_artiste', $idArtiste, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function closeConnection() {
        $this->conn = null;
    }
}
?>
