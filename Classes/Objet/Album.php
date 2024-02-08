<?php

class Album {
    private $id;
    private $nom;
    private $dateSortie;
    private $description;
    private $artisteId;
    private $img;

    public function __construct($id, $nom, $dateSortie, $description, $artisteId, $img) {
        $this->id = $id;
        $this->nom = $nom;
        $this->dateSortie = $dateSortie;
        $this->description = $description;
        $this->artisteId = $artisteId;
        $this->img = $img;
    }

    // Getter methods
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDateSortie() {
        return $this->dateSortie;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getArtisteId() {
        return $this->artisteId;
    }

    public function getImg() {
        return $this->img;
    }

    function afficherAlbum() {
        echo '<div class="album">';
        
        if ($this->img === NULL) {
            echo '<img src="IMG/default.png" alt="user">';
        } else {
            $imageData = $this->img;
            echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
        }
        
        echo '<div class="contenu">';
        
        echo '<a href="detail_album.php?id=' . $this->id . '">' . substr($this->nom, 0, 15) . '</a>';
        
        if (strlen($this->nom) > 15) {
            echo '...';
        }
        
        echo '</div>';
        echo '</div>';
    }




    

}
?>
