<?php
include 'config/config.php';
$showModal = false;
$modalMessage = '';
$modalType = 'success';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $cover = $_FILES['cover'];

    if ($judul && $genre && $deskripsi && $cover['name']) {
        $cek = $conn->prepare("SELECT COUNT(*) FROM comics WHERE judul = ?");
        $cek->bind_param("s", $judul);
        $cek->execute();
        $cek->bind_result($ada);
        $cek->fetch();
        $cek->close();

        if ($ada > 0) {
            $showModal = true;
            $modalMessage = 'Judul komik sudah ada, silakan pakai judul lain!';
            $modalType = 'error';
        } else {
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $coverName = time() . '_' . basename($cover['name']);
            $uploadFile = $uploadDir . $coverName;

            if (move_uploaded_file($cover['tmp_name'], $uploadFile)) {
                $stmt = $conn->prepare("INSERT INTO comics (judul, genre, deskripsi, cover) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $judul, $genre, $deskripsi, $coverName);

                if ($stmt->execute()) {
                    $showModal = true;
                    $modalMessage = 'Komik berhasil di-upload!';
                    $modalType = 'success';
                } else {
                    $showModal = true;
                    $modalMessage = 'Gagal upload ke database!';
                    $modalType = 'error';
                }
            } else {
                $showModal = true;
                $modalMessage = 'Upload cover gagal!';
                $modalType = 'error';
            }
        }
    } else {
        $showModal = true;
        $modalMessage = 'Harap isi semua field dan upload cover!';
        $modalType = 'error';
    }
}

?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Upload Komik - LUTIFY COMIC</title>
    <link rel="stylesheet" href="css/upload.css" />
    <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100vw;
      height: 100vh;
      overflow: auto;
      background: rgba(51, 51, 51, 0.85);
      justify-content: center;
      align-items: center;
    }
    .modal.active {
      display: flex;
    }
    .modal-content {
      background: #202938;
      color: #f57;
      margin: auto;
      padding: 32px 26px 22px 26px;
      border-radius: 10px;
      max-width: 350px;
      width: 90%;
      box-shadow: 0 8px 40px rgba(0,0,0,0.2);
      text-align: center;
      position: relative;
      animation: pop-in 0.3s;
    }
    @keyframes pop-in {
      0% { transform: scale(0.8); opacity: 0;}
      100% { transform: scale(1); opacity: 1;}
    }
    .modal-content h2 {margin-top: 0;}
    .modal-content button {
      background: #f57;
      color: #fff;
      padding: 10px 30px;
      border: none;
      border-radius: 6px;
      margin-top: 18px;
      font-size: 1em;
      cursor: pointer;
      font-weight: bold;
    }
    </style>
  </head>
  <body>
    <header>
      <h1>Upload Komik Baru</h1>
      <p>Silakan isi formulir di bawah ini untuk menambahkan komik baru</p>
    </header>

    <nav>
      <a href="index.php">Dashboard</a>
      <a href="kelola-comic.php">Kelola Comic</a>
      <a href="episode.php">Kelola Episode</a>
      <a href="upload.php">Upload Comic</a>
    </nav>

    <form action="" method="post" enctype="multipart/form-data" id="uploadForm" autocomplete="off">
      <label for="judul">Judul Komik:</label><br />
      <input type="text" id="judul" name="judul" /><br /><br />

      <label for="genre">Genre Komik:</label><br />
      <input type="text" id="genre" name="genre" /><br /><br />

      <label for="deskripsi">Deskripsi:</label><br />
      <textarea id="deskripsi" name="deskripsi" rows="4"></textarea><br /><br />

      <label for="cover">Upload Cover:</label><br />
      <input type="file" id="cover" name="cover" accept="image/*" /><br /><br />

      <button type="submit">Upload Komik</button>
    </form>

    <!-- Modal -->
    <div id="successModal" class="modal<?php if ($showModal) echo ' active'; ?>">
      <div class="modal-content">
        <h2><?php echo $modalType === 'success' ? 'Sukses!' : 'Error!'; ?></h2>
        <p><?php echo htmlspecialchars($modalMessage); ?></p>
        <button id="closeModalBtn"><?php echo $modalType === 'success' ? 'Ke Daftar Komik' : 'Tutup'; ?></button>
      </div>
    </div>

    <script src="js/upload.js"></script>
    <script>
    // Modal handler (untuk PHP)
    document.addEventListener('DOMContentLoaded', function() {
      var modal = document.getElementById('successModal');
      var btn = document.getElementById('closeModalBtn');
      if (modal && btn && modal.classList.contains('active')) {
        btn.focus();
        btn.onclick = function() {
          modal.classList.remove('active');
          <?php if ($modalType === 'success' && $showModal) { ?>
            window.location = "kelola-comic.php";
          <?php } ?>
        };
        // Blok scroll di belakang modal
        document.body.style.overflow = 'hidden';
      }
    });
    </script>
  </body>
</html>
