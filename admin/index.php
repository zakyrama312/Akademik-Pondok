<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
include '../koneksi.php';

include '../components/header.php';
include '../components/sidebar.php';
include '../components/navbar.php';
?>

<main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Dashboard Statistik</h3>
        <p class="text-gray-500 text-sm mt-1">Ringkasan sistem akademik hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-semibold mb-1">Total Santri</p>
                <h4 class="text-2xl font-bold text-gray-800">120</h4>
            </div>
            <div class="bg-blue-100 p-3 rounded-full text-blue-500">
                <i class="fas fa-users fa-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-semibold mb-1">Pemasukan Bulan Ini</p>
                <h4 class="text-2xl font-bold text-gray-800">Rp 5.2M</h4>
            </div>
            <div class="bg-green-100 p-3 rounded-full text-green-500">
                <i class="fas fa-wallet fa-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-red-500 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-semibold mb-1">Tunggakan SPP</p>
                <h4 class="text-2xl font-bold text-gray-800">15</h4>
            </div>
            <div class="bg-red-100 p-3 rounded-full text-red-500">
                <i class="fas fa-exclamation-circle fa-lg"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-bold text-gray-800">Pembayaran Menunggu Konfirmasi</h3>
        </div>
        <div class="p-6 text-center text-gray-500">
            Belum ada data pembayaran baru.
        </div>
    </div>

</main>

<?php include '../components/footer.php'; ?>