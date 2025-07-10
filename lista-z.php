<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lutify Comic</title>
    <link rel="stylesheet" href="Styles/lista-z.css">
    <link rel="stylesheet" href="Styles/footer.css">
    <link rel="stylesheet" href="Styles/header.css">
</head>
<body>
    <?php include 'header.php'?>

    <div class="grid-content">
        <h2>List A-Z</h2>
        <hr>
        <div id="filter-buttons"> 
            <button data-letter="A">A</button><button data-letter="B">B</button><button data-letter="C">C</button>
            <button data-letter="D">D</button><button data-letter="E">E</button>
            <button data-letter="F">F</button><button data-letter="G">G</button><button data-letter="H">H</button>
            <button data-letter="I">I</button><button data-letter="J">J</button>
            <button data-letter="K">K</button><button data-letter="L">L</button><button data-letter="M">M</button>
            <button data-letter="N">N</button><button data-letter="O">O</button>
            <button data-letter="P">P</button><button data-letter="Q">Q</button><button data-letter="R">R</button>
            <button data-letter="S">S</button><button data-letter="T">T</button>
            <button data-letter="U">U</button><button data-letter="V">V</button><button data-letter="W">W</button>
            <button data-letter="X">X</button><button data-letter="Y">Y</button>
            <button data-letter="Z">Z</button><button data-letter="0-9">0-9</button><button data-letter="#">#</button>
        </div>

        <div class="comic-list">
        <?php
        include 'koneksi.php';

        $sql = "SELECT comic.id_comic, comic.title_comic, comic.cover_comic, 
                        genre.action, genre.comedy, genre.romance, genre.horror, genre.adventure
                FROM comic
                LEFT JOIN genre ON comic.id_comic = genre.id_comic
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
        ?>
        </div>
    </div>

    <script src="js/lista-z.js"></script>
    <?php include 'footer.php' ?>
</body>
</html>
