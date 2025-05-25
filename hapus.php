<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Hapus dari tabel hasil terlebih dahulu (jika ada)
    $query = "DELETE FROM hasil WHERE id_alternatif = $id";
    mysqli_query($koneksi, $query);

    // Hapus dari tabel alternatif
    $query = "DELETE FROM alternatif WHERE id = $id";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='alternatif.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($koneksi) . "'); window.location.href='alternatif.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.location.href='alternatif.php';</script>";
}
