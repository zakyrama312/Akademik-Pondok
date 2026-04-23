<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Al Falah Salafiyah</title>
    <link rel="icon" type="image/x-icon" href="uploads/favicon/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3 cursor-pointer" onclick="window.scrollTo(0,0)">
                    <img src="uploads/img/Logo_AlFalah.png" alt="Logo Al Falah"
                        class="w-10 h-10 object-contain drop-shadow-md">
                    <span class="font-extrabold text-xl tracking-tight text-slate-800">Al Falah<span
                            class="text-emerald-600"> Salafiyah</span></span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <!-- <a href="#profil" class="text-slate-600 hover:text-emerald-600 font-medium transition">Profil</a> -->
                    <a href="#program" class="text-slate-600 hover:text-emerald-600 font-medium transition">Program
                        Unggulan</a>
                    <a href="#galeri" class="text-slate-600 hover:text-emerald-600 font-medium transition">Galeri</a>
                    <a href="#fasilitas"
                        class="text-slate-600 hover:text-emerald-600 font-medium transition">Fasilitas</a>

                    <a href="login.php"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-full font-bold transition shadow-lg shadow-emerald-500/30 flex items-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fas fa-sign-in-alt"></i> Portal Login
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-600 hover:text-emerald-600 focus:outline-none p-2">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>

            <div id="mobile-menu"
                class="hidden md:hidden absolute top-20 left-0 w-full bg-white border-t border-slate-100 shadow-xl pb-6 pt-2 rounded-b-3xl transform origin-top transition-transform duration-300">
                <div class="flex flex-col px-6 space-y-4">
                    <!-- <a href="#profil"
                        class="mobile-link text-slate-600 font-medium hover:text-emerald-600 py-2 border-b border-slate-50">Profil</a> -->
                    <a href="#program"
                        class="mobile-link text-slate-600 font-medium hover:text-emerald-600 py-2 border-b border-slate-50">Program
                        Unggulan</a>
                    <a href="#galeri"
                        class="mobile-link text-slate-600 font-medium hover:text-emerald-600 py-2 border-b border-slate-50">Galeri</a>
                    <a href="#fasilitas"
                        class="mobile-link text-slate-600 font-medium hover:text-emerald-600 py-2 border-b border-slate-50">Fasilitas</a>

                    <a href="login.php"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 mt-4 shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-sign-in-alt"></i> Portal Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-emerald-700">
        <div class="absolute inset-0 bg-pattern z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-emerald-800/50 to-emerald-900/80 z-0"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-emerald-500/30 border border-emerald-400/50 text-emerald-50 text-sm font-semibold tracking-wider mb-6 backdrop-blur-sm animate-pulse">
                    Penerimaan Santri Baru 2026/2027 Telah Dibuka
                </span>
                <h1
                    class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6 drop-shadow-lg">
                    Membangun Generasi <span class="text-emerald-300">Qur'ani</span> yang Berakhlakul Karimah
                </h1>
                <p class="text-lg md:text-xl text-emerald-50/90 mb-10 leading-relaxed">
                    Yayasan Pondok Pesantren & Pendidikan Al Falah Salafiyah Jatirokeh memadukan kurikulum pendidikan
                    formal dan ilmu agama Islam salaf untuk mencetak cendekiawan muslim yang tangguh.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#program"
                        class="bg-white text-emerald-700 px-8 py-3.5 rounded-full font-bold text-lg hover:bg-gray-50 transition shadow-xl transform hover:-translate-y-1">
                        Jelajahi Program
                    </a>
                    <a href="login.php"
                        class="bg-emerald-600 border-2 border-emerald-400 text-white px-8 py-3.5 rounded-full font-bold text-lg hover:bg-emerald-500 transition shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i class="fas fa-user-graduate"></i> Cek Nilai & Tagihan
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10">
            <svg class="relative block w-full h-[50px] md:h-[100px]" data-name="Layer 1"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,121.32,201.3,113.62C242.15,109.24,282.48,84.32,321.39,56.44Z"
                    class="fill-slate-50"></path>
            </svg>
        </div>
    </section>

    <section class="relative z-20 -mt-16 md:-mt-24 mb-16 max-w-5xl mx-auto px-4 sm:px-6">
        <div
            class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-extrabold text-emerald-600 mb-1">500+</div>
                <p class="text-slate-500 font-medium text-sm uppercase tracking-wide">Santri Aktif</p>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-extrabold text-emerald-600 mb-1">45</div>
                <p class="text-slate-500 font-medium text-sm uppercase tracking-wide">Tenaga Pengajar</p>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-extrabold text-emerald-600 mb-1">12</div>
                <p class="text-slate-500 font-medium text-sm uppercase tracking-wide">Program Ekstra</p>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-extrabold text-emerald-600 mb-1">10<span class="text-xl">Ha</span>
                </div>
                <p class="text-slate-500 font-medium text-sm uppercase tracking-wide">Luas Area</p>
            </div>
        </div>
    </section>

    <section id="program" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-emerald-600 font-bold uppercase tracking-widest text-sm mb-2">Program Pendidikan</h3>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Program Unggulan Pondok</h2>
                <p class="text-slate-600 text-lg">Membekali santri dengan ilmu agama yang mendalam serta keterampilan
                    keahlian untuk menghadapi tantangan masa depan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 group">
                    <div
                        class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 text-3xl mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fas fa-quran"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Tahfidzul Qur'an</h3>
                    <p class="text-slate-600 leading-relaxed">Program hafalan Al-Qur'an bersanad dengan target mutqin 30
                        juz, didampingi oleh musyrif/musyrifah berpengalaman.</p>
                </div>
                <div
                    class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 group">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-3xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Kajian Kitab Kuning</h3>
                    <p class="text-slate-600 leading-relaxed">Pendidikan Madrasah Diniyah yang mengkaji literatur Islam
                        klasik (Turats) seperti Nahwu, Shorof, Fiqih, dan Aqidah.</p>
                </div>
                <div
                    class="bg-white rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 group">
                    <div
                        class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 text-3xl mb-6 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Pendidikan Formal (SMK)</h3>
                    <p class="text-slate-600 leading-relaxed">Terintegrasi dengan SMK Kejuruan (RPL & Multimedia) agar
                        santri siap terjun sebagai tenaga ahli di industri teknologi.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="galeri" class="py-20 bg-white relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-emerald-600 font-bold uppercase tracking-widest text-sm mb-2">Lensa Pondok</h3>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Galeri Aktivitas Santri</h2>
                <p class="text-slate-600 text-lg">Potret keseharian, momen kebersamaan, dan ragam kegiatan positif
                    santri di dalam maupun di luar area pondok pesantren.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    class="relative overflow-hidden rounded-3xl group cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[4/3]">
                    <img src="uploads/img/kegiatan/abb.jpg" alt="Kajian Sore" loading="lazy"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <h4 class="text-lg font-bold text-white mb-1">Pembacaan Maulid & Sholawat</h4>
                        <p class="text-xs text-emerald-200">Memupuk mahabbah kepada Rasulullah melalui kegiatan rutinan
                            pembacaan Maulid Nabi.</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-3xl group cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[4/3]">
                    <img src="uploads/img/kegiatan/iksab.jpg" alt="Kelas SMK" loading="lazy"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <h4 class="text-lg font-bold text-white mb-1">Pengajian Akbar & Haul</h4>
                        <p class="text-xs text-blue-200">Keterlibatan santri dalam peringatan Haul Massal dan
                            silaturahmi warga Jatirokeh.</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-3xl group cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[4/3]">
                    <img src="uploads/img/kegiatan/pn.jpg" alt="Salat Jamaah" loading="lazy"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <h4 class="text-lg font-bold text-white mb-1">Ekstrakurikuler Pagar Nusa</h4>
                        <p class="text-xs text-emerald-200">Membentuk fisik yang tangguh dan karakter ksatria melalui
                            seni bela diri pencak silat.</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-3xl group cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[4/3]">
                    <img src="uploads/img/kegiatan/putri.jpg" alt="Ekstrakurikuler Bola" loading="lazy"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <h4 class="text-lg font-bold text-white mb-1">Kajian & Doa Bersama</h4>
                        <p class="text-xs text-amber-200">Momen khusyuk santriwati dalam kegiatan muhasabah dan
                            pembinaan spiritual.</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-3xl group cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[4/3]">
                    <img src="uploads/img/kegiatan/ttb.jpg" alt="Makan Bersama" loading="lazy"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <h4 class="text-lg font-bold text-white mb-1">Semarak Class Meeting</h4>
                        <p class="text-xs text-emerald-200">Lomba tarik tambang untuk mempererat solidaritas,
                            kekompakan, dan kebersamaan santri.</p>
                    </div>
                </div>

                <div
                    class="relative overflow-hidden rounded-3xl group cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500 aspect-[4/3]">
                    <img src="uploads/img/kegiatan/ziaroh.jpg" alt="Belajar Mandiri" loading="lazy"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-6">
                        <h4 class="text-lg font-bold text-white mb-1">Rihlah & Ziarah Auliya</h4>
                        <p class="text-xs text-purple-200">Kegiatan ziarah tokoh ulama untuk mengenang jasa pendahulu
                            dan memperkuat iman.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="fasilitas" class="py-20 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-emerald-600 font-bold uppercase tracking-widest text-sm mb-2">Infrastruktur</h3>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">Fasilitas Pondok</h2>
                <p class="text-slate-600 text-lg">Lingkungan belajar yang asri, nyaman, dan didukung fasilitas memadai
                    untuk menunjang kegiatan akademik maupun ubudiyah santri.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div
                    class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-slate-100 group">
                    <div class="h-56 overflow-hidden relative">
                        <img src="uploads/fasilitas/gedung serbaguna.jpeg" alt="Gedung Serbaguna" loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                        <h3 class="absolute bottom-4 left-4 text-xl font-bold text-white">Gedung Serbaguna Zawiyah At
                            Tijani</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-600 text-sm leading-relaxed">Gedung serbaguna yang digunakan untuk berbagai
                            kegiatan akademik dan kegiatan keislaman.</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-slate-100 group">
                    <div class="h-56 overflow-hidden relative">
                        <img src="uploads/fasilitas/Asrama Putra.jpeg" alt="Asrama Putra" loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                        <h3 class="absolute bottom-4 left-6 text-xl font-bold text-white">Asrama Putra</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-600 text-sm leading-relaxed">Asrama putra yang dilengkapi fasilitas yang
                            memadai untuk menunjang kegiatan belajar dan beribadah.</p>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-shadow border border-slate-100 group">
                    <div class="h-56 overflow-hidden relative">
                        <img src="uploads/fasilitas/gedung smp.jpeg" alt="Gedung SMP" loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                        <h3 class="absolute bottom-4 left-6 text-xl font-bold text-white">Gedung SMP Bustanul Ulul NU
                            Jatirokeh</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-600 text-sm leading-relaxed">Gedung SMP yang dilengkapi fasilitas yang
                            memadai untuk menunjang kegiatan belajar.</p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="fasilitas/index.php"
                    class="inline-flex items-center gap-2 bg-slate-800 hover:bg-emerald-600 text-white px-8 py-3.5 rounded-full font-bold transition shadow-lg group">
                    Lihat Seluruh Fasilitas <i
                        class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-[3rem] overflow-hidden flex flex-col lg:flex-row relative shadow-2xl">
                <div class="absolute inset-0 bg-pattern opacity-10"></div>

                <div class="p-10 lg:p-16 lg:w-3/5 relative z-10 flex flex-col justify-center">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Sistem Informasi Terpadu</h2>
                    <p class="text-slate-300 text-lg mb-8 leading-relaxed">
                        Kini pantau perkembangan akademik santri, rincian nilai raport, hingga administrasi pembayaran
                        SPP menjadi lebih transparan dan mudah diakses melalui Portal Wali Santri.
                    </p>
                    <ul class="space-y-4 mb-8 text-slate-200">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-400"></i> Pantau
                            Nilai Raport & Keaktifan</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-400"></i> Cek &
                            Upload Bukti Pembayaran SPP</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-emerald-400"></i> Catatan
                            Khusus dari Wali Kelas</li>
                    </ul>
                    <div>
                        <a href="login.php"
                            class="inline-flex bg-emerald-500 hover:bg-emerald-600 text-white px-8 py-4 rounded-xl font-bold transition shadow-lg items-center gap-2 group">
                            Masuk ke Portal <i
                                class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
                <div
                    class="lg:w-2/5 bg-emerald-600 relative overflow-hidden hidden md:flex items-center justify-center p-12">
                    <div class="relative z-10 text-center">
                        <div
                            class="w-48 h-48 mx-auto bg-white/10 rounded-full flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl mb-6">
                            <i class="fas fa-mobile-alt text-7xl text-white"></i>
                        </div>
                        <p class="text-emerald-100 font-medium">Akses mudah dari Smartphone Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-300 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <img src="uploads/img/Logo_AlFalah.png" alt="Logo Al Falah" class="w-8 h-8 object-contain">
                        <span class="font-extrabold text-xl tracking-tight text-white">Al Falah<span
                                class="text-emerald-500"> Salafiyah</span></span>
                    </div>
                    <p class="text-slate-400 leading-relaxed mb-6">Lembaga pendidikan Islam berbasis teknologi yang
                        berkomitmen mencetak generasi unggul, berakhlak mulia, dan siap menghadapi tantangan global.</p>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <!-- <li><a href="#" class="hover:text-emerald-400 transition flex items-center gap-2"><i
                                    class="fas fa-angle-right text-xs"></i> Pendaftaran Santri</a></li> -->
                        <li><a href="#galeri" class="hover:text-emerald-400 transition flex items-center gap-2"><i
                                    class="fas fa-angle-right text-xs"></i> Galeri Kegiatan</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition flex items-center gap-2"><i
                                    class="fas fa-angle-right text-xs"></i> Berita & Artikel</a></li>
                        <li><a href="login.php" class="hover:text-emerald-400 transition flex items-center gap-2"><i
                                    class="fas fa-angle-right text-xs"></i> Portal Sistem</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt mt-1 text-emerald-500"></i>
                            <span>Jl. Raya Brebes - Purwokerto Desa Jatirokeh Kec. Songgom Kab. Brebes 52266</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone-alt text-emerald-500"></i>
                            <span>0857 2898 1547</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-emerald-500"></i>
                            <span>alfalahsalafyonline@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
                <p>&copy; <?php echo date('Y'); ?> Yayasan Al Falah Salafiyah Jatirokeh. All rights reserved.</p>
                <p>Dibuat dengan <i class="fas fa-heart text-red-500 mx-1"></i> untuk Pendidikan.</p>
            </div>
        </div>
    </footer>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-link');
        const icon = btn.querySelector('i');

        // Fungsi buka/tutup menu
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');

            // Ganti ikon dari bars (garis 3) ke times (silang)
            if (menu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });

        // Otomatis tutup menu kalau link diklik (Biar UX-nya bagus)
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
    });
    </script>

</body>

</html>