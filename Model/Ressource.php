<?php
require_once(__DIR__ . '/../config.php');

class Ressource {
    private ?int $id_ressource = null ;
    private ?string $type = null;
    private ?string $titre = null;
    private ?string $lien = null;
    private ?string $description = null;


    public function __construct($id ,$ty, $t, $l, $des) {
        $this->id_ressource = $id;
        $this->type = $ty;
        $this->titre = $t;
        $this->lien = $l;
        $this->description = $des;
       
    }


    public function getIdRessource() {
        return $this->id_ressource;
    }

    public function getType() {
        return $this->type;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getLien() {
        return $this->lien;
    }

    public function getDescription() {
        return $this->description;
    }


    public function setType($type) {
        $this->type = $type;
        return $this ;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
        return $this ;
    }

    public function setLien($lien) {
        $this->lien = $lien;
        return $this ;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this ;
    }
}
