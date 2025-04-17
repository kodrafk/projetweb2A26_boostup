<?php
require_once '../model/projet.php';
require_once '../config/database.php';

class ProjetController {

    //  Afficher tous les projets
    public function afficher() {
        $projets = Projet::afficherProjets();
        require 'View/BackOffice/template/Template_Luna/Projet.php';
    }
    

    //  Ajouter un projet
    public function ajouter() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = $_POST['nom'];
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $description = $_POST['description'];
            $projet = new Projet($nom, $date_debut, $date_fin, $description);
            $projet->ajouterProjet();
            header("Location: /BoostUp/view/Backoffice/template/Template_Luna/Projet.php"); // redirection après ajout
        } else {
            include 'View/BackOffice/template/Template_Luna/Projet.php'; // le formulaire
        }
    }

    //  Ajouter un projetF
    public function ajouterF() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = $_POST['nom'];
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $description = $_POST['description'];
            $projet = new Projet($nom, $date_debut, $date_fin, $description);
            $projet->ajouterProjet();
            header("Location: /BoostUp/view/Backoffice/TemplateFront/projetF.php"); // redirection après ajout
        } else {
            include 'View/Backoffice/TemplateFront/projetF.php'; // le formulaire
        }
    }

    // Modifier un projet
    public function modifierProjet($id, $nom_projet, $date_debut, $date_fin, $description) {
        $db = getDB();
        $sql = "UPDATE Projet SET nom_projet = :nom_projet, date_debut = :date_debut, date_fin = :date_fin, description = :description WHERE ID_Projet = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom_projet', $nom_projet);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }
    //  Supprimer un projet
    public function supprimer() {
        if (isset($_GET['id'])) {
            Projet::supprimerProjet($_GET['id']);
            header("Location: index.php?action=afficher");
        }
    }

}

?>
