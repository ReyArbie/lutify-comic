# Lutify Comic

**Lutify Comic** adalah aplikasi web sederhana untuk membaca komik online. Aplikasi ini dibangun menggunakan PHP, HTML, CSS, dan JavaScript, serta terhubung ke database MySQL untuk menyimpan data komik.

## Fitur

- 📚 Menampilkan daftar komik berdasarkan genre dan abjad
- 🔍 Pencarian komik berdasarkan judul
- 📄 Halaman detail komik dengan informasi lengkap
- 🖼️ Galeri gambar komik (bisa diperbesar)
- 🗂️ Navigasi berdasarkan genre dan abjad A-Z
- ⚙️ Struktur backend dengan koneksi database MySQL

```markdown
## Struktur Proyek

- **File utama:**
  - index.php
  - detail-comic.php
  - genre.php
  - lista-z.php
  - genre.js
  - lista-z.js
  - koneksi.php
  - README.md

- **Direktori:**
  - admin/ - Berisi file-file panel admin
  - images/ - Berisi gambar-gambar komik
  - styles/ - Berisi file CSS


## Instalasi

1. **Klon repositori ini**

   ```bash
   git clone https://github.com/ReyArbie/lutify-comic.git
   ```

2. **Impor database**

   - Buat database MySQL baru, misalnya `lutify_comic`.
   - Impor file SQL (jika tersedia) ke database tersebut.

3. **Atur koneksi database**

   - Buka file `koneksi.php`.
   - Edit variabel `host`, `username`, `password`, dan `dbname` sesuai konfigurasi database Anda.

4. **Jalankan aplikasi**
   - Pindahkan folder proyek ke dalam direktori server lokal Anda (misal, `htdocs` jika memakai XAMPP).
   - Akses lewat browser: `http://localhost/lutify-comic`.

## Kontribusi

Kontribusi sangat terbuka!

1. Fork repo ini.
2. Buat branch baru (`git checkout -b fitur-baru`).
3. Commit perubahan Anda (`git commit -m 'fitur: fitur baru'`).
4. Push ke branch Anda (`git push origin fitur-baru`).
5. Buat pull request.
