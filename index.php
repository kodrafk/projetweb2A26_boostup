<?php
// Enable error reporting at the very top
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define absolute base path (points to the 'events' directory)
define('BASE_PATH', realpath(dirname(__FILE__)));

// Load database configuration
require_once __DIR__ . '/config/database.php';

try {
    // Initialize database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // Test connection
    $db->query("SELECT 1");
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle routing
$action = $_GET['action'] ?? 'index';
$controller = $_GET['controller'] ?? 'event';

// Prepare controller
$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile = BASE_PATH . '/controllers/' . $controllerClass . '.php';

// Verify controller exists
if (!file_exists($controllerFile)) {
    die("Controller file not found: " . $controllerFile);
}

require_once $controllerFile;

try {
    $controllerInstance = new $controllerClass($db);
    
    if (in_array($action, ['edit', 'delete'])) {
        $id = $_GET['id'] ?? null;
        if ($id && ctype_digit($id)) {
            $controllerInstance->$action((int)$id);
        } else {
            header("Location: index.php?action=index");
            exit();
        }
    } elseif (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        throw new Exception("Action {$action} not found");
    }
} catch(Exception $e) {
    // Error handling
    $errorMessage = "Application Error: " . $e->getMessage();
    error_log($errorMessage);
    
    if (!headers_sent()) {
        header("HTTP/1.1 500 Internal Server Error");
    }
    
    $errorPage = BASE_PATH . '/views/errors/500.php';
    if (file_exists($errorPage)) {
        require $errorPage;
    } else {
        die($errorMessage);
    }
    exit();
}