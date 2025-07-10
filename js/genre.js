document.addEventListener("DOMContentLoaded", function () {
  const filterButtons = document.querySelectorAll("#filter-buttons button");
  const comicList = document.querySelector(".comic-list");
  const searchInput = document.getElementById("searchComic");
  let selectedLetter = "";

  // Function untuk load data komik
  function fetchComics(letter, search) {
    let params = [];
    if (letter) params.push("letter=" + encodeURIComponent(letter));
    if (search) params.push("search=" + encodeURIComponent(search));
    fetch("list_a_z_data.php?" + params.join("&"))
      .then((res) => res.text())
      .then((html) => {
        comicList.innerHTML = html;
      });
  }

  // Event tombol filter A-Z
  filterButtons.forEach(function (btn) {
    btn.addEventListener("click", function () {
      filterButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      selectedLetter = btn.getAttribute("data-letter");
      fetchComics(selectedLetter, searchInput.value.trim());
    });
  });

  // Event pencarian (otomatis search saat diketik)
  searchInput.addEventListener("input", function () {
    fetchComics(selectedLetter, searchInput.value.trim());
  });

  // Jika form disubmit, tetap lakukan search (untuk tombol CARI)
  searchInput.form.addEventListener("submit", function (e) {
    e.preventDefault();
    fetchComics(selectedLetter, searchInput.value.trim());
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".genre-btn");
  const comics = document.querySelectorAll(".comic-card");

  buttons.forEach((btn) => {
    btn.addEventListener("click", function () {
      // Toggle tombol active
      buttons.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      const genre = this.getAttribute("data-genre").toLowerCase();
      comics.forEach((card) => {
        let cardGenres = card.getAttribute("data-genre").split(",");
        if (genre === "all") {
          card.style.display = "";
        } else {
          if (cardGenres.includes(genre)) {
            card.style.display = "";
          } else {
            card.style.display = "none";
          }
        }
      });
    });
  });
});
