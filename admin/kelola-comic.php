<?php
include 'config/config.php';

// Hapus Komik
if (isset($_POST['delete_id'])) {
  $id = intval($_POST['delete_id']);
  $conn->query("DELETE FROM genre WHERE id_comic=$id");
  $conn->query("DELETE FROM comic WHERE id_comic=$id");
  echo 'deleted';
  exit;
}

// Edit Komik (AJAX POST)
if (isset($_POST['edit_id'])) {
  $id = intval($_POST['edit_id']);
  $judul = $conn->real_escape_string($_POST['edit_judul']);
  $deskripsi = $conn->real_escape_string($_POST['edit_deskripsi']);

  // Ambil genre dari form (array)
  $all_genres = ['action', 'comedy', 'romance', 'horror', 'adventure'];
  $genre_values = array_fill_keys($all_genres, 0);
  if (isset($_POST['edit_genre'])) {
    foreach ($_POST['edit_genre'] as $g) {
      if (isset($genre_values[$g])) $genre_values[$g] = 1;
    }
  }

  // Cek jika ada file cover baru
  $cover_updated = false;
  if (isset($_FILES['edit_cover']) && $_FILES['edit_cover']['tmp_name']) {
    $imageData = file_get_contents($_FILES['edit_cover']['tmp_name']);
    $base64Cover = base64_encode($imageData);
    $cover_updated = true;
  }

  // Update komik
  if ($cover_updated) {
    $conn->query("UPDATE comic SET title_comic='$judul', summary_comic='$deskripsi', cover_comic='$base64Cover' WHERE id_comic=$id");
  } else {
    $conn->query("UPDATE comic SET title_comic='$judul', summary_comic='$deskripsi' WHERE id_comic=$id");
  }

  // Update genre (selalu update semua field)
  $conn->query("UPDATE genre SET action={$genre_values['action']}, comedy={$genre_values['comedy']}, romance={$genre_values['romance']}, horror={$genre_values['horror']}, adventure={$genre_values['adventure']} WHERE id_comic=$id");

  echo 'updated';
  exit;
}

// Ambil data komik untuk form edit (AJAX GET)
if (isset($_GET['get_id'])) {
  $id = intval($_GET['get_id']);
  $res1 = $conn->query("SELECT * FROM comic WHERE id_comic=$id");
  $comic = $res1 ? $res1->fetch_assoc() : [];
  $res2 = $conn->query("SELECT * FROM genre WHERE id_comic=$id");
  $genre = $res2 ? $res2->fetch_assoc() : [];
  echo json_encode(['comic' => $comic, 'genre' => $genre]);
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
  <style>
    #editFormRow td { background: #fafaff; border-bottom: 1px solid #eee; }
    #editForm { padding: 10px 5px; }
    #editForm label { margin-top: 5px; }
    .genre-grid { display: flex; flex-wrap: wrap; gap: 12px 22px; margin: 8px 0 15px 0; }
    .genre-grid label { font-size: 1em; }
    .current-cover { max-width: 80px; max-height: 90px; margin-bottom:6px; }
  </style>
</head>
<body>
<header>
  <h1>Kelola Comic</h1>
  <p>Daftar semua komik di LUTIFY COMIC</p>
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
  <h2>Daftar comic</h2>
  <table>
    <thead>
      <tr>
        <th>Judul</th>
        <th>Deskripsi</th>
        <th>Genre</th>
        <th>Cover</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT comic.*, genre.action, genre.comedy, genre.romance, genre.horror, genre.adventure FROM comic 
        LEFT JOIN genre ON comic.id_comic = genre.id_comic ORDER BY comic.id_comic DESC");
      $all_genres = ['action','comedy','romance','horror','adventure'];
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          ?>
          <tr data-id="<?php echo $row['id_comic']; ?>">
            <td class="comicTitle"><?php echo htmlspecialchars($row['title_comic']); ?></td>
            <td class="comicDesc"><?php echo htmlspecialchars($row['summary_comic']); ?></td>
            <td class="comicGenre">
              <?php
                $active = [];
                foreach($all_genres as $g) if($row[$g]) $active[] = ucfirst($g);
                echo $active ? implode(', ', $active) : '<span style="color:#aaa">-</span>';
              ?>
            </td>
            <td>
              <?php if ($row['cover_comic']) { ?>
                <img src="data:image/jpeg;base64,<?php echo $row['cover_comic'];?>" alt="Cover" style="max-width:60px;max-height:80px;">
              <?php } ?>
            </td>
            <td>
              <button class="editBtn">Edit</button>
              <button class="deleteBtn">Hapus</button>
            </td>
          </tr>
          <?php
        }
      } else {
        echo '<tr><td colspan="5">Belum ada komik.</td></tr>';
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Inline Form Edit Komik -->
<div id="editForm" style="display:none;">
  <h3>Edit Komik</h3>
  <form id="realEditForm" enctype="multipart/form-data" autocomplete="off">
    <label for="editTitle">Judul Komik</label>
    <input type="text" id="editTitle" name="edit_judul" required />

    <label for="editDescription">Deskripsi Komik</label>
    <textarea id="editDescription" name="edit_deskripsi" required></textarea>

    <label>Genre Komik:</label>
    <div class="genre-grid" id="editGenreGrid">
      <label><input type="checkbox" name="edit_genre[]" value="action"> Action</label>
      <label><input type="checkbox" name="edit_genre[]" value="comedy"> Comedy</label>
      <label><input type="checkbox" name="edit_genre[]" value="romance"> Romance</label>
      <label><input type="checkbox" name="edit_genre[]" value="horror"> Horror</label>
      <label><input type="checkbox" name="edit_genre[]" value="adventure"> Adventure</label>
    </div>

    <label>Cover Komik Saat Ini:</label><br>
    <img id="currentCover" class="current-cover" src="" alt="Current Cover"><br>
    <label for="editCover">Ubah Cover (opsional):</label>
    <input type="file" id="editCover" name="edit_cover" accept="image/*"><br><br>

    <button type="submit" id="saveChanges">Simpan Perubahan</button>
    <button type="button" id="cancelEdit">Batal</button>
  </form>
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
let editRow = null;
let lastFormRow = null;

// Inline Edit
document.querySelectorAll('.editBtn').forEach(function(btn) {
  btn.onclick = async function() {
    // Tutup form edit lain jika terbuka
    if (editRow && lastFormRow && lastFormRow.parentNode) {
      document.body.appendChild(document.getElementById('editForm'));
      document.getElementById('editForm').style.display = 'none';
      lastFormRow.remove();
    }

    const row = btn.closest('tr');
    editId = row.getAttribute('data-id');
    editRow = row;

    // Ambil data komik (GET AJAX)
    fetch('?get_id='+editId)
      .then(r => r.json())
      .then(data => {
        document.getElementById('editTitle').value = data.comic.title_comic || '';
        document.getElementById('editDescription').value = data.comic.summary_comic || '';
        // Cover
        if (data.comic.cover_comic) {
          document.getElementById('currentCover').src = "data:image/jpeg;base64," + data.comic.cover_comic;
          document.getElementById('currentCover').style.display = 'inline';
        } else {
          document.getElementById('currentCover').src = '';
          document.getElementById('currentCover').style.display = 'none';
        }
        // Genre
        let allGenres = ['action','comedy','romance','horror','adventure'];
        allGenres.forEach(g => {
          document.querySelector('#editGenreGrid input[value="'+g+'"]').checked = data.genre && data.genre[g]==1;
        });
      });

    document.getElementById('editForm').style.display = 'block';

    // Pindah form edit ke bawah baris aktif
    if (row.nextSibling && row.nextSibling.id === 'editFormRow') row.nextSibling.remove();
    const editFormRow = document.createElement('tr');
    editFormRow.id = 'editFormRow';
    const td = document.createElement('td');
    td.colSpan = 5;
    td.appendChild(document.getElementById('editForm'));
    editFormRow.appendChild(td);
    row.parentNode.insertBefore(editFormRow, row.nextSibling);
    lastFormRow = editFormRow;
  };
});

document.getElementById('cancelEdit').onclick = function() {
  document.getElementById('editForm').style.display = 'none';
  if (editRow && lastFormRow && lastFormRow.parentNode) {
    lastFormRow.remove();
  }
  editId = null;
  editRow = null;
  lastFormRow = null;
};

document.getElementById('realEditForm').onsubmit = function(e) {
  e.preventDefault();
  if (!editId) return;
  const fd = new FormData(document.getElementById('realEditForm'));
  fd.append('edit_id', editId);
  fetch('', { method: 'POST', body: fd })
    .then(res => res.text())
    .then(res => {
      if (res.trim() == 'updated') location.reload();
      else alert('Gagal update!');
    });
};

// Delete
let deleteId = null;
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
