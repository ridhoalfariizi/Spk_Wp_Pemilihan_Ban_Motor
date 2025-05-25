<?php
session_start();
require 'config/koneksi.php';

// Greeting otomatis
$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = "Selamat Pagi!";
} elseif ($hour >= 12 && $hour < 15) {
    $greeting = "Selamat Siang!";
} elseif ($hour >= 15 && $hour < 18) {
    $greeting = "Selamat Sore!";
} else {
    $greeting = "Selamat Malam!";
}

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['login'] = true;
        $_SESSION['flash'] = "Login berhasil!";
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

$flash = '';
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - SPK Ban Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col md:flex-row">

        <!-- Gambar kiri -->
        <div class="hidden md:flex md:w-1/2 items-center justify-center bg-gray-100 p-8">
            <img src="assets/img/Delivery-cuate.png" alt="Ilustrasi Motor" class="w-full max-w-sm drop-shadow-md">
        </div>

        <!-- Form login -->
        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">

            <!-- Greeting -->
            <div class="text-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800"><?= $greeting ?></h2>
                <p class="text-sm text-gray-600">Silakan login untuk mengakses sistem rekomendasi ban motor</p>
            </div>

            <!-- Flash -->
            <?php if ($flash): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 mb-4 rounded text-sm text-center"><?= $flash ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded text-sm text-center"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" required placeholder="Username"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" required placeholder="Password"
                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <span class="absolute right-3 top-2.5 text-gray-400 cursor-pointer" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium shadow">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-400">
                &copy; <?= date('Y') ?> SPK Ban Motor. Dibuat oleh Ridho.
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>