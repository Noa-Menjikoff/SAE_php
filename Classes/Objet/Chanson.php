<?php

class Chanson {
    private $id;
    private $nom;
    private $duree;
    private $album_id;

    public function __construct($id, $nom, $duree, $album_id) {
        $this->id = $id;
        $this->nom = $nom;
        $this->duree = $duree;
        $this->album_id = $album_id;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getDuree() {
        return $this->duree;
    }

    public function setDuree($duree) {
        $this->duree = $duree;
    }



    public function afficherChanson(){
        echo '<p>'.$this->id.'</p>';
        echo '<p>'.$this->nom.'</p>';
        echo '<p>'.$this->duree.' min</p>';
    }
}
