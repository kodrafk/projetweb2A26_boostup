<?php
// config.php - Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'projetweb');
define('DB_USER', 'root');
define('DB_PASS', '');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to get database connection
function getDBConnection() {
    try {
        $db = new PDO(
            "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", 
            DB_USER, 
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        return $db;
    } catch(PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Function to fetch all events
function getAllEvents($db) {
    try {
        $stmt = $db->query("
            SELECT id, title, event_date, location, link 
            FROM events 
            ORDER BY event_date DESC
        ");
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        die("Error fetching events: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Viewer</title>
    <style>
        :root {
            --primary-color: #975af4;
            --secondary-color: #2f7cf8;
            --accent-color: #78aafa;
            --dark-bg: #161a20;
            --text-light: #ffffff;
            --text-muted: #838383;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            padding: 2rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .subtitle {
            color: #64748b;
            font-size: 1.1rem;
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .event-card {
            background: linear-gradient(
                to top right,
                var(--primary-color),
                var(--secondary-color) 40%,
                var(--accent-color) 65%,
                #934cff 100%
            );
            padding: 4px;
            border-radius: 32px;
            transition: transform 0.3s ease;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
        }
        
        .card-content {
            background-color: var(--dark-bg);
            border-radius: 30px;
            padding: 1.5rem;
            height: 100%;
            color: var(--text-muted);
        }
        
        .event-title {
            color: var(--text-light);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .event-title svg {
            width: 20px;
            height: 20px;
        }
        
        .event-detail {
            display: flex;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        
        .detail-label {
            font-weight: 500;
            min-width: 80px;
            color: #bab9b9;
        }
        
        .detail-value {
            color: var(--text-light);
            word-break: break-word;
        }
        
        .event-link {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .event-link:hover {
            color: #a7c6ff;
            text-decoration: underline;
        }
        
        .no-events {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .error-message {
            grid-column: 1 / -1;
            padding: 2rem;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Events Viewer</h1>
            <p class="subtitle">View-only mode - No modifications allowed</p>
        </header>
        
        <main class="events-grid">
            <?php
            $db = getDBConnection();
            $events = getAllEvents($db);
            
            if (empty($events)): ?>
                <div class="no-events">
                    <p>No events found in the database.</p>
                    <p>Please check if the events table exists and contains data.</p>
                </div>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="card-content">
                            <div class="event-title">
                                <span><?= htmlspecialchars($event['title']) ?></span>
                                <svg viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M10.277 16.515c.005-.11.187-.154.24-.058c.254.45.686 1.111 1.177 1.412c.49.3 1.275.386 1.791.408c.11.005.154.186.058.24c-.45.254-1.111.686-1.412 1.176s-.386 1.276-.408 1.792c-.005.11-.187.153-.24.057c-.254-.45-.686-1.11-1.176-1.411s-1.276-.386-1.792-.408c-.11-.005-.153-.187-.057-.24c.45-.254 1.11-.686 1.411-1.177c.301-.49.386-1.276.408-1.791m8.215-1c-.008-.11-.2-.156-.257-.062c-.172.283-.421.623-.697.793s-.693.236-1.023.262c-.11.008-.155.2-.062.257c.283.172.624.42.793.697s.237.693.262 1.023c.009.11.2.155.258.061c.172-.282.42-.623.697-.792s.692-.237 1.022-.262c.11-.009.156-.2.062-.258c-.283-.172-.624-.42-.793-.697s-.236-.692-.262-1.022M14.704 4.002l-.242-.306c-.937-1.183-1.405-1.775-1.95-1.688c-.545.088-.806.796-1.327 2.213l-.134.366c-.149.403-.223.604-.364.752c-.143.148-.336.225-.724.38l-.353.141l-.248.1c-1.2.48-1.804.753-1.881 1.283c-.082.565.49 1.049 1.634 2.016l.296.25c.325.275.488.413.58.6c.094.187.107.403.134.835l.024.393c.093 1.52.14 2.28.634 2.542s1.108-.147 2.336-.966l.318-.212c.35-.233.524-.35.723-.381c.2-.032.402.024.806.136l.368.102c1.422.394 2.133.591 2.52.188c.388-.403.196-1.14-.19-2.613l-.099-.381c-.11-.419-.164-.628-.134-.835s.142-.389.365-.752l.203-.33c.786-1.276 1.179-1.914.924-2.426c-.254-.51-.987-.557-2.454-.648l-.379-.024c-.417-.026-.625-.039-.806-.135c-.18-.096-.314-.264-.58-.6m-5.869 9.324C6.698 14.37 4.919 16.024 4.248 18c-.752-4.707.292-7.747 1.965-9.637c.144.295.332.539.5.73c.35.396.852.82 1.362 1.251l.367.31l.17.145c.005.064.01.14.015.237l.03.485c.04.655.08 1.294.178 1.805"/>
                                </svg>
                            </div>
                            
                            <div class="event-detail">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value">
                                    <?= !empty($event['event_date']) ? 
                                        date('F j, Y', strtotime($event['event_date'])) : 
                                        'Not specified' ?>
                                </span>
                            </div>
                            
                            <div class="event-detail">
                                <span class="detail-label">Location:</span>
                                <span class="detail-value">
                                    <?= !empty($event['location']) ? 
                                        htmlspecialchars($event['location']) : 
                                        'Not specified' ?>
                                </span>
                            </div>
                            
                            <div class="event-detail">
                                <span class="detail-label">Link:</span>
                                <span class="detail-value">
                                    <?= !empty($event['link']) ? 
                                        '<a href="'.htmlspecialchars($event['link']).'" class="event-link" target="_blank">View Details</a>' : 
                                        'None' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>