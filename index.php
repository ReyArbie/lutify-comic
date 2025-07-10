<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lutify Comic</title>
    <link rel="stylesheet" href="index.css"/>
</head>
<body>

<?php
include 'koneksi.php';

// Ambil data komik + genre
$sql = "
    SELECT comic.id_comic, comic.title_comic, comic.cover_comic, comic.summary_comic,
           genre.action, genre.comedy, genre.romance, genre.horror, genre.adventure
    FROM comic
    LEFT JOIN genre ON comic.id_comic = genre.id_comic
    ORDER BY comic.title_comic ASC
";
$result = $conn->query($sql);

// Fungsi genre
function getGenreList($row) {
    $genres = [];
    if ($row['action'])    $genres[] = 'Action';
    if ($row['comedy'])    $genres[] = 'Comedy';
    if ($row['romance'])   $genres[] = 'Romance';
    if ($row['horror'])    $genres[] = 'Horror';
    if ($row['adventure']) $genres[] = 'Adventure';
    return implode(', ', $genres);
}

// Fungsi ringkasan
function formatSummary($text, $maxLength = 150) {
    $text = trim($text);
    $text = ucfirst($text);
    if (strlen($text) > $maxLength) {
        $text = substr($text, 0, $maxLength) . '...';
    }
    $lastChar = substr($text, -1);
    if (!in_array($lastChar, ['.', '!', '?'])) {
        $text .= '.';
    }
    return htmlspecialchars($text);
}
?>

<header>
    <div class="header">
        <h1>LUTIFY COMIC</h1>
        <form onsubmit="event.preventDefault();">
            <input placeholder="Cari Komik..." type="search" id="searchComic" />
            <button type="submit">CARI</button>
        </form>
    </div>
    <nav>
        <a href="index.php">HOME</a>
        <a href="lista-z.php">List A-Z</a>
        <a href="genre.php">Genre</a>
    </nav>
</header>

<main class="grid-content">
    <h2>SELAMAT DATANG DI LUTIFY COMIC</h2>
    <hr>

    <div class="comic-list">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id_comic = $row['id_comic'];
            $summary  = formatSummary($row['summary_comic']);
            $genre_list = getGenreList($row);
            $data_genre = strtolower(str_replace(', ', ',', $genre_list));
            // Detect MIME type (kalau ada kemungkinan bukan JPEG)
            $imgdata = $row['cover_comic'];
            $mime = (strpos($imgdata, '/9j/') === 0) ? 'jpeg' : 'png';
            $img_src = "data:image/$mime;base64," . $imgdata;                // Pakai title_comic dan urlencode untuk link
            $title_url = urlencode($row['title_comic']);
            
            echo "
            <a href='detail-comic.php?title_comic=$title_url' class='comic-card' data-genre='$data_genre' style='text-decoration:none;'>
                    <img src='$img_src' alt='" . htmlspecialchars($row['title_comic']) . "'>
                    <h3>" . htmlspecialchars($row['title_comic']) . "</h3>
                <small>$summary</small>
                <small>$genre_list</small>
            </a>
            ";
        }
    } else {
        echo "<div>Tidak ada komik ditemukan.</div>";
    }
    ?>
    </div>
</main>

</body>
</html>
=======
<?php 
  include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LUTIFY comic - Home</title>
  <link rel="stylesheet" href="./Lutify_comic_style.css" />
</head>
<body>
  <header>
    <div class="container nav-container">
      <h1 class="accent">LUTIFY comic</h1>
      <nav>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Anime List</a></li>
          <li><a href="#">Genres</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </nav>
      <div class="search-container">
        <input type="text" id="search-input" placeholder="Cari anime..." />
      </div>
    </div>
  </header>

  <main class="container">
    <section class="text-center">
      <h2 class="accent">Welcome to LUTIFY comic</h2>
      <p>Temukan anime favoritmu dan jelajahi dunia fantasi yang menakjubkan.</p>
    </section>

    <section class="cards" id="cards-container">
      <?php
        $sql = "SELECT * FROM comic";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
          
          }
        } else {
          echo '<p style="text-align:center; color:red;">Tidak ada anime ditemukan di database.</p>';
        }
      ?>

      <!-- Kartu Tambahan (manual, opsional) -->
      <div class="card">
        <img src="gambar/boruto two vortex.jpg" alt="Boruto Two Blue Vortex" />
        <h3 class="accent">Boruto: Two Blue Vortex</h3>
        <p>Boruto yang melarikan diri dari Konoha bersama Sasuke...</p>
      </div>

      <div class="card">
        <img src="gambar/heartpoundingGhosltyPoem.png" alt="Heart-Pounding Ghostly Poem" />
        <h3 class="accent">Heart-Pounding Ghostly Poem</h3>
        <p>Choi Doyeon, seorang streamer game, menerima game misterius dari Sakje-hyung...</p>
      </div>

      <div class="card">
        <img src="gambar/maxedstrength.jpg" alt="Maxed Strength Necromancer" />
        <h3 class="accent">Maxed Strength Necromancer</h3>
        <p>Qiao Yu mendapatkan kelas Necromancer dengan atribut aneh dan penuh tantangan...</p>
      </div>

      <div class="card">
        <img src="gambar/grandsonoftheloansharkking.jpg" alt="Genius Grandson of the Loan Shark King" />
        <h3 class="accent">Genius Grandson of the Loan Shark King</h3>
        <p>Kim Mu-hyuk berusaha diakui dalam dunia rentenir yang keras...</p>
      </div>

      <div class="card">
        <img src="gambar/blackhaze.jpg" alt="Black Haze" />
        <h3 class="accent">Black Haze</h3>
        <p>Pintu ke dunia lain terbuka, para penyihir bangkit untuk menyelamatkan dunia...</p>
      </div>

      <div class="card">
        <img src="gambar/ferstivalofwarriors.jpg" alt="Festival Of Warriors" />
        <h3 class="accent">Festival Of Warriors</h3>
        <p>Perang dan kekacauan menjadikan tinju satu-satunya jalan bertahan hidup...</p>
      </div>

      <div class="card">
        <img src="gambar/onestepdivinefirst.jpg" alt="One Step Divine First" />
        <h3 class="accent">One Step Divine First</h3>
        <p>Jang Geon mencoba mengubah nasib buruknya melalui jalan spiritual...</p>
      </div>

      <div class="card">
        <img src="gambar/fightingward.jpg" alt="Fighting Ward" />
        <h3 class="accent">Fighting Ward</h3>
        <p>Kang Sunwoo mengikuti turnamen bertarung demi uang dan kehormatan...</p>
      </div>

      <!-- Pesan jika pencarian tidak ditemukan -->
      <p id="no-result" style="display: none; text-align: center; color: #ed2828; margin-top: 20px;">
        Tidak ada hasil ditemukan.
      </p>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 LUTIFY comic. All Rights Reserved.</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>
>>>>>>> a39882d2f1a2613517f8186890aa9283448e88db
