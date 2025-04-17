<?php
//include_once '../config.php';
//include_once '../model/Ressource.php';
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../model/Ressource.php');

class RessourceC {

    //  Ajouter un ressource
     function ajouterRessource($ressource) {
        $sql="INSERT INTO ressources
        VALUES (NULL ,:type,:titre,:lien,:description)";
        $db=config::getConnexion();
        try{
            $query = $db->prepare($sql);
            $query->execute([
                'type' => $ressource->getType(),
                'titre' => $ressource->getTitre(),
                'lien' => $ressource->getLien(),
                'description' => $ressource->getDescription(),
            ]);
        } catch (Exception $r){
            echo 'Error: ' . $r->getMessage();
        }
    }

    // afficher un ressource 
     
    function afficherRessource() {
        try{
            $db=config::getConnexion();
            $query = $db->prepare("SELECT * FROM ressources");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $r){
            echo 'Error: ' . $r->getMessage();
        }
    }


    // Modifier un ressource 
    /*function updateRessource($ressource , $id) {
        try{
            $db=config::getConnexion();
            $query = $db->prepare(
                  ' UPDATE ressources SET
                      type = :type ,
                      titre = :titre,
                      lien = :lien,
                      description = :description
                    WHERE id_ressource = :id'
            );
            $query->execute([
                'id' => $id,
                'type' => $ressource->getType(),
                'titre' => $ressource->getTitre(),
                'lien' => $ressource->getLien(),
                'description' => $ressource->getDescription(),
            ]);

            echo $query->rowCount() . "records UPDATED successfully <br>";
        } catch (PDOException $r){
             $r->getMessage();
        }
    }*/

    /*function updateRessource($ressource, $id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare('
                UPDATE ressources SET
                    type = :type,
                    titre = :titre,
                    lien = :lien,
                    description = :description
                WHERE id_ressource = :id
            ');
            $query->execute([
                'id' => $id,
                'type' => $ressource->getType(),
                'titre' => $ressource->getTitre(),
                'lien' => $ressource->getLien(),
                'description' => $ressource->getDescription()
            ]);
            return $query->rowCount() > 0;
        } catch (PDOException $e) {
            echo 'Erreur SQL: ' . $e->getMessage();
            return false;
        }
    }*/

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


    public function updateRessource($id, $type, $titre, $lien, $description) {
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
    }
    
    

}

?>

