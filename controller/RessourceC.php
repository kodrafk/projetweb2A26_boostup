<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../model/Ressource.php');

class RessourceC {

    //  Ajouter un ressource
     function ajouterRessource($ressource) {
        $sql="INSERT INTO ressources (type, titre, lien, description, type_acces, id_thematique)
        VALUES (:type,:titre,:lien,:description , :type_acces, :id_thematique)";
        $db=config::getConnexion();
        try{
            $query = $db->prepare($sql);
            $query->execute([
                'type' => $ressource->getType(),
                'titre' => $ressource->getTitre(),
                'lien' => $ressource->getLien(),
                'description' => $ressource->getDescription(),
                'id_thematique' => $ressource->getIdThematique(),
                'type_acces' => $ressource->getTypeAcces()
            ]);

            // Log de l'action
            $action = 'Ajout';
            $resourceDetails = 'Titre: ' . $ressource->getTitre() . ', Type: ' . $ressource->getType() . ', Lien: ' . $ressource->getLien();
            $this->logHistorique($action, $resourceDetails);

        } catch (Exception $r){
            echo 'Error: ' . $r->getMessage();
        }
    }

    public function afficherRessource() {
        $sql = "SELECT 
        r.id_ressource, 
        r.titre, 
        r.type, 
        r.lien, 
        r.description, 
        r.type_acces,
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
    
    /*function deleteRessource($ide) {
        $sql="DELETE FROM ressources WHERE id_ressource = :id";
        $db=config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue('id' , $ide);
        try{
            //$req->execute();

            $success = $req->execute();

            if ($success) {
                // Ajouter log ici
                $this->logHistorique("Suppression", "Ressource ID: $ide supprimée.");
            }
            
        } catch (Exception $r){
            die ('Error: ' . $r->getMessage());
        }
    }*/

    function deleteRessource($ide) {
        $db = config::getConnexion();
    
        try {
            // 1. Récupérer le titre de la ressource
            $stmt = $db->prepare("SELECT titre FROM ressources WHERE id_ressource = :id");
            $stmt->bindValue(':id', $ide, PDO::PARAM_INT);
            $stmt->execute();
            $titreRessource = $stmt->fetchColumn();
    
            // 2. Vérifier si la ressource existe
            if ($titreRessource) {
                // 3. Supprimer la ressource
                $sql = "DELETE FROM ressources WHERE id_ressource = :id";
                $req = $db->prepare($sql);
                $req->bindValue(':id', $ide, PDO::PARAM_INT);
                $success = $req->execute();
    
                if ($success) {
                    // 4. Créer un message de log clair
                    $dateHeure = date('Y-m-d H:i:s');
                    $messageLog = "[$dateHeure] Action: Suppression | Détails: Ressource \"$titreRessource\" avec ID: $ide supprimée.";
                    $this->logHistorique("Suppression", "Ressource \"$titreRessource\" avec ID: $ide supprimée.");
                } else {
                    echo "Erreur lors de la suppression.";
                }
            } else {
                echo "Ressource non trouvée.";
            }
    
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    


    function getRessourceById($id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM ressources WHERE id_ressource = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }


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
        
        //return $query->execute();

        $success = $query->execute();

        if ($success) {
            // Ajouter log ici
            $details = "ID: $id, Titre: $titre, Type: $type, Lien: $lien";
            $this->logHistorique("Modification", $details);
        }
    
        return $success;
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

    // Log de l'historique
    function logHistorique($action, $resourceDetails) {
        $time = date('Y-m-d H:i:s');  // Heure de l'action
        $logMessage = "[$time] Action: $action | Détails: $resourceDetails\n";
        file_put_contents(__DIR__ . '/../historique.log', $logMessage, FILE_APPEND); 
    }

}

?>

