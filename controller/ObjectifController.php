<?php
require_once __DIR__ . '/../controller/ObjectifController.php';

class ObjectifController {
    // Méthodes existantes
    public function listObjectifs() {
        $sql = "SELECT * FROM objectif";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->execute();
            return $q->fetchAll();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function selectObjectif($id) {
        $sql = "SELECT * FROM objectif WHERE id=:id";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->execute();
            return $q->fetch();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Méthode modifiée pour enregistrer l'historique de suppression
    public function deleteObjectif($id) {
        $db = config::getConnexion();
        try {
            // Récupérer les infos de l'objectif avant suppression
            $objectif = $this->selectObjectif($id);
            $nom_objectif = $objectif['nom'];
            
            // Supprimer l'objectif
            $sql = "DELETE FROM objectif WHERE id=:id";
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->execute();
            
            // Enregistrer dans l'historique
            $this->ajouterHistorique($id, 'suppression', "Suppression de l'objectif: $nom_objectif");
            
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Méthode modifiée pour enregistrer l'historique d'ajout
    public function addObjectif($nom, $statut, $date_limite, $description = "") {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO objectif (nom, status, date_limite, description) VALUES (:nom, :statut, :date_limite, :description)";
            $q = $db->prepare($sql);
            $q->bindValue(':nom', $nom);
            $q->bindValue(':statut', $statut);
            $q->bindValue(':date_limite', $date_limite);
            $q->bindValue(':description', $description);
            $q->execute();
            
            // Récupérer l'ID du dernier objectif ajouté
            $objectif_id = $db->lastInsertId();
            
            // Enregistrer dans l'historique
            $this->ajouterHistorique($objectif_id, 'ajout', "Ajout de l'objectif: $nom");
            
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Méthode modifiée pour enregistrer l'historique de modification
    public function updateObjectif($id, $nom, $statut, $date_limite, $description = "") {
        $db = config::getConnexion();
        try {
            // Récupérer les anciennes valeurs
            $ancien_objectif = $this->selectObjectif($id);
            
            // Mettre à jour l'objectif
            $sql = "UPDATE objectif SET nom=:nom, status=:statut, date_limite=:date_limite, description=:description WHERE id=:id";
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->bindValue(':nom', $nom);
            $q->bindValue(':statut', $statut);
            $q->bindValue(':date_limite', $date_limite);
            $q->bindValue(':description', $description);
            $q->execute();
            
            // Préparer les détails des modifications
            $details = [];
            if ($ancien_objectif['nom'] != $nom) {
                $details[] = "Nom: {$ancien_objectif['nom']} -> $nom";
            }
            if ($ancien_objectif['status'] != $statut) {
                $details[] = "Statut: {$ancien_objectif['status']} -> $statut";
            }
            if ($ancien_objectif['date_limite'] != $date_limite) {
                $details[] = "Date limite: {$ancien_objectif['date_limite']} -> $date_limite";
            }
            
            // Enregistrer dans l'historique s'il y a des modifications
            if (!empty($details)) {
                $detail_text = "Modification de l'objectif #$id: " . implode(", ", $details);
                $this->ajouterHistorique($id, 'modification', $detail_text);
            }
            
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function searchObjectifByNom($nom) {
        $sql = "SELECT * FROM objectif WHERE nom LIKE :nom";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':nom', '%' . $nom . '%', PDO::PARAM_STR);
            $q->execute();
            return $q->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    
    // Nouvelle méthode pour récupérer les statistiques
    public function getStatistiques() {
        $db = config::getConnexion();
        try {
            $stats = [
                'freelance' => $db->query("SELECT COUNT(*) FROM objectif WHERE status = 'freelance'")->fetchColumn(),
                'stage' => $db->query("SELECT COUNT(*) FROM objectif WHERE status = 'stage'")->fetchColumn(),
                'projet_collaboratif' => $db->query("SELECT COUNT(*) FROM objectif WHERE status = 'projet collaboratif'")->fetchColumn()
            ];
            return $stats;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    
    // Nouvelle méthode pour ajouter une entrée dans l'historique
    private function ajouterHistorique($objectif_id, $action, $details) {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO historique_objectif (objectif_id, action, details) VALUES (:objectif_id, :action, :details)";
            $q = $db->prepare($sql);
            $q->bindValue(':objectif_id', $objectif_id);
            $q->bindValue(':action', $action);
            $q->bindValue(':details', $details);
            $q->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    
    // Nouvelle méthode pour récupérer l'historique
    public function getHistorique($limit = 10) {
        $db = config::getConnexion();
        try {
            $sql = "SELECT h.*, o.nom as objectif_nom 
                    FROM historique_objectif h 
                    LEFT JOIN objectif o ON h.objectif_id = o.id 
                    ORDER BY h.date_action DESC 
                    LIMIT :limit";
            $q = $db->prepare($sql);
            $q->bindValue(':limit', $limit, PDO::PARAM_INT);
            $q->execute();
            return $q->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
?>