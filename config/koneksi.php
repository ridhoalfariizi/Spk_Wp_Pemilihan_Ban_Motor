<?php
// Hapus session_start() dari sini
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_pemilihan_ban_motor";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
