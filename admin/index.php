<?php
include 'config/config.php';
session_start();
if (!isset($_SESSION['login'])) {
    // Kalau belum login, kembalikan ke login.php
    header('Location: login.php');
    exit;
}

// Hitung total komik dari tabel comics
$total_komik = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM comics");
if ($result && $row = $result->fetch_assoc()) {
  $total_komik = $row['total'];
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
      <a href="index.php">Dashboard</a>
      <a href="kelola-comic.php">Kelola Comic</a>
      <a href="episode.php">kelola episode</a>
      <a href="upload.php">upload comic </a>
    </nav>
    <div class="card-container">
      <div class="card">
        <h3>Total Komik</h3>
        <p><?php echo $total_komik; ?></p>
      </div>
      
      <div class="card">
        <h3>Total Episode</h3>
        <p>0</p>
      </div>
     
    </div>
  </body>
</html>
