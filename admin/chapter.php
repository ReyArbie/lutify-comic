<?php
include 'config/config.php';

// Hapus seluruh episode (berdasarkan id_comic & id_chapter)
if (isset($_POST['delete_episode'])) {
    $id_comic = intval($_POST['delete_comic']);
    $id_chapter = intval($_POST['delete_chapter']);
    $conn->query("DELETE FROM image WHERE id_comic = $id_comic AND id_chapter = $id_chapter");
    echo 'deleted';
    exit;
}

// Hapus satu halaman pada episode
if (isset($_POST['delete_page'])) {
    $id_comic = intval($_POST['comic']);
    $id_chapter = intval($_POST['chapter']);
    $id_page = intval($_POST['page']);
    $conn->query("DELETE FROM image WHERE id_comic = $id_comic AND id_chapter = $id_chapter AND id_page = $id_page");
    // Setelah hapus, urutkan kembali id_page agar urut
    $res = $conn->query("SELECT id_page FROM image WHERE id_comic=$id_comic AND id_chapter=$id_chapter ORDER BY id_page ASC");
    $newPage = 1;
    while ($row = $res->fetch_assoc()) {
        $conn->query("UPDATE image SET id_page=$newPage WHERE id_comic=$id_comic AND id_chapter=$id_chapter AND id_page={$row['id_page']}");
        $newPage++;
    }
    echo 'deleted';
    exit;
}

// Tambah halaman ke episode (edit: tambah gambar)
if (isset($_POST['edit_add_page'])) {
    $id_comic = intval($_POST['edit_comic']);
    $id_chapter = intval($_POST['edit_chapter']);
    if (isset($_FILES['edit_file']) && $_FILES['edit_file']['tmp_name']) {
        $files = $_FILES['edit_file'];
        $count = count($files['name']);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        // Cari page terakhir
        $r = $conn->query("SELECT MAX(id_page) as maxpage FROM image WHERE id_comic=$id_comic AND id_chapter=$id_chapter");
        $page_number = 1;
        if ($rr = $r->fetch_assoc()) {
            $page_number = intval($rr['maxpage']) + 1;
        }
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === 0) {
                $tmpName = $files['tmp_name'][$i];
                $fileType = mime_content_type($tmpName);
                if (!in_array($fileType, $allowed_types)) continue;
                $imgData = file_get_contents($tmpName);
                $b64 = base64_encode($imgData);
                $stmt = $conn->prepare("INSERT INTO image (id_comic, id_chapter, id_page, content) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiis", $id_comic, $id_chapter, $page_number, $b64);
                $stmt->execute();
                $stmt->close();
                $page_number++;
            }
        }
    }
    echo 'updated';
    exit;
}

// Ambil daftar komik
$comics = [];
$sql_comics = "SELECT id_comic, title_comic FROM comic";
$result_comics = $conn->query($sql_comics);
if ($result_comics) {
    while ($row = $result_comics->fetch_assoc()) {
        $comics[] = $row;
    }
}

// Tambah episode baru (multi-image per page)
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['edit_add_page'])) {
    $comic_id = intval($_POST['komik']);
    $chapter_id = intval($_POST['nomorEpisode']);
    if (isset($_FILES['fileEpisode'])) {
        $files = $_FILES['fileEpisode'];
        $count = count($files['name']);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === 0) {
                $tmpName = $files['tmp_name'][$i];
                $fileType = mime_content_type($tmpName);
                if (!in_array($fileType, $allowed_types)) continue;
                $imgData = file_get_contents($tmpName);
                $b64 = base64_encode($imgData);
                $page_number = $i + 1;
                $stmt = $conn->prepare("INSERT INTO image (id_comic, id_chapter, id_page, content) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiis", $comic_id, $chapter_id, $page_number, $b64);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    header("Location: chapter.php");
    exit();
}

// Ambil daftar episode/chapter (distinct by id_comic & id_chapter)
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
        .form-group { margin-bottom: 14px; }
        .edit-episode-ctrl { margin-top: 8px; }
        .edit-page-btn { color: #c22; font-size: 0.98em; border: none; background: none; cursor: pointer; }
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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($episodes as $episode): ?>
            <tr>
                <td><?= htmlspecialchars($episode['title_comic']) ?></td>
                <td><?= htmlspecialchars($episode['id_chapter']) ?></td>
                <td>
                    <div class="episode-images">
                    <?php
                    $stmtImg = $conn->prepare("SELECT id_page, content FROM image WHERE id_comic = ? AND id_chapter = ? ORDER BY id_page ASC");
                    $stmtImg->bind_param("ii", $episode['id_comic'], $episode['id_chapter']);
                    $stmtImg->execute();
                    $resultImg = $stmtImg->get_result();
                    while ($imgRow = $resultImg->fetch_assoc()) {
                        ?>
                        <div style="position:relative;display:inline-block;">
                          <img src="data:image/jpeg;base64,<?= $imgRow['content'] ?>" alt="Page <?= $imgRow['id_page'] ?>" title="Halaman <?= $imgRow['id_page'] ?>">
                          <form method="post" class="form-delete-page" style="position:absolute;top:2px;right:2px;">
                              <input type="hidden" name="comic" value="<?= $episode['id_comic'] ?>">
                              <input type="hidden" name="chapter" value="<?= $episode['id_chapter'] ?>">
                              <input type="hidden" name="page" value="<?= $imgRow['id_page'] ?>">
                              <button type="submit" class="edit-page-btn" title="Hapus halaman" onclick="return confirm('Hapus halaman ini?')">&times;</button>
                          </form>
                        </div>
                        <?php
                    }
                    $stmtImg->close();
                    ?>
                    </div>
                    <!-- Form tambah halaman pada episode -->
                    <form class="form-add-page" method="post" enctype="multipart/form-data" style="margin-top:8px;">
                        <input type="hidden" name="edit_comic" value="<?= $episode['id_comic'] ?>">
                        <input type="hidden" name="edit_chapter" value="<?= $episode['id_chapter'] ?>">
                        <input type="file" name="edit_file[]" accept="image/*" multiple required>
                        <button type="submit" name="edit_add_page" value="1" class="edit-episode-ctrl">+ Tambah Halaman</button>
                    </form>
                </td>
                <td>
                    <form class="form-delete-episode" method="post" style="display:inline;">
                        <input type="hidden" name="delete_comic" value="<?= $episode['id_comic'] ?>">
                        <input type="hidden" name="delete_chapter" value="<?= $episode['id_chapter'] ?>">
                        <button type="submit" name="delete_episode" value="1" onclick="return confirm('Hapus seluruh episode ini?')">Hapus Episode</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <hr />
    <h2>Tambah Chapter Baru</h2>
    <form class="form-episode" action="chapter.php" method="post" enctype="multipart/form-data">
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
            <input type="number" id="nomorEpisode" name="nomorEpisode" min="1" placeholder="1" required />
        </div>

        <div class="form-group">
            <label for="fileEpisode">Upload File Episode (bisa banyak gambar):</label>
            <input type="file" id="fileEpisode" name="fileEpisode[]" accept="image/*" multiple required />
            <small>Bisa upload banyak gambar sekaligus (JPEG, PNG, WebP, GIF)</small>
        </div>

        <button type="submit" class="btn-upload">Upload Episode</button>
    </form>
</div>
<script>
// AJAX hapus halaman (per image)
document.querySelectorAll('.form-delete-page').forEach(function(form){
  form.onsubmit = function(e){
    e.preventDefault();
    if(!confirm('Hapus halaman ini?')) return;
    const fd = new FormData(form);
    fd.append('delete_page', '1');
    fetch('', {method:'POST', body:fd})
      .then(res=>res.text())
      .then(res=>{
        if(res.trim()=='deleted') location.reload();
        else alert('Gagal hapus halaman!');
      });
  }
});
// AJAX tambah halaman
document.querySelectorAll('.form-add-page').forEach(function(form){
  form.onsubmit = function(e){
    e.preventDefault();
    const fd = new FormData(form);
    fd.append('edit_add_page','1');
    fetch('', {method:'POST', body:fd})
      .then(res=>res.text())
      .then(res=>{
        if(res.trim()=='updated') location.reload();
        else alert('Gagal tambah halaman!');
      });
  }
});
// AJAX hapus episode
document.querySelectorAll('.form-delete-episode').forEach(function(form){
  form.onsubmit = function(e){
    e.preventDefault();
    if(!confirm('Hapus seluruh episode ini?')) return;
    const fd = new FormData(form);
    fd.append('delete_episode','1');
    fetch('', {method:'POST', body:fd})
      .then(res=>res.text())
      .then(res=>{
        if(res.trim()=='deleted') location.reload();
        else alert('Gagal hapus episode!');
      });
  }
});
</script>
</body>
</html>
