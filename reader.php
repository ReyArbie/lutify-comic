<?php
// Database connection
$db = new PDO('mysql:host=localhost;dbname=lutify_comic;charset=utf8', 'root', '');

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
        :root {
            --bg-dark: #121212;
            --bg-darker: #0a0a0a;
            --accent: #ff7b00;
            --accent-glow: rgba(255, 123, 0, 0.3);
            --text-primary: #e0e0e0;
            --text-secondary: #b0b0b0;
        }
        
        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            background-color: var(--bg-darker);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--accent);
            box-shadow: 0 0 15px var(--accent-glow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .home-button {
            color: var(--accent);
            text-decoration: none;
            font-weight: bold;
            font-size: 1.5rem;
            text-shadow: 0 0 8px var(--accent-glow);
            transition: all 0.2s ease;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }
        
        .home-button:hover {
            background-color: var(--accent);
            color: var(--bg-darker);
            box-shadow: 0 0 15px var(--accent-glow);
        }
        
        .comic-title {
            margin: 0;
            color: var(--accent);
            text-shadow: 0 0 8px var(--accent-glow);
            font-size: 1.8rem;
        }
        
        .chapter-info {
            margin: 0.5rem 0 0;
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        .reader-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            background: radial-gradient(circle at center, #1a1a1a 0%, var(--bg-dark) 100%);
        }
        
        .comic-frame {
            border: 8px solid var(--accent);
            border-radius: 4px;
            box-shadow: 0 0 25px var(--accent-glow), 
                        inset 0 0 15px rgba(0, 0, 0, 0.5);
            background-color: black;
            padding: 10px;
            margin: 2rem 0;
            max-width: 95%;
            transition: all 0.3s ease;
        }
        
        .comic-frame:hover {
            box-shadow: 0 0 35px var(--accent-glow), 
                        inset 0 0 20px rgba(0, 0, 0, 0.7);
        }
        
        .comic-image {
            max-width: 100%;
            max-height: 80vh;
            display: block;
            margin: 0 auto;
        }
        
        .nav-buttons {
            display: flex;
            gap: 1.5rem;
            margin: 1.5rem 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .nav-button {
            padding: 0.8rem 1.8rem;
            background-color: var(--bg-darker);
            color: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            box-shadow: 0 0 10px var(--accent-glow);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .nav-button:hover {
            background-color: var(--accent);
            color: var(--bg-darker);
            box-shadow: 0 0 20px var(--accent-glow);
            transform: translateY(-2px);
        }
        
        .nav-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: var(--bg-darker);
            color: var(--text-secondary);
            border-color: var(--text-secondary);
            box-shadow: none;
        }
        
        .nav-button:disabled:hover {
            transform: none;
            background-color: var(--bg-darker);
        }
        
        .footer {
            text-align: center;
            padding: 1rem;
            background-color: var(--bg-darker);
            border-top: 1px solid var(--accent);
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem;
            }
            
            .header-left {
                width: 100%;
                justify-content: space-between;
            }
            
            .reader-container {
                padding: 1rem;
            }
            
            .comic-title {
                font-size: 1.4rem;
            }
            
            .nav-button {
                padding: 0.6rem 1.2rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <a href="index.php" class="home-button">Lutify Comic</a>
            <div>
                <h1 class="comic-title"><?= htmlspecialchars($comic['title_comic']) ?></h1>
                <div class="chapter-info">Chapter <?= $chapter ?> • Page <?= $page ?> of <?= $maxPage ?></div>
            </div>
        </div>
    </div>
    
    <div class="reader-container">
        <?php if ($imageData && !empty($imageData['content'])): ?>
            <div class="comic-frame">
                <img class="comic-image" src="data:image/jpeg;base64,<?= $imageData['content'] ?>" alt="Page <?= $page ?>">
            </div>
        <?php else: ?>
            <div class="error" style="color: var(--accent); text-align: center; padding: 2rem;">
                Page not found
            </div>
        <?php endif; ?>
        
        <div class="nav-buttons">
            <?php $prevLink = prevPage($comicId, $chapter, $page); ?>
            <a href="<?= $prevLink ?: '#' ?>" class="nav-button" <?= !$prevLink ? 'disabled' : '' ?>>
                ← Previous
            </a>
            
            <?php $nextLink = nextPage($comicId, $chapter, $page, $maxPage, $maxChapter); ?>
            <a href="<?= $nextLink ?: '#' ?>" class="nav-button" <?= !$nextLink ? 'disabled' : '' ?>>
                Next →
            </a>
        </div>
    </div>
    
    <div class="footer">
        Lutify Comic Reader • Use arrow keys or click buttons to navigate
    </div>

    <script>
        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            <?php if ($nextLink): ?>
                if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'd') {
                    window.location.href = '<?= $nextLink ?>';
                    e.preventDefault();
                }
            <?php endif; ?>
            
            <?php if ($prevLink): ?>
                if (e.key === 'ArrowLeft' || e.key === 'a') {
                    window.location.href = '<?= $prevLink ?>';
                    e.preventDefault();
                }
            <?php endif; ?>
            
            // Home/End navigation
            if (e.key === 'Home') {
                window.location.href = '?comic=<?= $comicId ?>&chapter=1&page=1';
                e.preventDefault();
            }
            
            if (e.key === 'End') {
                window.location.href = '?comic=<?= $comicId ?>&chapter=<?= $maxChapter ?>&page=1';
                e.preventDefault();
            }
            
            // Escape key to go home
            if (e.key === 'Escape') {
                window.location.href = 'index.php';
                e.preventDefault();
            }
        });
        
        // Add button click animation
        document.querySelectorAll('.nav-button:not(:disabled), .home-button').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'translateY(2px)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        });
    </script>
</body>
</html>