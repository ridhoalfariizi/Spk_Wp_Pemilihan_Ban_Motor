<?php
header('Content-Type: application/json');
require '../config/koneksi.php';

$query = "SELECT nama_kriteria, bobot FROM kriteria ORDER BY id";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die(json_encode(['error' => mysqli_error($koneksi)]));
}

$data = ['labels' => [], 'weights' => []];

while ($row = mysqli_fetch_assoc($result)) {
    $data['labels'][] = $row['nama_kriteria'];
    $data['weights'][] = (float)$row['bobot'];
}

echo json_encode($data);
