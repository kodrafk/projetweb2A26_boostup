<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once(__DIR__.'/../../../config.php');

class AIAdvisor {
    private $db;
    private $lastError = null;
    private $recommendationsCache = [];

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function handleRequest() {
        try {
            $input = $this->getInput();
            $this->validateInput($input);
            
            if (isset($input['action'])) {
                switch ($input['action']) {
                    case 'get_details':
                        $this->handleGetDetails($input);
                        break;
                    case 'implement':
                        $this->handleImplement($input);
                        break;
                    default:
                        $this->handleAnalyze($input);
                }
            } else {
                $this->handleAnalyze($input);
            }
            
        } catch (InvalidInputException $e) {
            $this->logError("Input error: " . $e->getMessage());
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        } catch (Exception $e) {
            $this->logError("System error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Internal server error']);
        }
    }

    private function handleAnalyze($input) {
        $days = $input['days'] ?? 7;
        $recommendations = $this->analyzeData($days);
        $this->recommendationsCache = $recommendations; // Cache les résultats
        
        $this->logSuccess(count($recommendations) . " recommendations generated");
        $this->sendResponse(['recommendations' => $recommendations]);
    }

    private function handleGetDetails($input) {
        $recId = $input['rec_id'] ?? null;
        if (!$recId) {
            throw new InvalidInputException("Missing recommendation ID");
        }
        
        // Recherche dans le cache
        foreach ($this->recommendationsCache as $rec) {
            if ($rec['id'] === $recId) {
                $this->sendResponse(['recommendation' => $rec]);
                return;
            }
        }
        
        // Si pas dans le cache, génère une nouvelle analyse
        $recommendations = $this->analyzeData(7);
        foreach ($recommendations as $rec) {
            if ($rec['id'] === $recId) {
                $this->sendResponse(['recommendation' => $rec]);
                return;
            }
        }
        
        throw new Exception("Recommendation not found");
    }

    private function handleImplement($input) {
        $recId = $input['rec_id'] ?? null;
        if (!$recId) {
            throw new InvalidInputException("Missing recommendation ID");
        }
        
        // Logique d'implémentation (exemple)
        $this->logSuccess("Recommendation $recId implemented");
        
        // Enregistrer en base de données (exemple)
        try {
            $stmt = $this->db->prepare("INSERT INTO implemented_recommendations (rec_id, implemented_at) VALUES (?, NOW())");
            $stmt->execute([$recId]);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Recommendation implemented successfully'
            ]);
        } catch (PDOException $e) {
            throw new Exception("Failed to save implementation: " . $e->getMessage());
        }
    }

    private function analyzeData($days = 7) {
        $analysis = [];
        
        $userTypeStats = $this->getUserTypeStats($days);
        $countryStats = $this->getCountryStats($days);
        $hourlyStats = $this->getHourlyStats($days);
        
        $analysis = array_merge(
            $this->generateUserTypeRecommendations($userTypeStats),
            $this->generateCountryRecommendations($countryStats),
            $this->generateHourlyRecommendations($hourlyStats)
        );
        
        usort($analysis, function($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });
        
        return array_map(function($item) use ($days) {
            return [
                'id' => uniqid(),
                'type' => $item['type'],
                'title' => $item['insight'],
                'description' => $item['action'],
                'priority' => $this->getPriorityLevel($item['confidence']),
                'confidence' => $item['confidence'],
                'timestamp' => $item['timestamp'],
                'days' => $days
            ];
        }, array_slice($analysis, 0, 5));
    }

    private function getUserTypeStats($days) {
        $sql = "SELECT u.type, COUNT(*) as count 
                FROM connexions c
                JOIN user u ON c.user_id = u.iduser
                WHERE c.date_connexion >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY u.type
                ORDER BY count DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCountryStats($days) {
        $sql = "SELECT pays, COUNT(*) as count 
                FROM connexions 
                WHERE date_connexion >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY pays 
                ORDER BY count DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getHourlyStats($days) {
        $sql = "SELECT HOUR(date_connexion) as hour, COUNT(*) as count 
                FROM connexions 
                WHERE date_connexion >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY HOUR(date_connexion)
                ORDER BY hour ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function generateUserTypeRecommendations($stats) {
        $recommendations = [];
        $total = array_sum(array_column($stats, 'count'));
        
        foreach ($stats as $item) {
            $type = strtolower($item['type']);
            $percentage = round(($item['count'] / $total) * 100, 2);
            
            $recommendations[] = $this->buildRecommendation(
                'user_type_'.$type,
                ucfirst($type)."s represent $percentage% of connections",
                "Develop specific features for ".$type."s",
                min(100, $percentage + 20)
            );
        }
        
        return $recommendations;
    }

    private function generateCountryRecommendations($stats) {
        $recommendations = [];
        $total = array_sum(array_column($stats, 'count'));
        
        foreach (array_slice($stats, 0, 3) as $item) {
            $country = $item['pays'];
            $percentage = round(($item['count'] / $total) * 100, 2);
            
            $recommendations[] = $this->buildRecommendation(
                'country_'.$country,
                "$country represents $percentage% of traffic",
                "Adapt content for $country market",
                min(100, $percentage + 25)
            );
        }
        
        return $recommendations;
    }

    private function generateHourlyRecommendations($stats) {
        $recommendations = [];
        $peakHour = null;
        $maxConnections = 0;
        
        foreach ($stats as $item) {
            if ($item['count'] > $maxConnections) {
                $maxConnections = $item['count'];
                $peakHour = $item['hour'];
            }
        }
        
        if ($peakHour !== null) {
            $recommendations[] = $this->buildRecommendation(
                'peak_hour',
                "Peak activity at ".$peakHour."h ($maxConnections connections)",
                "Increase support during this time slot",
                min(100, $maxConnections)
            );
        }
        
        return $recommendations;
    }

    private function buildRecommendation($type, $insight, $action, $confidence) {
        return [
            'type' => $type,
            'insight' => $insight,
            'action' => $action,
            'confidence' => $confidence,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    private function sendResponse($data) {
        echo json_encode(array_merge(['status' => 'success'], $data));
    }

    private function getPriorityLevel($confidence) {
        if ($confidence > 80) return 'high';
        if ($confidence > 50) return 'medium';
        return 'low';
    }

    private function getInput() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidInputException("Invalid JSON format");
        }
        return $input;
    }

    private function validateInput($input) {
        if (empty($input['api_key'])) {
            throw new InvalidInputException("API key missing");
        }
        
        $validApiKey = "a7e8bf0c-1ce0-45ec-982d-c15ba8035cdf";
        if ($input['api_key'] !== $validApiKey) {
            throw new InvalidInputException("Invalid API key");
        }
    }

    private function logError($message) {
        error_log("[AI_ADVISOR_ERROR] " . date('Y-m-d H:i:s') . " - " . $message);
    }

    private function logSuccess($message) {
        error_log("[AI_ADVISOR_INFO] " . date('Y-m-d H:i:s') . " - " . $message);
    }
}

class InvalidInputException extends Exception {}

// Initialisation
try {
    $db = config::getConnexion();
    $advisor = new AIAdvisor($db);
    $advisor->handleRequest();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Initialization failed']);
}
?>