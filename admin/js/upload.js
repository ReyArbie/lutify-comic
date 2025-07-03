document
  .getElementById("uploadForm")
  .addEventListener("submit", function (event) {
    const judul = document.getElementById("judul");
    const deskripsi = document.getElementById("deskripsi");
    const cover = document.getElementById("cover");

    if (!judul.value.trim()) {
      alert("Judul komik wajib diisi!!!");
      event.preventDefault();
      return;
    }

    if (!deskripsi.value.trim()) {
      alert("Deskripsi komik wajib diisi!!!");
      event.preventDefault();
      return;
    }

    if (!cover.files.length) {
      alert("Cover komik wajib diisi!!!");
      event.preventDefault();
      return;
    }

    // Validasi minimal satu genre dipilih
    const genreBoxes = document.querySelectorAll(
      '.genre-grid input[type=checkbox][name="genre[]"]'
    );
    let checkedGenre = false;
    genreBoxes.forEach(function (box) {
      if (box.checked) checkedGenre = true;
    });
    if (!checkedGenre) {
      alert("Minimal satu genre harus dipilih!");
      event.preventDefault();
      return;
    }
  });

// Select All logic
document.addEventListener("DOMContentLoaded", function () {
  var selectAll = document.getElementById("selectAllGenre");
  if (selectAll) {
    selectAll.addEventListener("change", function () {
      var checked = this.checked;
      document
        .querySelectorAll('.genre-grid input[type=checkbox][name="genre[]"]')
        .forEach(function (box) {
          box.checked = checked;
        });
    });
  }
});
