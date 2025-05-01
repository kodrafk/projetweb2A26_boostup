<?php
session_start(); // Ajouter cette ligne en haut
require_once(__DIR__ . '/../../../config.php');
include '../../../Controller/ThematiqueC.php';

// Activer le rapport d'erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if (!isset($_POST['id_thematique'])) {
        throw new Exception("Aucun ID reçu");
    }

    $id = (int)$_POST['id_thematique']; // Conversion en entier
    
    $ThematiqueC = new ThematiqueC();
    $result = $ThematiqueC->deleteThematique($id);

    if ($result) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Thématique supprimée avec succès'
        ];
    } else {
        throw new Exception("La suppression a échoué");
    }

} catch (Exception $e) {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Erreur : ' . $e->getMessage()
    ];
}

header("Location: thematique.php");
exit();
?>