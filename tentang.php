<?php
include 'config/koneksi.php';
include 'auth.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="ml-64 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                <i class="fas fa-info-circle mr-2"></i> Tentang Sistem
            </h1>
        </div>

        <!-- Deskripsi Sistem -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 hover:shadow-lg transition">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Deskripsi Sistem</h2>
            <div class="space-y-4 text-gray-700">
                <p>
                    Sistem Rekomendasi Pemilihan Ban Sepeda Motor ini dikembangkan untuk membantu pengguna dalam memilih ban sepeda motor terbaik berdasarkan kriteria tertentu menggunakan metode Weighted Product (WP).
                </p>
                <p>
                    Sistem ini mempertimbangkan 4 kriteria utama yaitu Ukuran, Tipe/Jenis, Beban Maksimal, dan Harga. Metode WP digunakan untuk menghitung dan memberikan rekomendasi terbaik berdasarkan bobot yang telah ditentukan untuk setiap kriteria.
                </p>
            </div>
        </div>

        <!-- Metode WP -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 hover:shadow-lg transition">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Metode Weighted Product (WP)</h2>
            <div class="space-y-4 text-gray-700">
                <p>
                    Metode Weighted Product (WP) adalah salah satu metode dalam Sistem Pendukung Keputusan (SPK) yang digunakan untuk mengevaluasi beberapa alternatif berdasarkan beberapa kriteria. Kelebihan metode WP adalah:
                </p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Proses perhitungan yang relatif cepat</li>
                    <li>Mampu menangani kriteria benefit dan cost</li>
                    <li>Hasil perhitungan yang akurat</li>
                    <li>Mudah diimplementasikan dalam sistem komputer</li>
                </ul>
                <p>
                    Langkah-langkah metode WP meliputi: normalisasi bobot, perhitungan vektor S, perhitungan vektor V, dan pengurutan ranking alternatif.
                </p>
            </div>
        </div>

        <!-- Referensi dan Pembuat -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
            <div class="space-y-6">
                <!-- Referensi Jurnal -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Referensi Jurnal</h2>
                    <div class="space-y-2 text-gray-700">
                        <p>Sistem ini dikembangkan berdasarkan jurnal penelitian dengan judul:</p>
                        <p class="font-medium">"Penerapan Metode Weighted Product dalam Sistem Rekomendasi Pemilihan Ban Sepeda Motor"</p>
                        <p>Penulis: Ivan Hermawan, Alexander Waworuutu</p>
                        <p>
                            DOI: <a href="https://doi.org/10.33395/jmp.v12i1.12365" target="_blank" class="text-blue-600 hover:underline">10.33395/jmp.v12i1.12365</a>
                        </p>
                    </div>
                </div>

                <!-- Pembuat Sistem -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Pembuat Sistem</h2>
                    <div class="space-y-1 text-gray-700">
                        <p>Nama: Muhamad Ridho Alfarizi</p>
                        <p>Institusi: Universitas Banten Jaya, Teknik Informatika</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>