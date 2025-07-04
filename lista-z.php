<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lutify Comic</title>
    <link rel="stylesheet" href="lista-z.css">
</head>
<body>
    <header>
        <div class="header">
            <h1>LUTIFY COMIC</h1>
            <form onsubmit="event.preventDefault();">
                <input placeholder="Cari Komik..." type="search" />
                <button type="submit">CARI</button>
            </form>
        </div>
        <nav>
            <a href="#">HOME</a>
            <a href="#">List A-Z</a>
            <a href="#">Genre</a>    
        </nav>
    </header>

    <div class="table">
        <h3>List A-Z</h3>  
        <div id="filter-buttons"> 
            <button>A</button><button>B</button><button>C</button><button>D</button><button>E</button>
            <button>F</button><button>G</button><button>H</button><button>I</button><button>J</button>
            <button>K</button><button>L</button><button>M</button><button>N</button><button>O</button>
            <button>P</button><button>Q</button><button>R</button><button>S</button><button>T</button>
            <button>U</button><button>V</button><button>W</button><button>X</button><button>Y</button>
            <button>Z</button><button>0-9</button><button>#</button>
        </div>

        <table id="comic-list" border="1" cellpadding="10">
            <?php
            include 'koneksi.php'; // Menggunakan file koneksi yang sudah dibuat

            // Ambil data komik
            $sql = "SELECT judul, cover, deskripsi FROM comic ORDER BY judul ASC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='covers/" . htmlspecialchars($row['cover']) . "' alt='" . htmlspecialchars($row['judul']) . "' width='100'></td>";
                    echo "<td>";
                    echo "<strong>" . htmlspecialchars($row['judul']) . "</strong><br>";
                    echo htmlspecialchars($row['deskripsi']);
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>Tidak ada komik ditemukan.</td></tr>";
            }

            $conn->close();
            ?>
        </table>
    </div>

    <script src="lista-z.js"></script>
</body>
</html>
