<?php 
  include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LUTIFY comic - Home</title>
  <link rel="stylesheet" href="./Lutify_comic_style.css" />
</head>
<body>
  <header>
    <div class="container nav-container">
      <h1 class="accent">LUTIFY comic</h1>
      <nav>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Anime List</a></li>
          <li><a href="#">Genres</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </nav>
      <div class="search-container">
        <input type="text" id="search-input" placeholder="Cari anime..." />
      </div>
    </div>
  </header>

  <main class="container">
    <section class="text-center">
      <h2 class="accent">Welcome to LUTIFY comic</h2>
      <p>Temukan anime favoritmu dan jelajahi dunia fantasi yang menakjubkan.</p>
    </section>

    <section class="cards" id="cards-container">
      <?php
        $sql = "SELECT * FROM anime";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
          
          }
        } else {
          echo '<p style="text-align:center; color:red;">Tidak ada anime ditemukan di database.</p>';
        }
      ?>

      <!-- Kartu Tambahan (manual, opsional) -->
      <div class="card">
        <img src="gambar/boruto two vortex.jpg" alt="Boruto Two Blue Vortex" />
        <h3 class="accent">Boruto: Two Blue Vortex</h3>
        <p>Boruto yang melarikan diri dari Konoha bersama Sasuke...</p>
      </div>

      <div class="card">
        <img src="gambar/heartpoundingGhosltyPoem.png" alt="Heart-Pounding Ghostly Poem" />
        <h3 class="accent">Heart-Pounding Ghostly Poem</h3>
        <p>Choi Doyeon, seorang streamer game, menerima game misterius dari Sakje-hyung...</p>
      </div>

      <div class="card">
        <img src="gambar/maxedstrength.jpg" alt="Maxed Strength Necromancer" />
        <h3 class="accent">Maxed Strength Necromancer</h3>
        <p>Qiao Yu mendapatkan kelas Necromancer dengan atribut aneh dan penuh tantangan...</p>
      </div>

      <div class="card">
        <img src="gambar/grandsonoftheloansharkking.jpg" alt="Genius Grandson of the Loan Shark King" />
        <h3 class="accent">Genius Grandson of the Loan Shark King</h3>
        <p>Kim Mu-hyuk berusaha diakui dalam dunia rentenir yang keras...</p>
      </div>

      <div class="card">
        <img src="gambar/blackhaze.jpg" alt="Black Haze" />
        <h3 class="accent">Black Haze</h3>
        <p>Pintu ke dunia lain terbuka, para penyihir bangkit untuk menyelamatkan dunia...</p>
      </div>

      <div class="card">
        <img src="gambar/ferstivalofwarriors.jpg" alt="Festival Of Warriors" />
        <h3 class="accent">Festival Of Warriors</h3>
        <p>Perang dan kekacauan menjadikan tinju satu-satunya jalan bertahan hidup...</p>
      </div>

      <div class="card">
        <img src="gambar/onestepdivinefirst.jpg" alt="One Step Divine First" />
        <h3 class="accent">One Step Divine First</h3>
        <p>Jang Geon mencoba mengubah nasib buruknya melalui jalan spiritual...</p>
      </div>

      <div class="card">
        <img src="gambar/fightingward.jpg" alt="Fighting Ward" />
        <h3 class="accent">Fighting Ward</h3>
        <p>Kang Sunwoo mengikuti turnamen bertarung demi uang dan kehormatan...</p>
      </div>

      <!-- Pesan jika pencarian tidak ditemukan -->
      <p id="no-result" style="display: none; text-align: center; color: #ed2828; margin-top: 20px;">
        Tidak ada hasil ditemukan.
      </p>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 LUTIFY comic. All Rights Reserved.</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>
