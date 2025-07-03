<!DOCTYPE html>
<html lang="id">
  <head>
    <?php
      include "koneksi.php";

      $sql = "SELECT * FROM comics where id='1'";
      $result = $conn->query($sql);

      $row = $result->fetch_assoc();

      $cover_image = $row['cover_image'];
      $title = $row['title'];
      $age_rating = $row['age_rating'];
      $release_year = $row['release_year'];
      $total_episodes = $row['total_episodes'];
      $synopsis = $row['synopsis'];

    ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Boruto: Two Blue Vortex</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap"
      rel="stylesheet"
    />
    <style>
      :root {
        --primary: #e50914;
        --background: #121212;
        --card: #222;
        --accent: #f87171;
        --text: #fff;
        --subtext: #bbb;
        --gray: #2d2d2d;
        --yellow: #facc15;
      }
      html,
      body {
        margin: 0;
        padding: 0;
        background: var(--background);
        color: var(--text);
        font-family: "Roboto", Arial, sans-serif;
        min-height: 100%;
      }
      /* Header */
      header {
        background: var(--gray);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.22);
        padding: 1rem 0;
      }
      .nav-wrap {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
      }
      .site-title {
        color: var(--accent);
        font-size: 2.1rem;
        font-weight: 700;
        letter-spacing: 2px;
      }
      nav ul {
        list-style: none;
        display: flex;
        gap: 1.2rem;
        padding: 0;
        margin: 0;
      }
      nav ul li a {
        color: var(--accent);
        font-size: 1rem;
        font-weight: 400;
        transition: color 0.2s;
        padding: 6px 8px;
        border-radius: 4px;
        text-decoration: none;
      }
      nav ul li a:hover {
        color: var(--yellow);
        background: rgba(255, 255, 255, 0.06);
      }
      /* Welcome */
      .welcome-section {
        text-align: center;
        padding: 2.5rem 1.3rem 1.5rem;
      }
      .welcome-section h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
      }
      .welcome-section p {
        color: #cccccc;
        font-size: 1.1rem;
      }
      /* Main Container */
      .container-content {
        max-width: 920px;
        margin: 0 auto;
        padding: 0 24px 32px;
      }
      /* Banner */
      .banner {
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.26);
        background: var(--card);
        padding: 20px 0;
      }
      .banner img {
        display: block;
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 0 auto;
        box-shadow: 0 4px 24px #0008;
      }
      /* Info Box */
      .movie-info {
        margin-top: 0.5rem;
        text-align: center;
        background: var(--card);
        border-radius: 12px;
        box-shadow: 0 2px 8px #0002;
        padding: 26px 16px 16px;
      }
      .movie-title {
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: 7px;
        letter-spacing: 0.5px;
      }
      .details {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 14px;
        color: var(--subtext);
        font-size: 1.01rem;
        margin-bottom: 17px;
      }
      .play-button {
        background: var(--primary);
        color: #fff;
        padding: 13px 26px;
        border: none;
        border-radius: 6px;
        font-size: 1.07rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 7px;
        box-shadow: 0 4px 20px #e509141b;
        transition: background 0.24s;
        letter-spacing: 0.7px;
      }
      .play-button:hover,
      .play-button:focus {
        background: #c40812;
      }
      .description,
      .info-text {
        font-size: 1rem;
        color: #ccc;
        text-align: justify;
        margin: 13px 0 0 0;
      }
      .info-text strong {
        color: var(--primary);
        font-weight: 600;
      }
      .episodes {
        margin-top: 36px;
        background: var(--card);
        border-radius: 12px;
        padding: 24px 18px 18px 18px;
        box-shadow: 0 2px 8px #0002;
      }
      .episodes h3 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 14px;
        text-align: center;
        color: var(--primary);
      }
      .episode-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
      }
      .episode-link {
        display: flex;
        align-items: center;
        background: #191919;
        border-radius: 8px;
        gap: 13px;
        padding: 11px 12px;
        color: #fff;
        border: 1px solid transparent;
        transition: background 0.18s, border 0.18s;
        box-shadow: 0 2px 8px #0002;
        cursor: pointer;
      }
      .episode-link:hover,
      .episode-link:focus {
        background: #222;
        border: 1px solid var(--primary);
      }
      .episode-thumbnail {
        width: 70px;
        height: 44px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
        box-shadow: 0 2px 6px #0004;
      }
      .episode-label {
        background: var(--primary);
        color: #fff;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.82rem;
        font-weight: bold;
        text-transform: uppercase;
        margin-left: 2px;
        flex-shrink: 0;
        letter-spacing: 1px;
      }
      .episode-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
      }
      .episode-number {
        font-weight: 600;
        font-size: 1rem;
      }
      footer {
        margin-top: 50px;
        text-align: center;
        font-size: 1rem;
        color: #777;
        padding: 32px 0 16px;
        background: none;
      }
      @media (max-width: 900px) {
        .container-content {
          padding: 0 10px 28px;
        }
        .nav-wrap {
          padding: 0 10px;
        }
        .episode-list {
          grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        }
      }
      @media (max-width: 700px) {
        .movie-title {
          font-size: 1.22rem;
        }
        .episode-list {
          grid-template-columns: 1fr;
        }
      }
      @media (max-width: 500px) {
        .welcome-section h2 {
          font-size: 1.1rem;
        }
        .container-content {
          padding: 0 2px 18px;
        }
        .episodes {
          padding: 16px 4px 12px 4px;
        }
        .episode-link {
          padding: 8px 6px;
        }
        nav ul {
          gap: 0.4rem;
        }
        .site-title {
          font-size: 1.15rem;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <div class="nav-wrap">
        <div class="site-title">LUTIFY comic</div>
        <nav>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Anime List</a></li>
            <li><a href="#">Genres</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <section class="welcome-section">
      <h2>Welcome to LUTIFY comic</h2>
      <p>
        Temukan komik favoritmu dan jelajahi dunia fantasi yang menakjubkan.
      </p>
    </section>

    <main class="container-content">
      <!-- BANNER GAMBAR BIASA -->
      <section class="banner" aria-label="Poster Boruto: Two Blue Vortex">
        <img src="<?php echo $cover_image;?>" alt="Boruto: Two Blue Vortex" />
      </section>

      <!-- Info -->
      <section
        class="movie-info"
        aria-label="Informasi serial Boruto: Two Blue Vortex"
      >
        <h2 class="movie-title"><?php echo $title;?></h2>
        <div class="details">
          <span><?php echo $age_rating;?></span>
          <span><?php echo $release_year;?></span>
          <span><?php echo $total_episodes;?></span>
          <span>• Action | Drama</span>
        </div>
        <button class="play-button" aria-label="Baca Boruto: Two Blue Vortex">
          Baca
        </button>
        <p class="description">
          <?php echo $synopsis;?>
        </p>
        <p class="info-text">
          <strong>Author:</strong> Masashi Kishimoto, Mikio Ikemoto
        </p>
        <p class="info-text">
          <strong>Karakter:</strong> Boruto Uzumaki, Uchiha Sarada, Kawaki,
          Mitsuki, Uzumaki Himawari, Eida, Code, Nara Shikamaru.
        </p>
      </section>

      <!-- Episode List -->
      <section class="episodes" aria-label="Daftar Episode">
        <h3>Daftar Episode</h3>
        <ul class="episode-list">
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 01"
              ><img
                src="https://via.placeholder.com/80x50?text=E01"
                alt="Thumbnail Episode 01"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 01</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 02"
              ><img
                src="https://via.placeholder.com/80x50?text=E02"
                alt="Thumbnail Episode 02"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 02</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 03"
              ><img
                src="https://via.placeholder.com/80x50?text=E03"
                alt="Thumbnail Episode 03"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 03</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 04"
              ><img
                src="https://via.placeholder.com/80x50?text=E04"
                alt="Thumbnail Episode 04"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 04</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 05"
              ><img
                src="https://via.placeholder.com/80x50?text=E05"
                alt="Thumbnail Episode 05"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 05</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 06"
              ><img
                src="https://via.placeholder.com/80x50?text=E06"
                alt="Thumbnail Episode 06"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 06</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 07"
              ><img
                src="https://via.placeholder.com/80x50?text=E07"
                alt="Thumbnail Episode 07"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 07</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 08"
              ><img
                src="https://via.placeholder.com/80x50?text=E08"
                alt="Thumbnail Episode 08"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 08</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 09"
              ><img
                src="https://via.placeholder.com/80x50?text=E09"
                alt="Thumbnail Episode 09"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 09</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 10"
              ><img
                src="https://via.placeholder.com/80x50?text=E10"
                alt="Thumbnail Episode 10"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 10</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 11"
              ><img
                src="https://via.placeholder.com/80x50?text=E11"
                alt="Thumbnail Episode 11"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 11</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 12"
              ><img
                src="https://via.placeholder.com/80x50?text=E12"
                alt="Thumbnail Episode 12"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 12</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 13"
              ><img
                src="https://via.placeholder.com/80x50?text=E13"
                alt="Thumbnail Episode 13"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 13</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 14"
              ><img
                src="https://via.placeholder.com/80x50?text=E14"
                alt="Thumbnail Episode 14"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 14</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 15"
              ><img
                src="https://via.placeholder.com/80x50?text=E15"
                alt="Thumbnail Episode 15"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 15</div>
              </div></a
            >
          </li>
          <li>
            <a href="#" class="episode-link" aria-label="Buka Episode 16"
              ><img
                src="https://via.placeholder.com/80x50?text=E16"
                alt="Thumbnail Episode 16"
                class="episode-thumbnail"
              /><span class="episode-label">GRATIS</span>
              <div class="episode-info">
                <div class="episode-number">Episode 16</div>
              </div></a
            >
          </li>
        </ul>
      </section>
    </main>

    <footer>
      <p>© 2025 Website Comic/Manga. Dibuat oleh Rakha Adhitya.</p>
    </footer>

    <script>
      document
        .querySelector(".play-button")
        .addEventListener("click", function (e) {
          e.preventDefault();
          alert("Memutar Boruto: Two Blue Vortex...");
        });
      document.querySelectorAll(".episode-link").forEach(function (link, i) {
        link.addEventListener("click", function (e) {
          e.preventDefault();
          alert("Membuka Episode " + String(i + 1).padStart(2, "0") + "...");
        });
      });
    </script>
  </body>
</html>
