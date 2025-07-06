<?php
include 'config/config.php';

// Hapus Komik
if (isset($_POST['delete_id'])) {
  $id = intval($_POST['delete_id']);

  // Hapus genre terkait jika pakai tabel terpisah (comic_genre)
  $conn->query("DELETE FROM genre WHERE id_comic=$id");

  // Hapus komik
  $conn->query("DELETE FROM comic WHERE id_comic=$id");
  echo 'deleted';
  exit;
}

// Edit Komik
if (isset($_POST['edit_id'])) {
  $id = intval($_POST['edit_id']);
  $judul = $conn->real_escape_string($_POST['edit_judul']);
  $deskripsi = $conn->real_escape_string($_POST['edit_deskripsi']);
  $conn->query("UPDATE comic SET title_comic='$judul', summary_comic='$deskripsi' WHERE id_comic=$id");
  echo 'updated';
  exit;
}
?>



<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Komik LUTIFY COMIC</title>
    <link rel="stylesheet" href="css/kelola-comic.css" />
  </head>
  <body>
    <header>
      <h1>Kelola Komik</h1>
      <p>Daftar semua komik di LUTIFY COMIC</p>
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
      <h2>Daftar Komik</h2>
      <table>
        <thead>
          <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = $conn->query("SELECT * FROM comic ORDER BY id_comic DESC");
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              ?>
              <tr data-id="<?php echo $row['id_comic']; ?>">
                <td class="comicTitle"><?php echo htmlspecialchars($row['title_comic']); ?></td>
                <td class="comicDesc"><?php echo htmlspecialchars($row['summary_comic']); ?></td>
                <td>
                  <button class="editBtn">Edit</button>
                  <button class="deleteBtn">Hapus</button>
                </td>
              </tr>
              <?php
            }
          } else {
            echo '<tr><td colspan="3">Belum ada komik.</td></tr>';
          }
          ?>
        </tbody>
      </table>

      <!-- Form Edit Komik -->
      <div id="editForm" style="display: none">
        <h3>Edit Komik</h3>
        <label for="editTitle">Judul Komik</label>
        <input type="text" id="editTitle" />
        <label for="editDescription">Deskripsi Komik</label>
        <textarea id="editDescription"></textarea>
        <button id="saveChanges">Simpan Perubahan</button>
        <button id="cancelEdit">Batal</button>
      </div>
    </div>

    <!-- Konfirmasi Hapus -->
    <div id="deleteModal" class="modal">
      <div class="modal-content">
        <p>Apakah Anda yakin ingin menghapus komik ini?</p>
        <button id="confirmDelete">Hapus</button>
        <button id="cancelDelete">Batal</button>
      </div>
    </div>

    <script>
let editId = null;
let deleteId = null;

// Edit
document.querySelectorAll('.editBtn').forEach(function(btn) {
  btn.onclick = function() {
    const row = btn.closest('tr');
    editId = row.getAttribute('data-id');
    document.getElementById('editTitle').value = row.querySelector('.comicTitle').innerText;
    document.getElementById('editDescription').value = row.querySelector('.comicDesc').innerText;
    document.getElementById('editForm').style.display = 'block';
  };
});
document.getElementById('cancelEdit').onclick = function() {
  document.getElementById('editForm').style.display = 'none';
  editId = null;
};
document.getElementById('saveChanges').onclick = function() {
  const title = document.getElementById('editTitle').value;
  const desc = document.getElementById('editDescription').value;
  if (!editId) return;
  const fd = new FormData();
  fd.append('edit_id', editId);
  fd.append('edit_judul', title);
  fd.append('edit_deskripsi', desc);
  fetch('', { method: 'POST', body: fd })
    .then(res => res.text())
    .then(res => {
      if (res.trim() == 'updated') location.reload();
      else alert('Gagal update!');
    });
};

// Delete
document.querySelectorAll('.deleteBtn').forEach(function(btn) {
  btn.onclick = function() {
    const row = btn.closest('tr');
    deleteId = row.getAttribute('data-id');
    document.getElementById('deleteModal').style.display = 'block';
  };
});
document.getElementById('cancelDelete').onclick = function() {
  document.getElementById('deleteModal').style.display = 'none';
  deleteId = null;
};
document.getElementById('confirmDelete').onclick = function() {
  if (!deleteId) return;
  const fd = new FormData();
  fd.append('delete_id', deleteId);
  fetch('', { method: 'POST', body: fd })
    .then(res => res.text())
    .then(res => {
      if (res.trim() == 'deleted') location.reload();
      else alert('Gagal hapus!');
    });
};
    </script>
  </body>
</html>
