<?php
// Trik kecil PHP untuk mendeteksi kita lagi di halaman mana
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside
    class="bg-slate-900 w-64 min-h-screen flex flex-col shadow-2xl transition-transform transform -translate-x-full md:translate-x-0 md:static fixed z-20"
    id="sidebar">

    <div class="flex items-center justify-center h-20 border-b border-slate-800/60 mt-2">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 text-white">
                <i class="fas fa-graduation-cap text-xl"></i>
            </div>
            <h1 class="text-xl font-extrabold text-white tracking-wider">AKADEMIK</h1>
        </div>
    </div>

    <div class="px-4 py-6 overflow-y-auto flex-grow custom-scrollbar">

        <ul class="space-y-1.5">

            <li class="mb-2">
                <a href="index.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'index.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-chart-pie w-5 text-center text-lg <?= ($current_page == 'index.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
            </li>

            <li class="pt-4 pb-1">
                <div class="flex items-center gap-2 px-3">
                    <!-- <i class="fas fa-database text-slate-500 text-xs"></i> -->
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Master Data</p>
                </div>
            </li>
            <li>
                <a href="kelola_santri.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'kelola_santri.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-user-graduate w-5 text-center text-lg <?= ($current_page == 'kelola_santri.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Data Santri</span>
                </a>
            </li>
            <li>
                <a href="kelola_walisantri.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'kelola_walisantri.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-user-friends w-5 text-center text-lg <?= ($current_page == 'kelola_walisantri.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Data Wali Santri</span>
                </a>
            </li>
            <li>
                <a href="kelola_pengajar.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'kelola_pengajar.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-chalkboard-teacher w-5 text-center text-lg <?= ($current_page == 'kelola_pengajar.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Data Pengajar</span>
                </a>
            </li>
            <li>
                <a href="kelola_jadwal.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'kelola_jadwal.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-calendar-alt w-5 text-center text-lg <?= ($current_page == 'kelola_jadwal.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Pelajaran & Jadwal</span>
                </a>
            </li>
            <li class="pt-4 pb-1">
                <div class="flex items-center gap-2 px-3">
                    <!-- <i class="fas fa-tasks text-slate-500 text-xs"></i> -->
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Operasional</p>
                </div>
            </li>
            <li>
                <a href="kelola_akademik.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'kelola_akademik.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-book-open w-5 text-center text-lg <?= ($current_page == 'kelola_akademik.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Akademik & Raport</span>
                </a>
            </li>
            <li>
                <a href="kelola_pembayaran.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'kelola_pembayaran.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-file-invoice-dollar w-5 text-center text-lg <?= ($current_page == 'kelola_pembayaran.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Pembayaran</span>
                </a>
            </li>

            <li class="pt-4 pb-1">
                <div class="flex items-center gap-2 px-3">
                    <!-- <i class="fas fa-cogs text-slate-500 text-xs"></i> -->
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Sistem</p>
                </div>
            </li>
            <li>
                <a href="kelola_user.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'kelola_user.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-users-cog w-5 text-center text-lg <?= ($current_page == 'kelola_user.php') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Kelola User</span>
                </a>
            </li>

        </ul>
    </div>

    <div class="p-4 border-t border-slate-800/60">
        <a href="../logout.php" onclick="return confirm('Yakin ingin keluar dari sistem?')"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 text-red-400 hover:bg-red-500/10 hover:text-red-300 group">
            <i class="fas fa-sign-out-alt w-5 text-center text-lg group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium text-sm">Keluar Sistem</span>
        </a>
    </div>
</aside>