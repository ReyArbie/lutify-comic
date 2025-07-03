document.addEventListener("DOMContentLoaded", function () {
  const episodeForm = document.querySelector(".form-episode");
  const editButtons = document.querySelectorAll("table button:first-child");
  const deleteButtons = document.querySelectorAll("table button:last-child");

  // Delete button click handler
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (confirm("Apakah Anda yakin ingin menghapus episode ini?")) {
        const row = this.closest("tr");
        row.remove();
        updateEpisodeCount();
      }
    });
  });

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");
      const cells = row.querySelectorAll("td");

      // In a real implementation, you'd populate a form with these values
      alert(`Edit episode: ${cells[1].textContent} (${cells[0].textContent})`);
    });
  });

  episodeForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const komik = document.getElementById("komik").value;
    const judulEpisode = document.getElementById("judulEpisode").value;
    const nomorEpisode = document.getElementById("nomorEpisode").value;
    const fileEpisode = document.getElementById("fileEpisode").files[0];

    // Basic validation
    if (!komik || !judulEpisode || !nomorEpisode || !fileEpisode) {
      alert("Harap isi semua field!");
      return;
    }

    const table = document.querySelector("table tbody");
    const newRow = document.createElement("tr");

    newRow.innerHTML = `
            <td>${komik}</td>
            <td>${judulEpisode}</td>
            <td>${nomorEpisode}</td>
            <td>
                <button>Edit</button>
                <button>Hapus</button>
            </td>
        `;

    table.appendChild(newRow);

    newRow
      .querySelector("button:first-child")
      .addEventListener("click", function () {
        alert(`Edit episode: ${judulEpisode} (${komik})`);
      });

    newRow
      .querySelector("button:last-child")
      .addEventListener("click", function () {
        if (confirm("Apakah Anda yakin ingin menghapus episode ini?")) {
          newRow.remove();
          updateEpisodeCount();
        }
      });

    episodeForm.reset();

    alert(
      `Episode "${judulEpisode}" berhasil ditambahkan ke komik "${komik}"!`
    );

    updateEpisodeCount();
  });

  function updateEpisodeCount() {
    const episodeCount = document.querySelectorAll("table tbody tr").length;
  }
});
