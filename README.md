# 🚀 Sistem Rekomendasi Pemilihan Ban Sepeda Motor (Metode Weighted Product)

Proyek ini adalah Sistem Pendukung Keputusan (SPK) berbasis web yang menggunakan **Metode Weighted Product (WP)** untuk memberikan rekomendasi ban sepeda motor terbaik. Sistem ini mempertimbangkan beberapa kriteria penting seperti ukuran, tipe, beban maksimal, dan harga ban.

## 🛠️ Fitur Utama

- Manajemen Data Alternatif Ban
- Input dan Edit Data Kriteria & Range
- Perhitungan WP (Vektor S & Vektor V)
- Visualisasi Bobot & Nilai V dengan Chart.js
- Responsive Design menggunakan Tailwind CSS
- Statistik ringkasan dan kesimpulan alternatif terbaik

## 🧠 Metode WP (Weighted Product)

Weighted Product adalah salah satu metode dalam SPK yang digunakan untuk evaluasi alternatif berdasarkan banyak kriteria. Keunggulan metode WP:

- Menangani kriteria **benefit** dan **cost**
- Proses perhitungan cepat
- Akurat dan mudah diimplementasikan

### Langkah-langkah:

1. Normalisasi bobot kriteria
2. Hitung Vektor S tiap alternatif
3. Hitung Vektor V dari S
4. Ranking alternatif berdasarkan nilai V


## 🧪 Teknologi yang Digunakan

- **PHP** (Native)
- **MySQL** (Database)
- **Tailwind CSS** (Styling)
- **Font Awesome** (Icons)
- **Chart.js** (Visualisasi data)
- **JavaScript** (Interaksi dinamis)

## 📝 Cara Menjalankan

1. Clone repository ini
2. Import file database `db_pemilihan_ban_motor.sql` ke MySQL
3. Ubah konfigurasi database di `config/koneksi.php`
4. Jalankan `index.php` di localhost (XAMPP/Laragon)

## 📚 Referensi Jurnal

Penerapan metode WP diambil dari:
> *"Penerapan Metode Weighted Product dalam Sistem Rekomendasi Pemilihan Ban Sepeda Motor"*  
> Penulis: Ivan Hermawan, Alexander Waworuutu  
> DOI: [10.33395/jmp.v12i1.12365](https://doi.org/10.33395/jmp.v12i1.12365)

## 👨‍💻 Developer

- **Nama**: Muhamad Ridho Alfarizi  
- **Kampus**: Universitas Banten Jaya  
- **Program Studi**: Teknik Informatika

---

> Proyek ini dikembangkan sebagai bagian dari tugas akhir/skripsi dan bertujuan untuk membantu proses pengambilan keputusan dalam memilih ban sepeda motor secara objektif.


