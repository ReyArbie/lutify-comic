<?php
session_start();

// Fungsi untuk ambil isi .env (sederhana)
function get_env($key, $default = null) {
    $lines = @file('.env');
    if (!$lines) return $default;
    foreach ($lines as $line) {
        $line = trim($line);
        if (!$line || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($k, $v) = explode('=', $line, 2);
            if (trim($k) == $key) {
                return trim($v);
            }
        }
    }
    return $default;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['kode_masuk'] ?? '';
    $kode_masuk = get_env('KODE_MASUK', '');

    if ($input === $kode_masuk) {
        $_SESSION['login'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Kode masuk salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - LUTIFY COMIC</title>
    <link rel="stylesheet" href="css/login.css" />
  </head>
  <body>
    <div class="login-container">
      <h1>LUTIFY COMIC</h1>
      <form class="login-form" method="POST">
        <?php if ($error): ?>
          <div style="color: red;"><?= $error ?></div>
        <?php endif; ?>
        <input type="password" name="kode_masuk" placeholder="kode masuk" required />
        <button type="submit">Login</button>
      </form>
      <footer>
        <p>© 2025 LUTIFY COMIC. All rights reserved.</p>
      </footer>
    </div>
  </body>
</html>
