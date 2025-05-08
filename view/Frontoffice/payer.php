<?php
// Inclure le fichier de configuration de la base de données
require_once(__DIR__ . '/../../config/database.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nouveau_paiement = (float)$_POST['montant_paye'];
    $id_projet = $_POST['id_projet'];

    try {
        $pdo = getDB();

        // 1. Récupérer les informations actuelles du projet
        $stmt = $pdo->prepare("SELECT montant, montant_paye FROM projet WHERE ID_Projet = :id_projet");
        $stmt->bindParam(':id_projet', $id_projet);
        $stmt->execute();
        $projet = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$projet) {
            die('Projet non trouvé.');
        }

        $montant_total = (float)$projet['montant'];
        $montant_deja_paye = (float)$projet['montant_paye'];
        
        // 2. Calculer le nouveau montant cumulé
        $montant_cumule = $montant_deja_paye + $nouveau_paiement;

        // 3. Vérifications
        if ($nouveau_paiement <= 0) {
            die('Le montant payé doit être positif.');
        }

        if ($montant_cumule > $montant_total) {
            $reste_a_payer = $montant_total - $montant_deja_paye;
            die("Le montant payé dépasse le montant restant. Il reste à payer : $reste_a_payer €");
        }

        // 4. Mettre à jour le montant payé dans la table projet (cumul)
        $stmt_update = $pdo->prepare("UPDATE projet SET montant_paye = :montant_cumule WHERE ID_Projet = :id_projet");
        $stmt_update->bindParam(':montant_cumule', $montant_cumule);
        $stmt_update->bindParam(':id_projet', $id_projet);
        $stmt_update->execute();

        // 5. Enregistrer dans la session pour le checkout
        $_SESSION['montant_paye'] = $nouveau_paiement; // On enregistre seulement le nouveau paiement
        $_SESSION['montant_total_paye'] = $montant_cumule; // Montant total cumulé
        $_SESSION['id_projet'] = $id_projet;

        // 6. Rediriger vers checkout.php
        header("Location: /BoostUp/checkout.php");
        exit;

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>