<?php
require_once(__DIR__ . '/../model/projet.php');
require_once(__DIR__ .'/../config/database.php');
require_once(__DIR__ . '/../vendor/autoload.php'); 
use GuzzleHttp\Client;
error_reporting(E_ALL);
ini_set('display_errors', 1);

class ProjetController {
    public function ajouter() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titre = $_POST['titre'] ?? '';
            $date_debut = $_POST['date_debut'] ?? '';
            $date_fin = $_POST['date_fin'] ?? '';
            $description = $_POST['description'] ?? '';

            if (!empty($titre) && !empty($date_debut) && !empty($date_fin) && !empty($description)) {
                $projet = new Projet(null, $titre, $date_debut, $date_fin, $description);
                if ($projet->ajouter()) {
                    header("Location: projet.php?success=1");
                    exit();
                } else {
                    header("Location: projet.php?error=1");
                    exit();
                }
            } else {
                header("Location: projet.php?error=1");
                exit();
            }
        }
    }

    public function ajouterF() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titre = $_POST['titre'] ?? '';
            $date_debut = $_POST['date_debut'] ?? '';
            $date_fin = $_POST['date_fin'] ?? '';
            $description = $_POST['description'] ?? '';

            if (!empty($titre) && !empty($date_debut) && !empty($date_fin) && !empty($description)) {
                $projet = new Projet(null, $titre, $date_debut, $date_fin, $description);
                if ($projet->ajouter()) {
                    header("Location: projetF.php?success=1");
                    exit();
                } else {
                    header("Location: projetF.php?error=1");
                    exit();
                }
            } else {
                header("Location: projetF.php?error=1");
                exit();
            }
        }
    }

    public function modifierProjet() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id_projet'] ?? '';
            $titre = $_POST['titre'] ?? '';
            $date_debut = $_POST['date_debut'] ?? '';
            $date_fin = $_POST['date_fin'] ?? '';
            $description = $_POST['description'] ?? '';

            if (!empty($id) && !empty($titre) && !empty($date_debut) && !empty($date_fin) && !empty($description)) {
                $projet = new Projet($id, $titre, $date_debut, $date_fin, $description);
                if ($projet->modifier()) {
                    header("Location: projet.php?success=1");
                    exit();
                } else {
                    header("Location: projet.php?error=1");
                    exit();
                }
            } else {
                header("Location: projet.php?error=1");
                exit();
            }
        }
    }

    public function genererDescriptionAjax() {
        header('Content-Type: text/plain');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

    
        if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['description'])) {
            http_response_code(400);
            echo "Requête invalide";
            exit();
        }
    
        $apiKey = 'AIzaSyAe9FSnHXbt8_acjBuFxTB139fL6GKVn5A'; // Remplace par ta vraie clé Gemini
    
        $client = new Client();
        try {
            $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => "Améliore cette description projet : " . $_POST['description']
                                ]
                            ]
                        ]
                    ]
                ],
                'timeout' => 15
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            // Accès au texte généré (attention à bien vérifier le chemin)
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                echo $data['candidates'][0]['content']['parts'][0]['text'];
            } else {
                echo "Erreur : réponse inattendue de l'API Gemini.";
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo "Erreur API : " . $e->getMessage();
        }
        exit();
    }
    

 
    
    public function supprimer() {
        if (isset($_GET['id'])) {
            Projet::supprimerProjet($_GET['id']);
            header("Location: projet.php?success=1"); // ou projetF.php selon ta page
            exit();
        }
    }
}
