<?php
require_once(__DIR__ . '/../config/database.php');

class Projet {

    private $nom_projet;
    private $date_debut;
    private $date_fin;
    private $description;
    private $pdo;
    private $id_categorie;

    // Constructeur
    public function __construct($nom_projet, $date_debut, $date_fin, $description, $id_categorie) {
        $this->nom_projet = $nom_projet;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->description = $description;
        $this->id_categorie = $id_categorie ;

        $this->pdo = getDB(); // Connexion à la base de données
    }

    // Ajouter un projet
    public function ajouterProjet() {
        //try {
            $sql = "INSERT INTO Projet (nom_projet, date_debut, date_fin, description, id_categorie) 
                    VALUES (:nom_projet, :date_debut, :date_fin, :description, :id_categorie)";
            $stmt = $this->pdo->prepare($sql);

            // Lier les paramètres
            $stmt->bindParam(':nom_projet', $this->nom_projet);
            $stmt->bindParam(':date_debut', $this->date_debut);
            $stmt->bindParam(':date_fin', $this->date_fin);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':id_categorie', $this->id_categorie);

            // Exécuter la requête
            $stmt->execute();

            // // Vérification si l'insertion a eu lieu
            // if ($stmt->rowCount() > 0) {
            //     echo "Le projet a été ajouté avec succès.";
            // } else {
            //     echo "Aucune donnée insérée. Vérifiez les valeurs fournies.";
            // }
        // } catch (PDOException $e) {
        //     echo "Erreur lors de l'ajout du projet : " . $e->getMessage();
        // }
    }

    // Afficher tous les projets
    public static function afficherProjets() {
        try {
            $db = getDB();
            $sql = "SELECT p.id_projet, p.date_debut, p.date_fin, p.description, c.nom_categorie, AS categorie
            FROM projet p
            JOIN categorie c ON p.id_categorie= c.id_categorie";
            $stmt = $db->query($sql);
            
            if ($stmt === false) {
                error_log("Erreur SQL: " . print_r($db->errorInfo(), true));
                return [];
            }
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return is_array($result) ? $result : [];
        } catch (PDOException $e) {
            error_log("Erreur dans afficherProjets(): " . $e->getMessage());
            return [];
        }
    }

    

    // Modifier un projet existant
    public function modifierProjet($id_projet) {
        $db = getDB();
        $sql = "UPDATE Projet SET nom_projet = :nom_projet, date_debut = :date_debut,
                date_fin = :date_fin, description = :description
                WHERE ID_Projet = :id_projet";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':nom_projet' => $this->nom_projet,
            ':date_debut' => $this->date_debut,
            ':date_fin' => $this->date_fin,
            ':description' => $this->description,
            ':id_projet' => $id_projet
        ]);
    }
    
    
    
    // Supprimer un projet
    public static function supprimerProjet($id_projet) {
        $db = getDB();
        $sql = "DELETE FROM Projet WHERE ID_Projet = :id_projet";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id_projet' => $id_projet]);
 
    }
    public static function getProjetById($id) {
        $db = getDB();
        $sql = "SELECT * FROM Projet WHERE ID_Projet = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Vérifie si un projet a été trouvé
        $projet = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($projet) {
            return $projet;
        } else {
            return null;  // Aucun projet trouvé pour cet ID
        }
    }
    public static function getDB() {
        return getDB(); // Appeler la fonction getDB() définie dans database.php
    }

    // Méthode pour mettre à jour un projet
    public static function updateProjet($id, $nom_projet, $date_debut, $date_fin, $description) {
        $pdo = self::getDB();  // Obtenir la connexion à la base de données

        // Préparer la requête de mise à jour
        $sql = "UPDATE projet SET nom_projet = :nom_projet, date_debut = :date_debut, date_fin = :date_fin, description = :description WHERE ID_Projet = :id";
        
        // Exécuter la requête
        $stmt = $pdo->prepare($sql);
        
        // Lier les paramètres
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom_projet', $nom_projet, PDO::PARAM_STR);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        
        // Exécuter la requête et retourner le résultat (true si la mise à jour est réussie)
        return $stmt->execute();
    }
   
}
  
?>
