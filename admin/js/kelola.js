let editId = null;
let editRow = null;
let lastFormRow = null;

// Inline Edit
document.querySelectorAll(".editBtn").forEach(function (btn) {
  btn.onclick = async function () {
    // Tutup form edit lain jika terbuka
    if (editRow && lastFormRow && lastFormRow.parentNode) {
      document.body.appendChild(document.getElementById("editForm"));
      document.getElementById("editForm").style.display = "none";
      lastFormRow.remove();
    }

    const row = btn.closest("tr");
    editId = row.getAttribute("data-id");
    editRow = row;

    // Ambil data komik (GET AJAX)
    fetch("?get_id=" + editId)
      .then((r) => r.json())
      .then((data) => {
        document.getElementById("editTitle").value =
          data.comic.title_comic || "";
        document.getElementById("editDescription").value =
          data.comic.summary_comic || "";
        // Cover
        if (data.comic.cover_comic) {
          document.getElementById("currentCover").src =
            "data:image/jpeg;base64," + data.comic.cover_comic;
          document.getElementById("currentCover").style.display = "inline";
        } else {
          document.getElementById("currentCover").src = "";
          document.getElementById("currentCover").style.display = "none";
        }
        // Genre
        let allGenres = ["action", "comedy", "romance", "horror", "adventure"];
        allGenres.forEach((g) => {
          document.querySelector(
            '#editGenreGrid input[value="' + g + '"]'
          ).checked = data.genre && data.genre[g] == 1;
        });
      });

    document.getElementById("editForm").style.display = "block";

    // Pindah form edit ke bawah baris aktif
    if (row.nextSibling && row.nextSibling.id === "editFormRow")
      row.nextSibling.remove();
    const editFormRow = document.createElement("tr");
    editFormRow.id = "editFormRow";
    const td = document.createElement("td");
    td.colSpan = 5;
    td.appendChild(document.getElementById("editForm"));
    editFormRow.appendChild(td);
    row.parentNode.insertBefore(editFormRow, row.nextSibling);
    lastFormRow = editFormRow;
  };
});

document.getElementById("cancelEdit").onclick = function () {
  document.getElementById("editForm").style.display = "none";
  if (editRow && lastFormRow && lastFormRow.parentNode) {
    lastFormRow.remove();
  }
  editId = null;
  editRow = null;
  lastFormRow = null;
};

document.getElementById("realEditForm").onsubmit = function (e) {
  e.preventDefault();
  if (!editId) return;
  const fd = new FormData(document.getElementById("realEditForm"));
  fd.append("edit_id", editId);
  fetch("", { method: "POST", body: fd })
    .then((res) => res.text())
    .then((res) => {
      if (res.trim() == "updated") location.reload();
      else alert("Gagal update!");
    });
};

// Delete
let deleteId = null;
document.querySelectorAll(".deleteBtn").forEach(function (btn) {
  btn.onclick = function () {
    const row = btn.closest("tr");
    deleteId = row.getAttribute("data-id");
    document.getElementById("deleteModal").style.display = "block";
  };
});
document.getElementById("cancelDelete").onclick = function () {
  document.getElementById("deleteModal").style.display = "none";
  deleteId = null;
};
document.getElementById("confirmDelete").onclick = function () {
  if (!deleteId) return;
  const fd = new FormData();
  fd.append("delete_id", deleteId);
  fetch("", { method: "POST", body: fd })
    .then((res) => res.text())
    .then((res) => {
      if (res.trim() == "deleted") location.reload();
      else alert("Gagal hapus!");
    });
};
document.querySelector(".menu-toggle").addEventListener("click", function () {
  const navCenter = document.querySelector(".nav-center");
  navCenter.classList.toggle("active");
  this.classList.toggle("active");
});
