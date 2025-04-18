<?php
require_once '../../config.php';

class ObjectifController {
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

    public function deleteObjectif($id) {
        $sql = "DELETE FROM objectif WHERE id=:id";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function addObjectif($nom, $statut, $date_limite) {
        $sql = "INSERT INTO objectif (nom, statut, date_limite) VALUES (:nom, :statut, :date_limite)";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':nom', $nom);
            $q->bindValue(':statut', $statut);
            $q->bindValue(':date_limite', $date_limite);
            $q->execute();
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function updateObjectif($id, $nom, $statut, $date_limite) {
        $sql = "UPDATE objectif SET nom=:nom, statut=:statut, date_limite=:date_limite WHERE id=:id";
        $db = config::getConnexion();
        try {
            $q = $db->prepare($sql);
            $q->bindValue(':id', $id);
            $q->bindValue(':nom', $nom);
            $q->bindValue(':statut', $statut);
            $q->bindValue(':date_limite', $date_limite);
            $q->execute();
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
}
?>
