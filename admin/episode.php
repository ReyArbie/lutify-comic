<?php
include 'config/config.php';

// Ambil daftar komik dari tabel comics
$comics = [];
$sql_comics = "SELECT id, judul FROM comics";
$result_comics = $conn->query($sql_comics);
if ($result_comics) {
    while ($row = $result_comics->fetch_assoc()) {
        $comics[] = $row;
    }
}

// Proses tambah episode baru dengan multi-image
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comic_id = $_POST['komik'];
    $judul_episode = $_POST['judulEpisode'];
    $nomor_episode = $_POST['nomorEpisode'];

    // 1. Insert ke episodes
    $stmt = $conn->prepare("INSERT INTO episodes (comic_id, judul_episode, nomor_episode) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $comic_id, $judul_episode, $nomor_episode);
    $stmt->execute();
    $episode_id = $conn->insert_id;
    $stmt->close();

    // 2. Proses semua gambar
    if (isset($_FILES['fileEpisode'])) {
        $files = $_FILES['fileEpisode'];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === 0) {
                $tmpName = $files['tmp_name'][$i];
                $imgData = file_get_contents($tmpName);
                $b64 = base64_encode($imgData);

                // Insert ke episode_images
                $stmt = $conn->prepare("INSERT INTO episode_images (episode_id, image_b64) VALUES (?, ?)");
                $stmt->bind_param("is", $episode_id, $b64);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Redirect agar tidak dobel submit
    header("Location: episode.php");
    exit();
}

// Ambil daftar episode
$episodes = [];
$sql = "SELECT e.*, c.judul FROM episodes e JOIN comics c ON e.comic_id = c.id ORDER BY e.id DESC";
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
    <h1>Kelola Episode</h1>
    <p>Daftar episode untuk setiap komik dan tambah episode baru</p>
</header>

<nav>
  <div class="nav-center">
    <a href="index.php">Dashboard</a>
    <a href="kelola-comic.php">Kelola Comic</a>
    <a href="episode.php">Kelola Episode</a>
    <a href="upload.php">Upload Comic</a>
  </div>
  <a href="logout.php" class="logout-btn">Logout</a>
</nav>


<div class="container">
    <h2>Daftar Episode</h2>
    <table>
        <thead>
            <tr>
                <th>Judul Komik</th>
                <th>Judul Episode</th>
                <th>Nomor</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($episodes as $episode): ?>
            <tr>
                <td><?= htmlspecialchars($episode['judul']) ?></td>
                <td><?= htmlspecialchars($episode['judul_episode']) ?></td>
                <td><?= htmlspecialchars($episode['nomor_episode']) ?></td>
                <td>
                    <?php
                    // Tampilkan semua gambar episode (B64)
                    $stmtImg = $conn->prepare("SELECT image_b64 FROM episode_images WHERE episode_id = ?");
                    $stmtImg->bind_param("i", $episode['id']);
                    $stmtImg->execute();
                    $resultImg = $stmtImg->get_result();
                    echo '<div class="episode-images">';
                    while ($imgRow = $resultImg->fetch_assoc()) {
                        // Ganti image/jpeg ke tipe gambar lain jika ingin support PNG/WebP, dst
                        echo '<img src="data:image/jpeg;base64,'.$imgRow['image_b64'].'" alt="Episode Image">';
                    }
                    echo '</div>';
                    $stmtImg->close();
                    ?>
                </td>
                <td>
                    <button>Edit</button>
                    <button>Hapus</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <hr />
    <h2>Tambah Episode Baru</h2>
    <form class="form-episode" action="episode.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="komik">Pilih Komik:</label>
            <select id="komik" name="komik" required>
                <option value="">-- Pilih Komik --</option>
                <?php foreach ($comics as $komik): ?>
                    <option value="<?= $komik['id'] ?>"><?= htmlspecialchars($komik['judul']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="judulEpisode">Judul Episode:</label>
            <input type="text" id="judulEpisode" name="judulEpisode" placeholder="Petualangan Baru" required />
        </div>

        <div class="form-group">
            <label for="nomorEpisode">Nomor Episode:</label>
            <input type="number" id="nomorEpisode" name="nomorEpisode" placeholder="5" required />
        </div>

        <div class="form-group">
            <label for="fileEpisode">Upload File Episode (bisa banyak gambar):</label>
            <input type="file" id="fileEpisode" name="fileEpisode[]" accept="image/*" multiple required />
            <small>Bisa upload banyak gambar sekaligus (JPEG, PNG, dsb)</small>
        </div>

        <button type="submit" class="btn-upload">Upload Episode</button>
    </form>
</div>
<script src="js/episode.js"></script>
</body>
</html>
