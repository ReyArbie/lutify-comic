<?php
include 'config/config.php';

// Hitung total komik dari tabel comic
$total_comic = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM comic");
if ($result && $row = $result->fetch_assoc()) {
  $total_comic = $row['total'];
}

// Hitung total chapter unik dari tabel image
$total_chapter = 0;
$result_chapter = $conn->query("SELECT COUNT(DISTINCT id_chapter) as total FROM image");
if ($result_chapter && $row_chapter = $result_chapter->fetch_assoc()) {
  $total_chapter = $row_chapter['total'];
}
?>


<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard LUTIFY COMIC</title>
    <link rel="stylesheet" href="./css/style.css" />
  </head>
  <body>
    <header>
      <h1>Dashboard Admin</h1>
      <p>Selamat datang di lutify comic</p>
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


    <div class="card-container">
      <div class="card">
        <h3>Total Comic</h3>
        <p><?php echo $total_comic; ?></p>
      </div>
      
      <div class="card">
        <h3>Total Chapter</h3>
        <p><?php echo $total_chapter; ?></p>
      </div>
     
    </div>
  </body>
</html>
