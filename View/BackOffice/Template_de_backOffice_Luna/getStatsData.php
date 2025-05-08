
<?php
header('Content-Type: application/json');

include_once(__DIR__ . '/../../../config.php');
$conn = config::getConnexion();

// Récupérer la période (7 jours par défaut)
$days = isset($_GET['days']) ? (int)$_GET['days'] : 7;

$response = [
    'daily' => ['labels' => [], 'data' => []],
    'countries' => ['labels' => [], 'data' => []],
    'hours' => ['labels' => [], 'data' => []],
    'userTypes' => ['labels' => [], 'data' => []]
];

// 1. Connexions par jour
$sql = "SELECT 
            DATE(date_connexion) as day, 
            COUNT(*) as count 
        FROM connexions 
        WHERE date_connexion >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY DATE(date_connexion)
        ORDER BY day ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$days]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Remplir les jours manquants
$startDate = new DateTime("-{$days} days");
$endDate = new DateTime();
$interval = new DateInterval('P1D');
$period = new DatePeriod($startDate, $interval, $endDate);

foreach ($period as $date) {
    $formattedDate = $date->format('Y-m-d');
    $response['daily']['labels'][] = $date->format('d M');
    
    $found = false;
    foreach ($results as $row) {
        if ($row['day'] == $formattedDate) {
            $response['daily']['data'][] = (int)$row['count'];
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $response['daily']['data'][] = 0;
    }
}

// 2. Répartition par pays (top 5)
$sql = "SELECT 
            pays, 
            COUNT(*) as count 
        FROM connexions 
        WHERE date_connexion >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY pays 
        ORDER BY count DESC 
        LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute([$days]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $response['countries']['labels'][] = $row['pays'];
    $response['countries']['data'][] = (int)$row['count'];
}

// 3. Connexions par heure de la journée
$sql = "SELECT 
            HOUR(date_connexion) as hour, 
            COUNT(*) as count 
        FROM connexions 
        WHERE date_connexion >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY HOUR(date_connexion)
        ORDER BY hour ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$days]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Remplir toutes les heures (0-23)
for ($hour = 0; $hour < 24; $hour++) {
    $response['hours']['labels'][] = sprintf("%02dh", $hour);
    
    $found = false;
    foreach ($results as $row) {
        if ($row['hour'] == $hour) {
            $response['hours']['data'][] = (int)$row['count'];
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $response['hours']['data'][] = 0;
    }
}

// 4. Répartition par type d'utilisateur
$sql = "SELECT 
            u.type, 
            COUNT(*) as count 
        FROM connexions c
        JOIN user u ON c.user_id = u.iduser
        WHERE c.date_connexion >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY u.type
        ORDER BY count DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$days]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $response['userTypes']['labels'][] = ucfirst($row['type']);
    $response['userTypes']['data'][] = (int)$row['count'];
}

echo json_encode($response);
?>