<?php
require_once(__DIR__ . '/../config/database.php');

class Categorie {
    private $nom_categorie;
    private $description;
    private $pdo;

    // Constructeur
    public function __construct($nom_categorie, $description) {
        $this->nom_categorie = $nom_categorie;
        $this->description = $description;
        $this->pdo = getDB(); // Connexion à la base de données
    }

    // Ajouter une catégorie
    public function ajouterCategorie() {
        $sql = "INSERT INTO categorie (nom_categorie, description) 
                VALUES (:nom_categorie, :description)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nom_categorie', $this->nom_categorie);
        $stmt->bindParam(':description', $this->description);
        return $stmt->execute();
    }

    // Afficher toutes les catégories
    public static function afficherCategories() {
        try {
            $db = getDB();
            $sql = "SELECT * FROM categorie ORDER BY id_categorie DESC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans afficherCategories(): " . $e->getMessage());
            return [];
        }
    }

    public static function updateCategorie($id, $nom_categorie, $description) {
        try {
            $pdo = getDB();  // Connexion à la base
            $sql = "UPDATE categorie 
                    SET nom_categorie = :nom_categorie, description = :description 
                    WHERE id_categorie = :id";
    
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom_categorie', $nom_categorie);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur dans updateCategorie(): " . $e->getMessage());
            return false;
        }
    }
    
    
    // Modifier une catégorie
    public function modifierCategorie($id_categorie) {
        $sql = "UPDATE categorie 
                SET nom_categorie = :nom_categorie, description = :description 
                WHERE id_categorie = :id_categorie";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nom_categorie', $this->nom_categorie);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id_categorie', $id_categorie);
        return $stmt->execute();
    }

    // Supprimer une catégorie
    public static function supprimerCategorie($id_categorie) {
        $db = getDB();
        $sql = "DELETE FROM categorie WHERE id_categorie = :id_categorie";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_categorie', $id_categorie);
        return $stmt->execute();
    }

    // Obtenir une catégorie par ID
    public static function getCategorieById($id) {
        $db = getDB();
        $sql = "SELECT * FROM categorie WHERE id_categorie = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode statique pour obtenir la connexion
    public static function getDB() {
        return getDB(); // Depuis databaseC.php
    }
    public static function getAllCategories() {
        global $pdo;
        $sql = "SELECT * FROM categorie";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
