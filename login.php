<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-slate-50 flex h-screen font-sans overflow-hidden">

    <div class="hidden lg:flex lg:w-1/2 bg-cover bg-center relative"
        style="background-image: url('https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=2070&auto=format&fit=crop');">
        <div
            class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-slate-900/40 flex flex-col justify-end p-16">
            <div class="text-white transform transition-all duration-700 hover:scale-105">
                <div
                    class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-blue-500/30">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                </div>
                <h1 class="text-4xl font-extrabold mb-4 leading-tight">Sistem Informasi <br> Akademik & Keuangan</h1>
                <p class="text-slate-300 text-lg max-w-md">Kelola data santri, jadwal akademik, dan pantau pembayaran
                    SPP dalam satu pintu dengan mudah dan efisien.</p>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 sm:p-12">
        <div class="w-full max-w-md">

            <div class="lg:hidden mb-8 text-center">
                <div
                    class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl shadow-blue-500/30 text-white">
                    <i class="fas fa-graduation-cap text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Sistem Akademik</h2>
            </div>

            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Selamat Datang 👋</h2>
                <p class="text-slate-500 font-medium">Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "gagal") {
                    echo "<div class='flex items-center p-4 mb-6 text-sm text-red-800 border border-red-300 rounded-xl bg-red-50' role='alert'>
                            <i class='fas fa-exclamation-circle mr-3 text-lg'></i>
                            <span class='font-medium'>Gagal!</span> Username atau Password salah.
                          </div>";
                } else if ($_GET['pesan'] == "logout") {
                    echo "<div class='flex items-center p-4 mb-6 text-sm text-green-800 border border-green-300 rounded-xl bg-green-50' role='alert'>
                            <i class='fas fa-check-circle mr-3 text-lg'></i>
                            <span class='font-medium'>Berhasil!</span> Anda telah logout dengan aman.
                          </div>";
                } else if ($_GET['pesan'] == "belum_login") {
                    echo "<div class='flex items-center p-4 mb-6 text-sm text-yellow-800 border border-yellow-300 rounded-xl bg-yellow-50' role='alert'>
                            <i class='fas fa-lock mr-3 text-lg'></i>
                            <span class='font-medium'>Akses Ditolak!</span> Silakan login terlebih dahulu.
                          </div>";
                }
            }
            ?>

            <form action="cek_login.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="username">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <input type="text" name="username" id="username"
                            class="pl-11 w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:bg-white text-slate-800 transition-all duration-200 outline-none font-medium"
                            placeholder="Masukkan username Anda" autocomplete="off" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2" for="password">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="pl-11 w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:bg-white text-slate-800 transition-all duration-200 outline-none font-medium"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/40 transition-all duration-300 transform hover:-translate-y-1 active:translate-y-0">
                    Masuk Sekarang <i class="fas fa-arrow-right text-sm"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-slate-500 font-medium">
                    Lupa kata sandi? <a href="#"
                        class="text-blue-600 font-bold hover:text-blue-800 transition-colors">Hubungi Admin</a>
                </p>
            </div>
        </div>
    </div>

</body>

</html>