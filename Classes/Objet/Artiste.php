<?php 

class Artiste
{
    private $id;
    private $prenom;
    private $description;
    private $photo;

    public function __construct($id, $prenom, $description, $photo)
    {
        $this->id = $id;
        $this->prenom = $prenom;
        $this->description = $description;
        $this->photo = $photo;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function afficherArtiste(){
        echo '<div class="artiste">';
        
        if ($this->photo  === NULL) {
            echo '<img src="IMG/default.png" alt="user">';
        } else {
            $imageData = $this->photo;
            echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
        }
        
        echo '<div class="contenu">';
        
        echo '<a href="detail_artiste.php?id=' . $this->id . '">' . substr($this->prenom, 0, 15) . '</a>';
        
        if (strlen($this->prenom) > 15) {
            echo '...';
        }
        
        echo '</div>';
        echo '</div>';
    }

    public function afficherArtisteAdmin(){

        echo '<form action="admin.php" method="post">';
            echo '<input type="hidden" name="artiste_id" value="'.$this->id.'">';
            echo '<div class="artiste">';

                if ($this->photo  === NULL) {
                    echo '<img src="IMG/default.png" alt="user">';
                } else {
                    $imageData = $this->photo;
                    echo '<img src="data:image;base64,' . $imageData . '" alt="user">';
                }
            
                echo '<div class="contenu">';
                
                    echo '<a href="detail_artiste.php?id=' . $this->id . '">' . substr($this->prenom, 0, 15) . '</a>';
                    
                    if (strlen($this->prenom) > 15) {
                        echo '...';
                    }
                    
                echo '</div>';
                echo '<div class="bothButtons">';

                echo '<button type="button" onclick="window.location.href=\'modifier_artiste.php?id='.$this->id.'\'" class="Button">';
                        echo '<img class="imgButton" src="IMG/crayon.png" alt="Modifier">';
                    echo '</button>';

                    echo '<button type="submit" name="supprimer_artiste" class="Button">';
                    echo '<img class="imgButton" src="IMG/redCrossV2.png" alt="Supprimer">';
                    echo '</button>';

                echo '</div>';
            echo '</div>';
        echo '</form>';
    }

}

?>
