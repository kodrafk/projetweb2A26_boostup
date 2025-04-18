<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/../model/User.php');

class UserC {

    // Ajouter un utilisateur
    function ajouterUser($user) {
        $sql = "INSERT INTO user (email, password, type, numtel, firstName, lastName)
                VALUES (:email, :password, :type, :numtel, :firstName, :lastName)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'email'     => $user->getEmail(),
                'password'  => $user->getPassword(),
                'type'      => $user->getType(),
                'numtel'    => $user->getNumTel(),
                'firstName' => $user->getFirstName(),
                'lastName'  => $user->getLastName(),
            ]);
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Afficher tous les utilisateurs
    function afficherUsers() {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM user");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    // Supprimer un utilisateur
    function supprimerUser($ide) {
        $sql = "DELETE FROM user WHERE iduser = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $ide);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

 
    public function modifierUser($id, $firstName, $lastName, $email, $password, $type, $numtel) {
        $sql = "UPDATE user SET firstName = :firstName, lastName = :lastName, email = :email, password = :password, type = :type, numtel = :numtel WHERE iduser = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindParam(':firstName', $firstName);
            $query->bindParam(':lastName', $lastName);
            $query->bindParam(':email', $email);
            $query->bindParam(':password', $password);
            $query->bindParam(':type', $type);
            $query->bindParam(':numtel', $numtel);
            $query->bindParam(':id', $id);
            return $query->execute();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    // Récupérer un utilisateur par son ID
    function getUserById($id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare("SELECT * FROM user WHERE iduser = :id");
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
            return null;
        }
    }

}
?>
