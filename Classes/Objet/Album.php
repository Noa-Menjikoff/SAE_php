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

    function afficherAlbumAdmin() {

        echo '<form action="admin.php" method="post">';
            echo '<input type="hidden" name="album_id" value="'.$this->id.'">';


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
                echo '<div class="bothButtons">';

                    echo '<button type="button" onclick="window.location.href=\'modifier_album.php?id='.$this->id.'\'" class="Button">';
                            echo '<img class="imgButton" src="IMG/crayon.png" alt="Modifier">';
                        echo '</button>';

                        echo '<button type="submit" name="supprimer_album" class="Button">';
                        echo '<img class="imgButton" src="IMG/redCrossV2.png" alt="Supprimer">';
                        echo '</button>';

                echo '</div>';
            echo '</div>';
        echo '</form>';
        
    }




    

}
?>
