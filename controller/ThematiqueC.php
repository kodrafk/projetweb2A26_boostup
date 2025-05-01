<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../model/Thematique.php');

class ThematiqueC {

    // Ajouter une thématique
    function ajouterThematique($thematique) {
        $sql = "INSERT INTO thematique (titre, description) 
                VALUES (:titre, :description)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $thematique->getTitre(),
                'description' => $thematique->getDescription(),
            ]);
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Afficher toutes les thématiques
    function afficherThematique() {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM thematique");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Supprimer une thématique par ID
    /*function deleteThematique($id) {
        $sql = "DELETE FROM thematique WHERE id_thematique = :id";
        $db = config::getConnexion();
        try {
            $req = $db->prepare($sql);
            $req->bindValue(':id', $id);
            $req->execute();
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }*/

    function deleteThematique($id) {
        $sql = "DELETE FROM thematique WHERE id_thematique = :id";
        $db = config::getConnexion();
        
        try {
            $req = $db->prepare($sql);
            $req->bindValue(':id', $id, PDO::PARAM_INT); // Ajout du type
            $req->execute();
            
            // Vérifier si une ligne a été affectée
            return $req->rowCount() > 0;
            
        } catch (PDOException $e) {
            throw new Exception("Erreur SQL : " . $e->getMessage()); // Lancer l'exception
        }
    }

    // Récupérer une thématique par ID
    function getThematiqueById($id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM thematique WHERE id_thematique = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }

    // Modifier une thématique
    public function updateThematique($id, $titre, $description) {
        $sql = "UPDATE thematique SET titre = :titre, description = :description WHERE id_thematique = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':titre', $titre);
            $query->bindParam(':description', $description);
            $query->bindParam(':id', $id);
            return $query->execute();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    public function filtrerParTitre($titre) {
        $sql = "SELECT * FROM thematique WHERE LOWER(titre) LIKE LOWER(:titre)";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $titreLike = "%" . $titre . "%";
        $query->bindParam(':titre', $titreLike);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function trierParTitre()
    {
       $sql = "SELECT * FROM thematique ORDER BY titre ASC";
       $db = config::getConnexion();
       try {
          $liste = $db->query($sql);
          return $liste->fetchAll();
        } catch (Exception $e) {
           die('Erreur : ' . $e->getMessage());
       }
   }

    
}
?>
