<?php
// Trik menyulap username jadi Nama rapi (contoh: admin_pusat -> Admin Pusat)
$nama_lengkap = ucwords(str_replace(['_', '-'], ' ', $_SESSION['username']));
$role_teks = ($_SESSION['role'] == 'pimpinan') ? 'Pimpinan Yayasan' : 'Administrator';
?>
<header
    class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10 shrink-0 transition-all duration-300">
    <div class="flex items-center">
        <button id="menu-toggle"
            class="text-slate-500 hover:text-blue-600 focus:outline-none md:hidden transition-colors">
            <i class="fas fa-bars fa-lg"></i>
        </button>

        <div class="hidden md:flex items-center ml-4 text-sm font-medium text-slate-500">
            <i class="fas fa-home mr-2 text-slate-400"></i>
            <span>Sistem Akademik</span>
            <i class="fas fa-chevron-right text-[10px] mx-2 text-slate-300"></i>
            <span
                class="text-blue-600 font-bold capitalize"><?= str_replace('.php', '', basename($_SERVER['PHP_SELF'])) == 'index' ? 'Dashboard' : str_replace('_', ' ', str_replace('.php', '', basename($_SERVER['PHP_SELF']))); ?></span>
        </div>
    </div>

    <div class="flex items-center relative group cursor-pointer">
        <div class="flex items-center gap-3 text-slate-600 hover:text-slate-800 transition-colors">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-extrabold text-slate-800 tracking-wide">
                    <?php echo htmlspecialchars($nama_lengkap); ?></p>
                <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider"><?php echo $role_teks; ?></p>
            </div>
            <div class="relative">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($nama_lengkap); ?>&background=2563eb&color=fff&bold=true"
                    class="w-10 h-10 rounded-xl shadow-md border-2 border-white group-hover:scale-105 transition-transform"
                    alt="Profile">
                <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full">
                </div>
            </div>
        </div>
    </div>
</header>