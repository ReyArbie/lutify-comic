<?php
include "koneksi.php";

// Ambil title_comic dari parameter GET
$title_comic = isset($_GET['title_comic']) ? $_GET['title_comic'] : '';
if (!$title_comic) die("Comic tidak ditemukan.");

// Jika pakai URL, biasanya judul sudah di-urlencode, jadi kita urldecode dulu
$title_comic = urldecode($title_comic);

// Query detail komik dan genre (pakai prepared statement)
$stmt = $conn->prepare("SELECT c.id_comic, c.title_comic, c.summary_comic, c.cover_comic,
                               g.action, g.comedy, g.romance, g.horror, g.adventure
                        FROM comic c
                        LEFT JOIN genre g ON c.id_comic = g.id_comic
                        WHERE c.title_comic = ?");
$stmt->bind_param("s", $title_comic);
$stmt->execute();
$result = $stmt->get_result();
if(!$row = $result->fetch_assoc()) die("Data komik tidak ditemukan.");

// Genre
$genre_list = [];
if ($row['action']) $genre_list[] = 'Action';
if ($row['comedy']) $genre_list[] = 'Comedy';
if ($row['romance']) $genre_list[] = 'Romance';
if ($row['horror']) $genre_list[] = 'Horror';
if ($row['adventure']) $genre_list[] = 'Adventure';
$genre_str = implode(' | ', $genre_list);

// Total Episode/Chapter dari tabel image
$total_episodes = 0;
$id_comic = $row['id_comic'];
$ep_sql = "SELECT COUNT(DISTINCT id_chapter) as total FROM image WHERE id_comic = $id_comic";
$ep_result = $conn->query($ep_sql);
if ($ep_result && $ep_row = $ep_result->fetch_assoc()) {
    $total_episodes = $ep_row['total'];
}

// Cover image
$imgdata = $row['cover_comic'];
$mime = (strpos($imgdata, '/9j/') === 0) ? 'jpeg' : 'png';

// Ambil daftar chapter (id_chapter unik dan jumlah halaman per chapter)
$chapter_sql = "SELECT id_chapter, MAX(id_page) as pages 
                FROM image 
                WHERE id_comic = $id_comic 
                GROUP BY id_chapter 
                ORDER BY id_chapter ASC";
$chapter_result = $conn->query($chapter_sql);

$chapter_list = [];
if ($chapter_result) {
    while ($ch_row = $chapter_result->fetch_assoc()) {
        $chapter_list[] = [
            'id' => $ch_row['id_chapter'],
            'pages' => $ch_row['pages']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($row['title_comic']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="Styles/detail-comic.css" />
</head>
<body>
    <header>
      <div class="nav-wrap">
        <div class="site-title">LUTIFY comic</div>
        <nav>
          <ul>
            <li><a href="genre.php">Home</a></li>
            <li><a href="lista-z.php">Anime List</a></li>
            <li><a href="genre.php">Genres</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </nav>
      </div>
    </header>
    <section class="welcome-section">
      <h2>Welcome to LUTIFY comic</h2>
      <p>Temukan komik favoritmu dan jelajahi dunia fantasi yang menakjubkan.</p>
    </section>
    <main class="container-content">
      <!-- Banner Gambar -->
      <section class="banner" aria-label="Poster <?= htmlspecialchars($row['title_comic']) ?>">
        <img src="data:image/<?= $mime ?>;base64,<?= $imgdata ?>"
             alt="<?= htmlspecialchars($row['title_comic']) ?>" />
      </section>
      <!-- Info -->
      <section class="movie-info" aria-label="Informasi serial <?= htmlspecialchars($row['title_comic']) ?>">
        <h2 class="movie-title"><?= htmlspecialchars($row['title_comic']) ?></h2>
        <div class="details">
          <span><?= $genre_str ?></span>
          <?php if ($total_episodes): ?>
            <span>• <?= $total_episodes ?> Episode</span>
          <?php endif; ?>
        </div>
        <p class="description"><?= nl2br(htmlspecialchars($row['summary_comic'])) ?></p>
      </section>
      
      <!-- Daftar Chapter -->
      <?php if (!empty($chapter_list)): ?>
      <section class="chapter-list" aria-label="Daftar Chapter">
          <h3>Daftar Chapter</h3>
          <ul class="episode-list">
              <?php foreach ($chapter_list as $ch): ?>
                  <li>
                      <a class="episode-link" href="reader.php?comic=<?= $id_comic ?>&chapter=<?= $ch['id'] ?>&page=1">
                          <span class="episode-label">Chapter <?= $ch['id'] ?></span>
                          <div class="episode-info">
                              <span class="episode-number"><?= $ch['pages'] ?> halaman</span>
                          </div>
                      </a>
                  </li>
              <?php endforeach; ?>
          </ul>
      </section>
      <?php endif; ?>

    </main>
    <footer>
      <p>© 2025 Website Comic/Manga. Dibuat oleh Rakha Adhitya.</p>
    </footer>
</body>
</html>
