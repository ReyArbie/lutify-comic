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