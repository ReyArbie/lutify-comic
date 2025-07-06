<?php
include 'config/config.php';
$showModal = false;
$modalMessage = '';
$modalType = 'success';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $cover = $_FILES['cover'];

    // Daftar genre yang ada pada tabel genre
    $all_genres = ['action', 'comedy', 'romance', 'horror', 'adventure'];
    $genre_values = array_fill_keys($all_genres, 0);

    // Isi 1 jika user centang
    if (isset($_POST['genre'])) {
        foreach ($_POST['genre'] as $g) {
            if (isset($genre_values[$g])) {
                $genre_values[$g] = 1;
            }
        }
    }

    if ($judul && $deskripsi && $cover['tmp_name']) {
        // Baca file gambar dan encode ke base64
        $imageData = file_get_contents($cover['tmp_name']);
        $base64Cover = base64_encode($imageData);

        // Cek apakah judul sudah ada di tabel comic
        $cek = $conn->prepare("SELECT COUNT(*) FROM comic WHERE title_comic = ?");
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
            // 1. Insert data komik ke tabel comic
            $stmt = $conn->prepare("INSERT INTO comic (title_comic, summary_comic, cover_comic) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $judul, $deskripsi, $base64Cover);

            if ($stmt->execute()) {
                // 2. Ambil id_comic terakhir
                $last_id = $conn->insert_id;

                // 3. Insert genre ke tabel genre
                $query = "INSERT INTO genre (id_comic, action, comedy, romance, horror, adventure) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt2 = $conn->prepare($query);
                $stmt2->bind_param(
                    "iiiiii", $last_id,
                    $genre_values['action'],
                    $genre_values['comedy'],
                    $genre_values['romance'],
                    $genre_values['horror'],
                    $genre_values['adventure']
                );
                if ($stmt2->execute()) {
                    $showModal = true;
                    $modalMessage = 'Komik berhasil di-upload!';
                    $modalType = 'success';
                } else {
                    $showModal = true;
                    $modalMessage = 'Gagal simpan genre!';
                    $modalType = 'error';
                }
                $stmt2->close();
            } else {
                $showModal = true;
                $modalMessage = 'Gagal upload ke database!';
                $modalType = 'error';
            }
            $stmt->close();
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
    #genre-checkboxes-table {
      border-collapse: separate;
      border-spacing: 28px 8px;
      margin-bottom: 5px;
    }
    #genre-checkboxes-table td {
      vertical-align: middle;
      padding: 0;
    }
    #genre-checkboxes-table label {
      cursor: pointer;
      font-size: 1em;
      margin-bottom: 0;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    #genre-checkboxes-table input[type="checkbox"] {
      accent-color: #f57;
      margin-right: 7px;
      transform: scale(1.16);
    }
    @media (max-width: 700px) {
      #genre-checkboxes-table { border-spacing: 14px 8px; }
      #genre-checkboxes-table td { font-size: 0.98em; }
    }
    </style>
  </head>
  <body>
    <header>
      <h1>Upload Comic Baru</h1>
      <p>Silakan isi form di bawah ini untuk menambahkan Comic baru</p>
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

    <form action="" method="post" enctype="multipart/form-data" id="uploadForm" autocomplete="off">
      <label for="judul">Judul comic:</label><br />
      <input type="text" id="judul" name="judul" /><br /><br />

      <label>Genre comic:</label>
    <div class="genre-grid">
        <label><input type="checkbox" name="genre[]" value="action" /> Action</label>
        <!-- <label><input type="checkbox" name="genre[]" value="fantasy" /> Fantasy</label> -->
        <label><input type="checkbox" name="genre[]" value="adventure" /> Adventure</label>
        <label><input type="checkbox" name="genre[]" value="romance" /> Romance</label>

        <label><input type="checkbox" name="genre[]" value="comedy" /> Comedy</label>
        <label><input type="checkbox" name="genre[]" value="sci-fi" /> horror</label>
        <!-- <label><input type="checkbox" name="genre[]" value="drama" /> Drama</label> -->
        <!-- <label><input type="checkbox" name="genre[]" value="supernatural" /> Supernatural</label> -->

        <!-- <label><input type="checkbox" name="genre[]" value="thriller" /> Thriller</label> -->
    </div>
    <small>Pilih satu atau lebih genre yang sesuai.</small><br /><br />



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
        document.body.style.overflow = 'hidden';
      }
      // Checkbox Select All logic
      var selectAll = document.getElementById('selectAllGenre');
      if (selectAll) {
        selectAll.addEventListener('change', function() {
          var checked = this.checked;
          document.querySelectorAll('#genre-checkboxes-table input[type=checkbox][name="genre[]"]').forEach(function(box) {
            box.checked = checked;
          });
        });
      }
    });
    </script>
    <script src="js/upload.js"></script>
  </body>
</html>
