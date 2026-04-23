<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside
    class="bg-slate-900 w-64 min-h-screen flex flex-col shadow-2xl transition-transform transform -translate-x-full md:translate-x-0 md:static fixed z-20"
    id="sidebar">

    <div
        class="flex items-center justify-between md:justify-center h-20 border-b border-slate-800/60 mt-2 px-4 md:px-0">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 text-white">
                <img src="../uploads/img/Logo_AlFalah.png" class="" alt="Logo">
            </div>
            <p class="text-sm font-extrabold text-white tracking-wider">Al-Falah Salafiyah</p>
        </div>

        <button class="close-sidebar-btn md:hidden text-slate-400 hover:text-red-400 focus:outline-none p-2">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="px-4 py-6 overflow-y-auto flex-grow custom-scrollbar">
        <ul class="space-y-1.5">

            <li class="mb-2">
                <a href="index.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'index.php') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-chart-pie w-5 text-center text-lg <?= ($current_page == 'index.php') ? 'text-white' : 'text-slate-500 group-hover:text-emerald-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
            </li>

            <li class="pt-5 pb-2">
                <div class="flex items-center gap-3 px-4">
                    <div
                        class="w-6 h-6 rounded-lg bg-slate-800/80 flex items-center justify-center border border-slate-700/50 shadow-sm">
                        <i class="fas fa-book text-amber-400 text-[10px]"></i>
                    </div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Akademik</p>
                </div>
            </li>
            <li>
                <a href="input_nilai.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?= ($current_page == 'input_nilai.php') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' ?>">
                    <i
                        class="fas fa-marker w-5 text-center text-lg <?= ($current_page == 'input_nilai.php') ? 'text-white' : 'text-slate-500 group-hover:text-emerald-400' ?> transition-colors"></i>
                    <span class="font-medium text-sm">Input Nilai Santri</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="p-4 border-t border-slate-800/60">
        <a href="../logout.php" onclick="return confirm('Yakin ingin keluar dari portal pengajar?')"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 text-red-400 hover:bg-red-500/10 hover:text-red-300 group">
            <i class="fas fa-sign-out-alt w-5 text-center text-lg group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium text-sm">Keluar Sistem</span>
        </a>
    </div>
</aside>