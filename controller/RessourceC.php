<?php
//include_once '../config.php';
//include_once '../model/Ressource.php';
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../model/Ressource.php');

class RessourceC {

    //  Ajouter un ressource
     function ajouterRessource($ressource) {
        $sql="INSERT INTO ressources (type, titre, lien, description, id_thematique)
        VALUES (:type,:titre,:lien,:description , :id_thematique)";
        $db=config::getConnexion();
        try{
            $query = $db->prepare($sql);
            $query->execute([
                'type' => $ressource->getType(),
                'titre' => $ressource->getTitre(),
                'lien' => $ressource->getLien(),
                'description' => $ressource->getDescription(),
                'id_thematique' => $ressource->getIdThematique()
            ]);
        } catch (Exception $r){
            echo 'Error: ' . $r->getMessage();
        }
    }

    // afficher un ressource 
     
    

    /*public function afficherRessource() {
        $sql = "SELECT r.id_ressource, r.titre, r.type, r.lien, r.description, t.titre AS thematique
        FROM ressources r
        JOIN thematique t ON r.id_thematique = t.id_thematique";
    
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }*/

    public function afficherRessource() {
        $sql = "SELECT 
        r.id_ressource, 
        r.titre, 
        r.type, 
        r.lien, 
        r.description, 
        r.id_thematique,  
        t.titre AS thematique 
    FROM ressources r
    JOIN thematique t ON r.id_thematique = t.id_thematique";
        
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    
    // supprimer un ressource 
    
    function deleteRessource($ide) {
        $sql="DELETE FROM ressources WHERE id_ressource = :id";
        $db=config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue('id' , $ide);
        try{
            $req->execute();
        } catch (Exception $r){
            die ('Error: ' . $r->getMessage());
        }
    }


    function getRessourceById($id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM ressources WHERE id_ressource = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC); // tableau associatif (clé => valeur)
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }


    /*public function updateRessource($id, $type, $titre, $lien, $description) {
        $sql = "UPDATE ressources SET type = :type, titre = :titre, lien = :lien, description = :description WHERE id_ressource = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':type', $type);
            $query->bindParam(':titre', $titre);
            $query->bindParam(':lien', $lien);
            $query->bindParam(':description', $description);
            $query->bindParam(':id', $id);
            return $query->execute();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }*/

    public function updateRessource($id, $type, $titre, $lien, $description, $id_thematique) {
        $sql = "UPDATE ressources SET 
        type = :type,
        titre = :titre, 
        lien = :lien, 
        description = :description,
        id_thematique = :id_thematique 
       WHERE id_ressource = :id";
        
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        
        $query->bindValue(':id', $id);
        $query->bindValue(':type', $type);
        $query->bindValue(':titre', $titre);
        $query->bindValue(':lien', $lien);
        $query->bindValue(':description', $description);
        $query->bindValue(':id_thematique', $id_thematique);
        
        return $query->execute();
    }
    

    public function filtrerParType($type) {
        $sql = "SELECT r.*, t.titre AS thematique 
                FROM ressources r 
                INNER JOIN thematique t ON r.id_thematique = t.id_thematique 
                WHERE r.type = :type";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['type' => $type]);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }


    public function trierParNomThematique() {
        $sql = "SELECT r.*, t.titre AS thematique 
                FROM ressources r
                INNER JOIN thematique t ON r.id_thematique = t.id_thematique
                ORDER BY t.titre ASC";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getStatsByType() {
        $sql = "SELECT type, COUNT(*) AS count 
                FROM ressources 
                GROUP BY type";
        
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }


}

?>

