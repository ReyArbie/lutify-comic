<?php
include 'config/config.php';

// Ambil daftar komik
$comics = [];
$sql_comics = "SELECT id_comic, title_comic FROM comic";
$result_comics = $conn->query($sql_comics);
if ($result_comics) {
    while ($row = $result_comics->fetch_assoc()) {
        $comics[] = $row;
    }
}

// Proses tambah episode baru dengan multi-image
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comic_id = intval($_POST['komik']);
    $chapter_id = intval($_POST['nomorEpisode']);

    if (isset($_FILES['fileEpisode'])) {
        $files = $_FILES['fileEpisode'];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === 0) {
                $tmpName = $files['tmp_name'][$i];
                $imgData = file_get_contents($tmpName);
                $b64 = base64_encode($imgData);

                $page_number = $i + 1; // urutan halaman
                // Insert ke tabel image
                $stmt = $conn->prepare("INSERT INTO image (id_comic, id_chapter, id_page, content) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiis", $comic_id, $chapter_id, $page_number, $b64);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Redirect agar tidak dobel submit
    header("Location: episode.php");
    exit();
}

// Ambil daftar episode (chapter) per komik (distinct by id_comic & id_chapter)
$episodes = [];
$sql = "SELECT i.id_comic, c.title_comic, i.id_chapter 
        FROM image i 
        JOIN comic c ON i.id_comic = c.id_comic 
        GROUP BY i.id_comic, i.id_chapter 
        ORDER BY i.id_comic DESC, i.id_chapter DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $episodes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Episode - LUTIFY COMIC</title>
    <link rel="stylesheet" href="css/episode.css" />
    <style>
        .episode-images { display: flex; flex-wrap: wrap; gap: 8px; }
        .episode-images img { max-width: 80px; max-height: 120px; border: 1px solid #ddd; }
    </style>
</head>
<body>
<header>
    <h1>Kelola Chapter</h1>
    <p>Daftar Chapter untuk setiap comic dan tambah Chapter baru</p>
</header>

<nav>
  <div class="nav-center">
    <a href="index.php">Dashboard</a>
    <a href="kelola-comic.php">Kelola Comic</a>
    <a href="chapter.php">Kelola Chapter</a>
    <a href="upload.php">Upload Comic</a>
  </div>
  <a href="logout.php" class="logout-btn">Logout</a>
</nav>


<div class="container">
    <h2>Daftar Chapter</h2>
    <table>
        <thead>
            <tr>
                <th>Judul Comic</th>
                <th>Nomor Chapter</th>
                <th>Halaman</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($episodes as $episode): ?>
            <tr>
                <td><?= htmlspecialchars($episode['title_comic']) ?></td>
                <td><?= htmlspecialchars($episode['id_chapter']) ?></td>
                <td>
                    <?php
                    // Ambil semua halaman pada episode ini
                    $stmtImg = $conn->prepare("SELECT id_page, content FROM image WHERE id_comic = ? AND id_chapter = ? ORDER BY id_page ASC");
                    $stmtImg->bind_param("ii", $episode['id_comic'], $episode['id_chapter']);
                    $stmtImg->execute();
                    $resultImg = $stmtImg->get_result();
                    echo '<div class="episode-images">';
                    while ($imgRow = $resultImg->fetch_assoc()) {
                        echo '<img src="data:image/jpeg;base64,' . $imgRow['content'] . '" alt="Page ' . $imgRow['id_page'] . '">';
                    }
                    echo '</div>';
                    $stmtImg->close();
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <hr />
    <h2>Tambah Chapter Baru</h2>
    <form class="form-episode" action="episode.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="komik">Pilih Komik:</label>
            <select id="komik" name="komik" required>
                <option value="">-- Pilih Komik --</option>
                <?php foreach ($comics as $komik): ?>
                    <option value="<?= $komik['id_comic'] ?>"><?= htmlspecialchars($komik['title_comic']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nomorEpisode">Nomor Episode:</label>
            <input type="number" id="nomorEpisode" name="nomorEpisode" placeholder="1" required />
        </div>

        <div class="form-group">
            <label for="fileEpisode">Upload File Episode (bisa banyak gambar):</label>
            <input type="file" id="fileEpisode" name="fileEpisode[]" accept="image/*" multiple required />
            <small>Bisa upload banyak gambar sekaligus (JPEG, PNG, dsb)</small>
        </div>

        <button type="submit" class="btn-upload">Upload Episode</button>
    </form>
</div>
</body>
</html>
