<?php

require_once 'vendor/autoload.php';  // Inclure l'autoloader de Composer

use Symfony\Component\Yaml\Yaml;

function insertDataFromYAML($file_db, $yamlFile)
{
    $yamlData = Yaml::parseFile($yamlFile);

    foreach ($yamlData as $entry) {
        $nom = $entry['by'];
        $prenom = null; // Ajustez en fonction de vos besoins, le fichier YAML ne semble pas contenir de prénom
        $yearA = $entry['releaseYear'];
        $description = $entry['title'];

        // Insérer l'artiste dans la table Artistes
        $stmt = $file_db->prepare("INSERT INTO Artistes (nom, prenom, yearA, description) VALUES (:nom, :prenom, :yearA, :description)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':yearA', $yearA);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        // Récupérer l'ID de l'artiste inséré
        $artisteId = $file_db->lastInsertId();

        // Insérer les genres dans la table Genres
        foreach ($entry['genre'] as $genre) {
            $stmt = $file_db->prepare("INSERT INTO Genres (nom) VALUES (:nom)");
            $stmt->bindParam(':nom', $genre);
            $stmt->execute();

            // Récupérer l'ID du genre inséré
            $genreId = $file_db->lastInsertId();

            // Relier l'artiste au genre dans la table Artistes_Genres
            $stmt = $file_db->prepare("INSERT INTO Artistes_Genres (artiste_id, genre_id) VALUES (:artisteId, :genreId)");
            $stmt->bindParam(':artisteId', $artisteId);
            $stmt->bindParam(':genreId', $genreId);
            $stmt->execute();
        }
    }

    echo "Données insérées avec succès dans la base de données!";
}

// Utilisation de la fonction
$file_db = new PDO('sqlite:sae.sqlite3');
$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

insertDataFromYAML($file_db, __DIR__ . '/extrait.yml');


?>
