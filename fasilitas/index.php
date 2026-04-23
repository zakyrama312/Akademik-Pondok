<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas Pondok - Al Falah Salafiyah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden flex flex-col min-h-screen">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="index.php" class="flex items-center gap-3 cursor-pointer">
                    <img src="../uploads/img/Logo_AlFalah.png" alt="Logo Al Falah"
                        class="w-10 h-10 object-contain drop-shadow-md">
                    <span class="font-extrabold text-xl tracking-tight text-slate-800">Al Falah<span
                            class="text-emerald-600"> Salafiyah</span></span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <!-- <a href="../index.php#profil"
                        class="text-slate-600 hover:text-emerald-600 font-medium transition">Profil</a> -->
                    <a href="../index.php#program"
                        class="text-slate-600 hover:text-emerald-600 font-medium transition">Program Unggulan</a>
                    <a href="../index.php#galeri"
                        class="text-slate-600 hover:text-emerald-600 font-medium transition">Galeri</a>
                    <a href="../index.php#fasilitas" class="text-emerald-600 font-bold transition">Fasilitas</a>
                    <a href="../login.php"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-full font-bold transition shadow-lg shadow-emerald-500/30 flex items-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fas fa-sign-in-alt"></i> Portal Login
                    </a>
                </div>
                <div class="md:hidden flex items-center">
                    <a href="index.php" class="text-slate-600 hover:text-emerald-600 focus:outline-none"><i
                            class="fas fa-arrow-left text-xl mr-4"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-12 bg-emerald-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-extrabold mb-4">Infrastruktur & Fasilitas</h1>
            <p class="text-emerald-100 text-lg max-w-2xl mx-auto">Mendukung terwujudnya generasi Qur'ani yang
                berprestasi melalui penyediaan sarana prasarana yang memadai dan nyaman.</p>
        </div>
    </section>

    <main class="flex-grow py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Array data fasilitas dummy (Nanti fotonya tinggal ganti jalurnya)
                $fasilitas = [
                    ['nama' => 'Asrama Putra', 'img' => '../uploads/fasilitas/Asrama Putra.jpeg'],
                    ['nama' => 'Asrama Putra Tahfidz', 'img' => '../uploads/fasilitas/asrama putra tahfidz.jpeg'],
                    ['nama' => 'Gedung Serbaguna Zawiyah At Tijani', 'img' => '../uploads/fasilitas/gedung serbaguna.jpeg'],
                    ['nama' => 'Gedung SMP 1', 'img' => '../uploads/fasilitas/gedung smp.jpeg'],
                    ['nama' => 'Gedung SMP 2 SMP Bustanul Ulul NU Jatirokeh', 'img' => '../uploads/fasilitas/gedung smp 2.jpeg'],
                    ['nama' => 'Balai Latihan Kerja Komunitas', 'img' => '../uploads/fasilitas/BLKK.jpeg'],
                    ['nama' => 'Asrama Putri 1', 'img' => '../uploads/fasilitas/asrama putri.jpeg'],
                    ['nama' => 'Asrama Putri 2', 'img' => '../uploads/fasilitas/asrama putri 2.jpeg'],
                    ['nama' => 'Asrama Putri 3', 'img' => '../uploads/fasilitas/asrama putri 3.jpeg']
                ];

                foreach ($fasilitas as $f):
                ?>
                    <div
                        class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-slate-100 group">
                        <div class="h-64 overflow-hidden relative">
                            <img src="<?php echo $f['img']; ?>" alt="<?php echo $f['nama']; ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <div class="p-6 border-t-4 border-emerald-500">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center justify-between">
                                <?php echo $f['nama']; ?>
                                <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                            </h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-16">
                <a href="../index.php"
                    class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-700 px-8 py-3.5 rounded-full font-bold transition">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </div>

        </div>
    </main>

    <footer class="bg-slate-900 text-slate-300 pt-12 pb-8 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; <?php echo date('Y'); ?> Yayasan Al Falah Salafiyah Jatirokeh. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>