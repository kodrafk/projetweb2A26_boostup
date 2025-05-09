<?php
require_once('../../../config.php');

// Identifiants Twilio
$accountSid = 'AC23de7f15176988936eec05fa811c7590';
$authToken = 'f98535fadcfe45be16deab0e9711505d';
$twilioNumber = '+14405177047';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idRessource = $_POST['id_ressource'];

    try {
        $pdo = config::getConnexion();

        // Récupérer la ressource avec sa thématique
        $stmt = $pdo->prepare("
            SELECT r.titre, r.type, r.type_acces, r.lien, t.titre AS thematique
            FROM ressources r
            JOIN thematique t ON r.id_thematique = t.id_thematique
            WHERE r.id_ressource = ?
        ");
        $stmt->execute([$idRessource]);
        $ressource = $stmt->fetch();

        if ($ressource) {
            $type = strtolower($ressource['type']);
            $access = strtolower($ressource['type_acces']);

            // Par défaut, pas de lien QR
            $qrLink = '';

            // Si c'est un cours/évènement et accès en ligne/live, alors générer lien vers QR code
            if (($type === 'cour' || $type === 'evenement') && ($access === 'en ligne' || $access === 'live')) {
                $lien = urlencode($ressource['lien']);
                $qrLink = "https://api.qrserver.com/v1/create-qr-code/?data={$lien}&size=200x200";
            }

            // Contenu du SMS
            $messageBody = "Bonjour, le {$ressource['type']} de type {$ressource['type_acces']} intitulé '{$ressource['titre']}' dans la thématique '{$ressource['thematique']}' est démarré et il dure pendant 20 minutes.";

            // Ajouter le lien QR au message s'il existe
            if ($qrLink !== '') {
                $messageBody .= " QR Code : {$qrLink}";
            }

            // Numéro du destinataire (à adapter)
            $toPhoneNumber = '+21622210366';

            // Envoi via Twilio
            $url = "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json";
            $data = [
                'From' => $twilioNumber,
                'To' => $toPhoneNumber,
                'Body' => $messageBody,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_USERPWD, $accountSid . ':' . $authToken);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Erreur cURL: ' . curl_error($ch);
            } else {
                echo "<p>📩 Message envoyé avec succès !</p>";
                if ($qrLink !== '') {
                    echo "<p>📲 Scannez aussi ce QR Code :</p>";
                    echo "<img src='{$qrLink}' alt='QR Code'>";
                }
            }

            curl_close($ch);
        } else {
            echo "Ressource non trouvée.";
        }

    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
}
?>
