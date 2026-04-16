<?php
// Ambil nama wali dari variabel $data_wali (dideklarasikan di index.php/halaman lainnya)
$nama_wali_nav = isset($data_wali['nama_ayah']) && !empty($data_wali['nama_ayah']) ? $data_wali['nama_ayah'] : $_SESSION['username'];
?>
<header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10 shrink-0">
    <div class="flex items-center">
        <button id="menu-toggle" class="text-gray-500 focus:outline-none md:hidden">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        <h2 class="text-xl font-semibold text-gray-800 ml-4 hidden md:block">Portal Wali Santri</h2>
    </div>

    <div class="flex items-center relative group">
        <button class="flex items-center gap-3 text-gray-600 hover:text-gray-800 focus:outline-none">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($nama_wali_nav); ?></p>
                <p class="text-xs text-indigo-600 font-medium">Orang Tua / Wali</p>
            </div>
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($nama_wali_nav); ?>&background=6366f1&color=fff"
                class="w-9 h-9 rounded-full shadow-sm" alt="Profile">
        </button>
    </div>
</header>