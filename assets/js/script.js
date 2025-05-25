// Konfirmasi sebelum hapus
function confirmDelete() {
  return confirm("Apakah Anda yakin ingin menghapus data ini?");
}

// Toggle sidebar di mobile
document.addEventListener("DOMContentLoaded", function () {
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.querySelector(".sidebar");

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("hidden");
    });
  }

  // Auto close alert setelah 5 detik
  const alert = document.querySelector(".alert");
  if (alert) {
    setTimeout(() => {
      alert.remove();
    }, 5000);
  }
});
