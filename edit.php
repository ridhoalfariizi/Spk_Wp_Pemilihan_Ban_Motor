<?php
include 'config/koneksi.php';
include 'auth.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data alternatif berdasarkan ID
$query = "SELECT * FROM alternatif WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='alternatif.php';</script>";
    exit;
}

// Ambil data range kriteria
$range_data = ['ukuran' => [], 'tipe' => [], 'beban' => [], 'harga' => []];
$query_range = "SELECT r.*, k.nama_kriteria FROM kriteria_range r
                JOIN kriteria k ON r.kriteria_id = k.id
                ORDER BY k.id, r.min_value";
$result_range = mysqli_query($koneksi, $query_range);
while ($row = mysqli_fetch_assoc($result_range)) {
    if ($row['kriteria_id'] == 1) $range_data['ukuran'][] = $row;
    elseif ($row['kriteria_id'] == 2) $range_data['tipe'][] = $row;
    elseif ($row['kriteria_id'] == 3) $range_data['beban'][] = $row;
    elseif ($row['kriteria_id'] == 4) $range_data['harga'][] = $row;
}

// Proses Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $c1_ukuran = $_POST['c1_ukuran'];
    $c1_range = $_POST['c1_range'];
    $c2_tipe = $_POST['c2_tipe'];
    $c2_range = $_POST['c2_range'];
    $c3_beban = $_POST['c3_beban'];
    $c3_range = $_POST['c3_range'];
    $c4_harga = $_POST['c4_harga'];
    $c4_range = $_POST['c4_range'];

    $query = "UPDATE alternatif SET 
        nama = '$nama',
        c1_ukuran = $c1_ukuran,
        c1_range = '$c1_range',
        c2_tipe = $c2_tipe,
        c2_range = '$c2_range',
        c3_beban = $c3_beban,
        c3_range = '$c3_range',
        c4_harga = $c4_harga,
        c4_range = '$c4_range'
        WHERE id = $id";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='alternatif.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data');</script>";
    }
}
?>

<main class="ml-64 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-6"><i class="fas fa-edit mr-2"></i>Edit Data Alternatif</h2>
        <form method="POST" class="space-y-4">

            <div>
                <label class="block mb-1">Nama Ban</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required class="w-full border px-3 py-2 rounded" />
            </div>

            <!-- Ukuran -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Ukuran (50-130)</label>
                    <input type="number" name="c1_ukuran" id="c1_ukuran" value="<?= $data['c1_ukuran'] ?>" min="50" max="130" required
                        class="w-full border px-3 py-2 rounded"
                        onchange="updateRange('c1_ukuran', 'c1_range', <?= json_encode($range_data['ukuran']) ?>)">
                </div>
                <div>
                    <label class="block mb-1">Kategori Ukuran</label>
                    <select name="c1_range" id="c1_range" required class="w-full border px-3 py-2 rounded">
                        <option value="">Pilih</option>
                        <?php foreach ($range_data['ukuran'] as $range): ?>
                            <option value="<?= $range['nama_range'] ?>" <?= $data['c1_range'] == $range['nama_range'] ? 'selected' : '' ?>>
                                <?= $range['nama_range'] ?> (<?= $range['min_value'] ?> - <?= $range['max_value'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Tipe -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Tipe (0-100)</label>
                    <input type="number" name="c2_tipe" id="c2_tipe" value="<?= $data['c2_tipe'] ?>" min="0" max="100" required
                        class="w-full border px-3 py-2 rounded"
                        onchange="updateRange('c2_tipe', 'c2_range', <?= json_encode($range_data['tipe']) ?>)">
                </div>
                <div>
                    <label class="block mb-1">Kategori Tipe</label>
                    <select name="c2_range" id="c2_range" required class="w-full border px-3 py-2 rounded">
                        <option value="">Pilih</option>
                        <?php foreach ($range_data['tipe'] as $range): ?>
                            <option value="<?= $range['nama_range'] ?>" <?= $data['c2_range'] == $range['nama_range'] ? 'selected' : '' ?>>
                                <?= $range['nama_range'] ?> (<?= $range['min_value'] ?> - <?= $range['max_value'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Beban Maksimal -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Beban Maksimal (kg)</label>
                    <input type="number" name="c3_beban" id="c3_beban" value="<?= $data['c3_beban'] ?>" min="0" max="1000" required
                        class="w-full border px-3 py-2 rounded"
                        onchange="updateRange('c3_beban', 'c3_range', <?= json_encode($range_data['beban']) ?>)">
                </div>
                <div>
                    <label class="block mb-1">Kategori Beban</label>
                    <select name="c3_range" id="c3_range" required class="w-full border px-3 py-2 rounded">
                        <option value="">Pilih</option>
                        <?php foreach ($range_data['beban'] as $range): ?>
                            <option value="<?= $range['nama_range'] ?>" <?= $data['c3_range'] == $range['nama_range'] ? 'selected' : '' ?>>
                                <?= $range['nama_range'] ?> (<?= $range['min_value'] ?> - <?= $range['max_value'] ?> kg)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Harga -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">Harga (Rp)</label>
                    <input type="number" name="c4_harga" id="c4_harga" value="<?= $data['c4_harga'] ?>" min="0" max="1000000" required
                        class="w-full border px-3 py-2 rounded"
                        onchange="updateRange('c4_harga', 'c4_range', <?= json_encode($range_data['harga']) ?>)">
                </div>
                <div>
                    <label class="block mb-1">Kategori Harga</label>
                    <select name="c4_range" id="c4_range" required class="w-full border px-3 py-2 rounded">
                        <option value="">Pilih</option>
                        <?php foreach ($range_data['harga'] as $range): ?>
                            <option value="<?= $range['nama_range'] ?>" <?= $data['c4_range'] == $range['nama_range'] ? 'selected' : '' ?>>
                                <?= $range['nama_range'] ?> (Rp<?= number_format($range['min_value']) ?> - Rp<?= number_format($range['max_value']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateRange(inputId, selectId, ranges) {
            const inputValue = parseInt(document.getElementById(inputId).value);
            const selectElement = document.getElementById(selectId);
            for (const range of ranges) {
                if (inputValue >= range.min_value && inputValue <= range.max_value) {
                    selectElement.value = range.nama_range;
                    break;
                }
            }
        }
    </script>
</main>

<?php include 'includes/footer.php'; ?>