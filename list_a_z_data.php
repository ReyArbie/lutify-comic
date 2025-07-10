<?php
include 'koneksi.php';

$letter = isset($_GET['letter']) ? $_GET['letter'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$where = [];
if ($letter) {
    if ($letter == '0-9') {
        $where[] = "comic.title_comic REGEXP '^[0-9]'";
    } else if ($letter == '#') {
        $where[] = "comic.title_comic REGEXP '^[^A-Za-z0-9]'";
    } else {
        $where[] = "comic.title_comic LIKE '" . $conn->real_escape_string($letter) . "%'";
    }
}
if ($search) {
    $where[] = "comic.title_comic LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT comic.*, genre.action, genre.comedy, genre.romance, genre.horror, genre.adventure
        FROM comic
        INNER JOIN genre ON comic.id_comic = genre.id_comic
        $where_sql
        ORDER BY comic.title_comic ASC";
$result = $conn->query($sql);

function getGenreList($row) {
    $genres = [];
    if ($row['action']) $genres[] = 'Action';
    if ($row['comedy']) $genres[] = 'Comedy';
    if ($row['romance']) $genres[] = 'Romance';
    if ($row['horror']) $genres[] = 'Horror';
    if ($row['adventure']) $genres[] = 'Adventure';
    return implode(', ', $genres);
}

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $genre_list = getGenreList($row);
                $data_genre = strtolower(str_replace(', ', ',', $genre_list));
                // Detect MIME type (kalau ada kemungkinan bukan JPEG)
                $imgdata = $row['cover_comic'];
                $mime = (strpos($imgdata, '/9j/') === 0) ? 'jpeg' : 'png';
                $img_src = "data:image/$mime;base64," . $imgdata;
                // Pakai title_comic dan urlencode untuk link
                $title_url = urlencode($row['title_comic']);
                echo "
                <a href='detail-comic.php?title_comic=$title_url' class='comic-card' data-genre='$data_genre' style='text-decoration:none;'>
                    <img src='$img_src' alt='" . htmlspecialchars($row['title_comic']) . "'>
                    <h3>" . htmlspecialchars($row['title_comic']) . "</h3>
                    <small>$genre_list</small>
                </a>
                ";
            }
        } else {
            echo "<div>Tidak ada komik ditemukan.</div>";
        }
$conn->close();
?>
