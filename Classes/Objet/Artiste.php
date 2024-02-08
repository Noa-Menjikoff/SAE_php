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

}

?>
