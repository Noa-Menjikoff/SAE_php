<?php
// Database.php
session_start();
require_once 'config.php';

class Database {
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
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
            $stmt->bindParam(':imageData', $imageData, PDO::PARAM_LOB);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
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

    
    public function closeConnection() {
        $this->conn = null;
    }
}
?>
