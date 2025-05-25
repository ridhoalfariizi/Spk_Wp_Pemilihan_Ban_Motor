<?php
include 'config/koneksi.php';
include 'auth.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="ml-0 md:ml-64 p-4 md:p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Sistem Rekomendasi Ban Sepeda Motor</h1>
        <p class="text-gray-600 mt-2 text-sm md:text-base">
            Sistem ini menggunakan metode Weighted Product (WP) untuk memberikan rekomendasi ban terbaik
            berdasarkan kriteria ukuran, tipe, beban maksimal, dan harga.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Total Alternatif -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-tire text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Alternatif</p>
                    <?php
                    $query = "SELECT COUNT(*) as total FROM alternatif";
                    $result = mysqli_query($koneksi, $query);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1"><?= $row['total'] ?></p>
                </div>
            </div>
        </div>

        <!-- Alternatif Terbaik -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-trophy text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Alternatif Terbaik</p>
                    <?php
                    $query = "SELECT a.nama FROM alternatif a 
                              JOIN hasil h ON a.id = h.id_alternatif 
                              WHERE h.ranking = 1 LIMIT 1";
                    $result = mysqli_query($koneksi, $query);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <p class="text-lg md:text-xl font-bold text-gray-800 mt-1 truncate">
                        <?= $row['nama'] ?? '<span class="text-gray-400">Belum ada data</span>' ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Rata-rata Nilai V -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rata-rata Nilai V</p>
                    <?php
                    $query = "SELECT AVG(nilai_v) as avg_v FROM hasil";
                    $result = mysqli_query($koneksi, $query);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">
                        <?= $row['avg_v'] ? number_format($row['avg_v'], 4) : '0.0000' ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Kriteria -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                    <i class="fas fa-list-check text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Kriteria</p>
                    <?php
                    $query = "SELECT COUNT(*) as total FROM kriteria";
                    $result = mysqli_query($koneksi, $query);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1"><?= $row['total'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Pie Chart -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Bobot Kriteria</h3>
            <div class="h-64 md:h-80">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

        <!-- Bar Chart -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Nilai V Alternatif</h3>
            <div class="h-64 md:h-80">
                <?php
                $query_check = "SELECT COUNT(*) as total FROM alternatif";
                $result_check = mysqli_query($koneksi, $query_check);
                $row_check = mysqli_fetch_assoc($result_check);

                if ($row_check['total'] > 0): ?>
                    <canvas id="barChart"></canvas>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <i class="fas fa-chart-bar text-4xl mb-2"></i>
                        <p>Tidak ada data alternatif</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top 5 Alternatif -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Alternatif Terbaik</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ranking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ban</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai V</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $query = "SELECT a.nama, h.nilai_v, h.ranking
                              FROM alternatif a
                              JOIN hasil h ON a.id = h.id_alternatif
                              ORDER BY h.ranking ASC
                              LIMIT 5";
                    $result = mysqli_query($koneksi, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                            <tr class="<?= $row['ranking'] == 1 ? 'bg-green-50' : '' ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium <?= $row['ranking'] == 1 ? 'text-green-800 font-bold' : 'text-gray-900' ?>">
                                    <?= $row['ranking'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm <?= $row['ranking'] == 1 ? 'text-green-800 font-bold' : 'text-gray-500' ?>">
                                    <?= htmlspecialchars($row['nama']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm <?= $row['ranking'] == 1 ? 'text-green-800 font-bold' : 'text-gray-500' ?>">
                                    <?= number_format($row['nilai_v'], 4) ?>
                                </td>
                            </tr>
                        <?php
                        endwhile;
                    } else {
                        ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data alternatif
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>