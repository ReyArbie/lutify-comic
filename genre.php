<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lutify Comic</title>
    <link rel="stylesheet" href="Styles/genre.css"/>
    <link rel="stylesheet" href="Styles/footer.css"/>
</head>
<body>
<?php
    include 'koneksi.php';

    // Query ambil data comic + genre
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
            <a href="#">HOME</a>
            <a href="lista-z.php">List A-Z</a>
            <a href="genre.php">Genre</a>
        </nav>
    </header>
    
    <div class="grid-content">
        <h2>Genre</h2>
        <hr>
        <div class="genre-filter">
            <button class="genre-btn active" data-genre="all">All</button>
            <button class="genre-btn" data-genre="comedy">Comedy</button>
            <button class="genre-btn" data-genre="action">Action</button>
            <button class="genre-btn" data-genre="romance">Romance</button>
            <button class="genre-btn" data-genre="horror">Horror</button>
            <button class="genre-btn" data-genre="adventure">Adventure</button>
        </div>

        <div class="comic-list">
        <?php 
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

    <script src="js/genre.js">
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.genre-btn');
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                buttons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const genre = this.getAttribute('data-genre').toLowerCase();
                document.querySelectorAll('.comic-card').forEach(card => {
                    let cardGenres = card.getAttribute('data-genre').split(',');
                    if (genre === "all") {
                        card.style.display = "";
                    } else {
                        if (cardGenres.includes(genre)) {
                            card.style.display = "";
                        } else {
                            card.style.display = "none";
                        }
                    }
                });
            });
        });
    });
    </script>
    <?php include 'footer.php'?>
</body>
</html>
