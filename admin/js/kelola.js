document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".editBtn");
  const deleteButtons = document.querySelectorAll(".deleteBtn");
  const editForm = document.getElementById("editForm");
  const saveChangesBtn = document.getElementById("saveChanges");
  const cancelEditBtn = document.getElementById("cancelEdit");

  const deleteModal = document.getElementById("deleteModal");
  const confirmDeleteBtn = document.getElementById("confirmDelete");
  const cancelDeleteBtn = document.getElementById("cancelDelete");

  let currentComicRow = null;
  let rowToDelete = null;

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");
      const cells = row.querySelectorAll("td");
      document.getElementById("editTitle").value = cells[0].textContent;
      document.getElementById("editDescription").value =
        cells[1].textContent.trim();
      editForm.style.display = "block";
      editForm.scrollIntoView({ behavior: "smooth" });
      currentComicRow = row;
    });
  });

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      rowToDelete = this.closest("tr");
      deleteModal.style.display = "block";
    });
  });

  confirmDeleteBtn.addEventListener("click", function () {
    if (rowToDelete) {
      rowToDelete.remove();
      rowToDelete = null;
      deleteModal.style.display = "none";
    }
  });

  cancelDeleteBtn.addEventListener("click", function () {
    deleteModal.style.display = "none";
    rowToDelete = null;
  });

  saveChangesBtn.addEventListener("click", function () {
    if (currentComicRow) {
      const title = document.getElementById("editTitle").value;
      const description = document.getElementById("editDescription").value;
      const cells = currentComicRow.querySelectorAll("td");
      cells[0].textContent = title;
      cells[1].textContent = description;
      editForm.style.display = "none";
      currentComicRow = null;
    }
  });

  cancelEditBtn.addEventListener("click", function () {
    editForm.style.display = "none";
    currentComicRow = null;
  });
});
