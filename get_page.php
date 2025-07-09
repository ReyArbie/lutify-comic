<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'comic_reader');

// Connect to database
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$chapter_id = $_GET['chapter_id'] ?? null;
$page_number = $_GET['page'] ?? 1;

if ($chapter_id) {
    try {
        $stmt = $pdo->prepare("SELECT image_data FROM pages WHERE chapter_id = ? AND page_number = ?");
        $stmt->execute([$chapter_id, $page_number]);
        $page = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($page) {
            header("Content-Type: image/jpeg");
            echo $page['image_data'];
            exit;
        }
    } catch (PDOException $e) {
        // Error handling
    }
}

// Fallback image if page not found
header("Content-Type: image/svg+xml");
echo '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="#ccc"/><text x="50" y="50" font-family="Arial" font-size="10" text-anchor="middle" fill="#666">No Image</text></svg>';
?>