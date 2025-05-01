<?php
require_once(__DIR__ . '/../config.php');

class Ressource {
    private ?int $id_ressource = null ;
    private ?string $type = null;
    private ?string $titre = null;
    private ?string $lien = null;
    private ?string $description = null;
    private $id_thematique;


    public function __construct($id ,$ty, $t, $l, $des,  $id_thematique ) {
        $this->id_ressource = $id;
        $this->type = $ty;
        $this->titre = $t;
        $this->lien = $l;
        $this->description = $des;
        $this->id_thematique = $id_thematique;  // Initialiser l'ID thématique
       
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

     // Getter et Setter pour id_thematique
     public function getIdThematique() {
        return $this->id_thematique;
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

    public function setIdThematique($id_thematique) {
        $this->id_thematique = $id_thematique;
        return $this;
    }
}
