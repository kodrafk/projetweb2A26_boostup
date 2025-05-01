<?php
require_once(__DIR__ . '/../config.php');

class Thematique {
    private ?int $id_thematique = null;
    private ?string $titre = null;
    private ?string $description = null;

    public function __construct($id, $titre, $description) {
        $this->id_thematique = $id;
        $this->titre = $titre;
        $this->description = $description;
    }

    // Getters
    public function getIdThematique() {
        return $this->id_thematique;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    // Setters
    public function setTitre($titre) {
        $this->titre = $titre;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
}
