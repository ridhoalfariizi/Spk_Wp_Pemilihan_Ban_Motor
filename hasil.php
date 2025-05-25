<?php
include 'config/koneksi.php';
include 'auth.php';
include 'includes/header.php';
include 'includes/sidebar.php';

// Inisialisasi variabel untuk menghindari undefined variable
$kriteria = [];
$alternatif = [];
$hasil_ranking = [];
$total_bobot = 0;
$normalisasi = [];
$vektor_s = [];
$vektor_v = [];
$total_s = 0;

// Ambil data kriteria
$query_kriteria = "SELECT * FROM kriteria ORDER BY id";
$result_kriteria = mysqli_query($koneksi, $query_kriteria);

if ($result_kriteria) {
    while ($row = mysqli_fetch_assoc($result_kriteria)) {
        $kriteria[$row['id']] = $row;
    }
} else {
    echo "<script>alert('Error mengambil data kriteria: " . mysqli_error($koneksi) . "');</script>";
}

// Ambil data alternatif
$query_alternatif = "SELECT * FROM alternatif ORDER BY id";
$result_alternatif = mysqli_query($koneksi, $query_alternatif);

if ($result_alternatif) {
    while ($row = mysqli_fetch_assoc($result_alternatif)) {
        $alternatif[$row['id']] = $row;
    }
} else {
    echo "<script>alert('Error mengambil data alternatif: " . mysqli_error($koneksi) . "');</script>";
}

// Proses perhitungan WP hanya jika ada data kriteria dan alternatif
if (!empty($kriteria) && !empty($alternatif)) {
    try {
        // 1. Normalisasi bobot
        $total_bobot = array_sum(array_column($kriteria, 'bobot'));

        if ($total_bobot <= 0) {
            throw new Exception("Total bobot kriteria tidak valid");
        }

        foreach ($kriteria as $id => $k) {
            $normalisasi[$id] = $k['bobot'] / $total_bobot;
        }

        // 2. Hitung vektor S
        foreach ($alternatif as $id => $a) {
            $s = 1;
            foreach ($kriteria as $k_id => $k) {
                $column_map = [
                    1 => 'c1_ukuran',
                    2 => 'c2_tipe',
                    3 => 'c3_beban',
                    4 => 'c4_harga'
                ];

                if (!isset($column_map[$k_id])) {
                    continue;
                }

                $column_name = $column_map[$k_id];
                $nilai = $a[$column_name] ?? 1;

                // Pastikan nilai valid
                if (!is_numeric($nilai) || $nilai <= 0) {
                    $nilai = 1;
                }

                if ($k['jenis'] == 'cost') {
                    $s *= pow($nilai, -$normalisasi[$k_id]);
                } else {
                    $s *= pow($nilai, $normalisasi[$k_id]);
                }
            }
            $vektor_s[$id] = $s;
        }

        // 3. Hitung vektor V
        $total_s = array_sum($vektor_s);

        if ($total_s == 0) {
            $total_s = 1;
        }

        foreach ($vektor_s as $id => $s) {
            $vektor_v[$id] = $s / $total_s;
        }

        // 4. Ranking
        arsort($vektor_v);
        $ranking = 1;

        foreach ($vektor_v as $id => $v) {
            $hasil_ranking[$id] = [
                'nilai_s' => $vektor_s[$id],
                'nilai_v' => $v,
                'ranking' => $ranking++
            ];
        }

        // Simpan hasil ke database
        mysqli_query($koneksi, "TRUNCATE TABLE hasil");

        foreach ($hasil_ranking as $id => $data) {
            $query_insert = "INSERT INTO hasil (id_alternatif, nilai_s, nilai_v, ranking) 
                            VALUES ($id, {$data['nilai_s']}, {$data['nilai_v']}, {$data['ranking']})";
            mysqli_query($koneksi, $query_insert);
        }
    } catch (Exception $e) {
        echo "<script>alert('Error dalam perhitungan: " . $e->getMessage() . "');</script>";
    }
}
?>

<main class="ml-64 p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-calculator mr-2"></i> Hasil Perhitungan Weighted Product
        </h1>

        <?php if (empty($kriteria)): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Data kriteria belum tersedia. Silakan tambahkan data kriteria terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
        <?php elseif (empty($alternatif)): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            Data alternatif belum tersedia. Silakan tambahkan data alternatif terlebih dahulu.
                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Tabel Bobot Kriteria -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">1. Bobot Kriteria</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bobot</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($kriteria as $id => $k): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $k['bobot'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        <?= $k['jenis'] == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $k['jenis'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Normalisasi Bobot -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">2. Normalisasi Bobot</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proses Perhitungan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($kriteria as $id => $k): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($k['nama_kriteria']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $k['bobot'] ?> / <?= $total_bobot ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($normalisasi[$id], 9) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Vektor S -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">3. Perhitungan Vektor S</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ban</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proses Perhitungan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($alternatif as $id => $a): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($a['nama']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?php
                                        $formula_parts = [];
                                        foreach ($kriteria as $k_id => $k) {
                                            $column_map = [
                                                1 => 'c1_ukuran',
                                                2 => 'c2_tipe',
                                                3 => 'c3_beban',
                                                4 => 'c4_harga'
                                            ];
                                            $column_name = $column_map[$k_id] ?? '';
                                            $nilai = $a[$column_name] ?? 1;
                                            $formula_parts[] = "({$nilai}<sup>" . number_format($normalisasi[$k_id], 4) . "</sup>)";
                                        }
                                        echo implode(" × ", $formula_parts);
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= number_format($vektor_s[$id] ?? 0, 9) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Vektor V dan Ranking -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">4. Perhitungan Vektor V dan Ranking</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ranking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ban</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proses Perhitungan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai V</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $counter = 1;
                            foreach ($hasil_ranking as $id => $data):
                                $alternatif_data = $alternatif[$id] ?? null;
                                if (!$alternatif_data) continue;
                            ?>
                                <tr class="<?= $counter == 1 ? 'bg-green-50' : '' ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?= $counter == 1 ? 'text-green-800 font-bold' : 'text-gray-900' ?>">
                                        <?= $counter++ ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?= $counter - 1 == 1 ? 'text-green-800 font-bold' : 'text-gray-900' ?>">
                                        <?= htmlspecialchars($alternatif_data['nama']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= number_format($data['nilai_s'], 9) ?> / <?= number_format($total_s, 9) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm <?= $counter - 1 == 1 ? 'text-green-800 font-bold' : 'text-gray-500' ?>">
                                        <?= number_format($data['nilai_v'], 9) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kesimpulan -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kesimpulan</h3>
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">
                                Berdasarkan perhitungan WP, alternatif terbaik adalah:
                                <span class="font-bold"><?= htmlspecialchars($alternatif[key($hasil_ranking)]['nama'] ?? '-') ?></span>
                                dengan nilai V = <?= number_format(current($hasil_ranking)['nilai_v'] ?? 0, 4) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>