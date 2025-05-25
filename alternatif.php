<?php
include 'config/koneksi.php';
include 'auth.php';
include 'includes/header.php';
include 'includes/sidebar.php';

?>

<div class="ml-64 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Alternatif Ban</h1>
        <a href="input.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Tambah Alternatif
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Ban</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukuran (Range)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe (Range)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beban Maks (Range)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga (Range)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                $query = "SELECT * FROM alternatif ORDER BY id";
                $result = mysqli_query($koneksi, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($row['nama']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $row['c1_ukuran'] ?>
                                <span class="text-blue-600">(<?= $row['c1_range'] ?>)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $row['c2_tipe'] ?>
                                <span class="text-green-600">(<?= $row['c2_range'] ?>)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $row['c3_beban'] ?>kg
                                <span class="text-purple-600">(<?= $row['c3_range'] ?>)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp<?= number_format($row['c4_harga'], 0, ',', '.') ?>
                                <span class="text-yellow-600">(<?= $row['c4_range'] ?>)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Hapus data ini?')">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                } else {
                    ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data alternatif
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>