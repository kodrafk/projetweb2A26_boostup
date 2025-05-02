<?php
require_once '../model/categorie.php';
require_once '../config/database.php';

class CategorieController {

    // Afficher toutes les catégories
    public function afficher() {
        $categories = Categorie::afficherCategories();
        require 'View/BackOffice/template/Template_Luna/Categorie.php';
    }

    // Ajouter une catégorie (back office)
    public function ajouter() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom_categorie = $_POST['nom_categorie'];
            $description = $_POST['description'];
            $categorie = new Categorie($nom_categorie, $description);
            $categorie->ajouterCategorie();
            header("Location: /BoostUp/view/BackOffice/template/Template_Luna/Categorie.php"); // Redirection après ajout
        } else {
            include 'View/BackOffice/template/Template_Luna/Categorie.php'; // Le formulaire pour ajouter
        }
    }

    // Modifier une catégorie
    public function modifierCategorie($id, $nom_categorie, $description) {
        $db = getDB();
        $sql = "UPDATE Categorie SET nom_categorie = :nom_categorie, description = :description WHERE ID_Categorie = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom_categorie', $nom_categorie);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }

    // Supprimer une catégorie
    public function supprimer() {
        if (isset($_GET['id'])) {
            Categorie::supprimerCategorie($_GET['id']);
            header("Location: index.php?action=afficher");
        }
    }
}
?>
