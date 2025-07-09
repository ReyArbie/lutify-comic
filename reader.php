<?php
// Database connection
$db = new PDO('mysql:host=localhost;dbname=lutify;charset=utf8', 'root', '');

// Get current comic, chapter, and page from URL or defaults
$comicId = isset($_GET['comic']) ? (int)$_GET['comic'] : 1;
$chapter = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Get comic info
$stmt = $db->prepare("SELECT title_comic FROM comic WHERE id_comic = ?");
$stmt->execute([$comicId]);
$comic = $stmt->fetch(PDO::FETCH_ASSOC);

// Get total chapters for this comic
$stmt = $db->prepare("SELECT MAX(id_chapter) as max_chapter FROM image WHERE id_comic = ?");
$stmt->execute([$comicId]);
$maxChapter = $stmt->fetch(PDO::FETCH_ASSOC)['max_chapter'];

// Get current page image
$stmt = $db->prepare("SELECT content FROM image WHERE id_comic = ? AND id_chapter = ? AND id_page = ?");
$stmt->execute([$comicId, $chapter, $page]);
$imageData = $stmt->fetch(PDO::FETCH_ASSOC);

// Get total pages in current chapter
$stmt = $db->prepare("SELECT MAX(id_page) as max_page FROM image WHERE id_comic = ? AND id_chapter = ?");
$stmt->execute([$comicId, $chapter]);
$maxPage = $stmt->fetch(PDO::FETCH_ASSOC)['max_page'];

// Navigation functions
function nextPage($comicId, $chapter, $page, $maxPage, $maxChapter) {
    if ($page < $maxPage) {
        return "?comic=$comicId&chapter=$chapter&page=".($page+1);
    } elseif ($chapter < $maxChapter) {
        return "?comic=$comicId&chapter=".($chapter+1)."&page=1";
    }
    return null;
}

function prevPage($comicId, $chapter, $page) {
    if ($page > 1) {
        return "?comic=$comicId&chapter=$chapter&page=".($page-1);
    } elseif ($chapter > 1) {
        // Need to get max page of previous chapter
        global $db;
        $stmt = $db->prepare("SELECT MAX(id_page) as max_page FROM image WHERE id_comic = ? AND id_chapter = ?");
        $stmt->execute([$comicId, $chapter-1]);
        $prevMaxPage = $stmt->fetch(PDO::FETCH_ASSOC)['max_page'];
        return "?comic=$comicId&chapter=".($chapter-1)."&page=$prevMaxPage";
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($comic['title_comic']) ?> - Chapter <?= $chapter ?> Page <?= $page ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #222;
            color: #fff;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }
        .reader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 20px 0;
        }
        .comic-title {
            margin-bottom: 10px;
        }
        .chapter-info {
            margin-bottom: 20px;
        }
        .comic-image {
            max-width: 100%;
            max-height: 80vh;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }
        .nav-buttons {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .nav-button {
            padding: 10px 20px;
            background-color: #444;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .nav-button:hover {
            background-color: #555;
        }
        .nav-button:disabled {
            background-color: #333;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="reader-container">
        <h1 class="comic-title"><?= htmlspecialchars($comic['title_comic']) ?></h1>
        <div class="chapter-info">Chapter <?= $chapter ?> - Page <?= $page ?></div>
        
        <?php if ($imageData && !empty($imageData['content'])): ?>
            <img class="comic-image" src="data:image/jpeg;base64,<?= $imageData['content'] ?>" alt="Page <?= $page ?>">
        <?php else: ?>
            <div class="error">Page not found</div>
        <?php endif; ?>
        
        <div class="nav-buttons">
            <?php $prevLink = prevPage($comicId, $chapter, $page); ?>
            <a href="<?= $prevLink ?: '#' ?>" class="nav-button" <?= !$prevLink ? 'disabled' : '' ?>>Previous</a>
            
            <?php $nextLink = nextPage($comicId, $chapter, $page, $maxPage, $maxChapter); ?>
            <a href="<?= $nextLink ?: '#' ?>" class="nav-button" <?= !$nextLink ? 'disabled' : '' ?>>Next</a>
        </div>
    </div>

    <script>
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            <?php if ($nextLink): ?>
                if (e.key === 'ArrowRight' || e.key === ' ') {
                    window.location.href = '<?= $nextLink ?>';
                }
            <?php endif; ?>
            
            <?php if ($prevLink): ?>
                if (e.key === 'ArrowLeft') {
                    window.location.href = '<?= $prevLink ?>';
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>