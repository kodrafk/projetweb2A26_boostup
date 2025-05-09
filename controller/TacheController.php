<?php
require_once '../../config.php';

class TacheController {
    public function listTaches() {
        $sql = "SELECT * FROM tache";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->execute();
            $result = $q->fetchAll();
            return $result;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function selectTache($id) {
        $sql = "SELECT * FROM tache WHERE id=:id";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->execute();
            $result = $q->fetch();
            return $result;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function deleteTache($id) {
        $sql = "DELETE FROM tache WHERE id=:id";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function addTache($titre, $description) {
        $sql = "INSERT INTO tache (titre, description) VALUES (:titre, :description)";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':titre', $titre);
            $q->bindValue(':description', $description);
            $q->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function updateTache($id, $titre, $description) {
        $sql = "UPDATE tache SET titre=:titre, description=:description WHERE id=:id";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->bindValue(':titre', $titre);
            $q->bindValue(':description', $description);
            $q->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function searchTacheByTitle($titre) {
        $sql = "SELECT * FROM tache WHERE titre LIKE :titre";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':titre', '%' . $titre . '%', PDO::PARAM_STR);
            $q->execute();
            $result = $q->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
?>