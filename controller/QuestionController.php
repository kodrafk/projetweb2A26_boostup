<?php
require_once('Config.php');
require_once __DIR__ . '/../model/question.php';

class QuestionController {
    public function getQuestions() {
        $sql = "SELECT * FROM projetweb.question1"; // Modifié
        $db = Config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    
    public function addQuestion($question) {
        $db = Config::getConnexion();
        if(!$db) {
            error_log("Échec de connexion à la DB");
            return false;
        }
    
        try {
            $sql = "INSERT INTO projetweb.question1 (titre, date_creation, contenu) 
                   VALUES (:titre, :date_creation, :contenu)"; // Modifié
            $query = $db->prepare($sql);
            
            $params = [
                'titre' => $question->getTitre(),
                'date_creation' => $question->getDate_creation(),
                'contenu' => $question->getContenu()
            ];
            
            if($query->execute($params)) {
                return true;
            } else {
                $error = $query->errorInfo();
                error_log("Erreur d'exécution: " . print_r($error, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("Exception lors de l'insertion: " . $e->getMessage());
            return false;
        }
    }

    public function deleteQuestion($id_question) {
        $db = Config::getConnexion();
        $sql = "DELETE FROM projetweb.question1 WHERE id_question = :id_question"; // Modifié
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_question' => $id_question]);
            return true;
        } catch (Exception $e) {
            error_log('Error deleting question: ' . $e->getMessage());
            return false;
        }
    }

    public function getQuestionById($id_question) {
        $db = Config::getConnexion();
        $sql = "SELECT * FROM projetweb.question1 WHERE id_question = :id_question"; // Modifié
        try {
            $query = $db->prepare($sql);
            $query->execute(['id_question' => $id_question]);
            $result = $query->fetch();
            
            if ($result) {
                return new Question(
                    $result['id_question'],
                    $result['titre'],
                    $result['date_creation'],
                    $result['contenu']
                );
            }
            return null;
        } catch (Exception $e) {
            error_log('Error getting question by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function updateQuestion($question) {
        $db = Config::getConnexion();
        $sql = "UPDATE projetweb.question1 SET 
                titre = :titre, 
                date_creation = :date_creation, 
                contenu = :contenu 
                WHERE id_question = :id_question"; // Modifié
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id_question' => $question->getId_question(),
                'titre' => $question->getTitre(),
                'date_creation' => $question->getDate_creation(),
                'contenu' => $question->getContenu()
            ]);
            return true;
        } catch (Exception $e) {
            error_log('Error updating question: ' . $e->getMessage());
            return false;
        }
    }
}