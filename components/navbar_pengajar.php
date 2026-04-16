<?php
// Ambil data guru dari session (sudah di-set di halaman utama)
$nama_guru_nav = isset($data_guru['nama_pengajar']) ? $data_guru['nama_pengajar'] : $_SESSION['username'];
?>
<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10 shrink-0">
    <div class="flex items-center">
        <button id="menu-toggle" class="text-gray-500 focus:outline-none md:hidden">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <h2 class="text-xl font-semibold text-gray-800 ml-4 hidden md:block">Portal Pengajar</h2>
    </div>

    <div class="flex items-center relative group">
        <button class="flex items-center gap-3 text-gray-600 hover:text-gray-800 focus:outline-none">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($nama_guru_nav); ?></p>
                <p class="text-xs text-emerald-600 font-medium">Pengajar</p>
            </div>
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($nama_guru_nav); ?>&background=10b981&color=fff"
                class="w-9 h-9 rounded-full shadow-sm" alt="Profile">
        </button>
    </div>
</header>