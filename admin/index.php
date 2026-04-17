<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || ($_SESSION['role'] != "admin" && $_SESSION['role'] != "pimpinan")) {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';
$role_user = $_SESSION['role'];
// ========================================================
// LOGIKA WAKTU UNTUK UCAPAN
// ========================================================
date_default_timezone_set('Asia/Jakarta');
$jam = date('H');
$ucapan = "Selamat Pagi";
if ($jam >= 11 && $jam <= 14) $ucapan = "Selamat Siang";
elseif ($jam >= 15 && $jam <= 18) $ucapan = "Selamat Sore";
elseif ($jam >= 19 || $jam <= 3) $ucapan = "Selamat Malam";

// ========================================================
// AMBIL DATA STATISTIK UNTUK WIDGET
// ========================================================
$tot_santri = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM santri WHERE status_aktif='Aktif'"))['total'];
$tot_guru = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengajar"))['total'];
$tot_wali = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM wali_santri"))['total'];
$tot_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pembayaran WHERE status_acc='Pending'"))['total'];

include '../components/header.php';
?>

<?php include '../components/sidebar.php'; ?>

<div class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
    <?php include '../components/navbar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative">

        <div
            class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 rounded-3xl p-8 shadow-lg shadow-blue-500/20 text-white mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="relative z-10">
                <p class="text-blue-100 font-medium mb-1 uppercase tracking-wider text-sm"><?php echo $ucapan; ?>, Admin
                </p>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-2">
                    <?php echo htmlspecialchars(ucwords(str_replace(['_', '-'], ' ', $_SESSION['username']))); ?> 👋
                </h2>
                <p class="text-blue-100 text-lg max-w-2xl">Selamat datang di Pusat Kendali Sistem Akademik &
                    Administrasi. Berikut adalah ringkasan data hari ini, <?php echo date('d F Y'); ?>.</p>
            </div>
            <div class="relative z-10 flex gap-3 w-full md:w-auto">
                <a href="kelola_pembayaran.php"
                    class="flex-1 md:flex-none bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white px-5 py-3 rounded-xl font-bold transition flex items-center justify-center gap-2">
                    <i class="fas fa-search-dollar"></i> Cek Pembayaran
                </a>
            </div>

            <i class="fas fa-university absolute -right-10 -bottom-10 text-[180px] opacity-10 transform -rotate-12"></i>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition group">
                <div
                    class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Santri Aktif</p>
                    <h3 class="text-2xl font-extrabold text-gray-800"><?php echo $tot_santri; ?> <span
                            class="text-xs text-gray-400 font-normal">Anak</span></h3>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition group">
                <div
                    class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Pengajar</p>
                    <h3 class="text-2xl font-extrabold text-gray-800"><?php echo $tot_guru; ?> <span
                            class="text-xs text-gray-400 font-normal">Guru</span></h3>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition group">
                <div
                    class="w-14 h-14 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Wali Santri</p>
                    <h3 class="text-2xl font-extrabold text-gray-800"><?php echo $tot_wali; ?> <span
                            class="text-xs text-gray-400 font-normal">Orang Tua</span></h3>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-amber-200 flex items-center gap-5 hover:shadow-md transition group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
                <div
                    class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Pending ACC</p>
                    <h3 class="text-2xl font-extrabold text-amber-600"><?php echo $tot_pending; ?> <span
                            class="text-xs text-gray-400 font-normal">Tagihan</span></h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 flex items-center gap-2"><i
                                class="fas fa-file-invoice-dollar text-blue-500"></i> Antrean Validasi Pembayaran</h3>
                        <p class="text-xs text-gray-500 mt-1">5 Bukti transfer terbaru yang menunggu di-ACC.</p>
                    </div>
                    <a href="kelola_pembayaran.php" class="text-sm text-blue-600 font-bold hover:underline">Lihat
                        Semua</a>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr
                                class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="p-4 font-semibold">Tgl / Waktu</th>
                                <th class="p-4 font-semibold">Nama Santri</th>
                                <th class="p-4 font-semibold">Nominal Tagihan</th>
                                <th class="p-4 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                            <?php
                            $q_bayar = mysqli_query($koneksi, "
                                SELECT p.tanggal_bayar, t.nominal, s.nama_santri, s.kelas 
                                FROM pembayaran p 
                                JOIN tagihan t ON p.id_tagihan = t.id_tagihan 
                                JOIN santri s ON t.id_santri = s.id_santri 
                                WHERE p.status_acc = 'Pending' 
                                ORDER BY p.tanggal_bayar ASC LIMIT 5
                            ");
                            if (mysqli_num_rows($q_bayar) > 0) {
                                while ($b = mysqli_fetch_array($q_bayar)):
                            ?>
                                    <tr class="hover:bg-blue-50/20 transition-colors">
                                        <td class="p-4 text-gray-500"><i class="far fa-clock mr-1 text-gray-400"></i>
                                            <?php echo date('d/m/Y H:i', strtotime($b['tanggal_bayar'])); ?></td>
                                        <td class="p-4">
                                            <p class="font-bold text-gray-800">
                                                <?php echo htmlspecialchars($b['nama_santri']); ?></p>
                                            <p class="text-xs text-gray-500">Kelas <?php echo $b['kelas']; ?></p>
                                        </td>
                                        <td class="p-4 font-bold text-gray-700">Rp
                                            <?php echo number_format($b['nominal'], 0, ',', '.'); ?></td>
                                        <td class="p-4 text-center">
                                            <span
                                                class="inline-flex px-2 py-1 rounded bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100 animate-pulse">Menunggu</span>
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                            } else {
                                echo '<tr><td colspan="4" class="p-8 text-center text-gray-400 italic">Hore! Tidak ada tagihan yang tertunda. Semua sudah di-ACC.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-8">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2"><i
                                class="fas fa-user-plus text-emerald-500"></i> Santri Terbaru</h3>
                    </div>
                    <div class="p-0">
                        <?php
                        $q_santri_baru = mysqli_query($koneksi, "SELECT nama_santri, kelas, nis FROM santri ORDER BY id_santri DESC LIMIT 5");
                        if (mysqli_num_rows($q_santri_baru) > 0) {
                            echo '<ul class="divide-y divide-gray-50">';
                            while ($sb = mysqli_fetch_array($q_santri_baru)):
                        ?>
                                <li class="p-4 flex items-center gap-3 hover:bg-gray-50 transition">
                                    <div
                                        class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                        <?php echo substr($sb['nama_santri'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">
                                            <?php echo htmlspecialchars($sb['nama_santri']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo $sb['nis']; ?> • Kelas
                                            <?php echo $sb['kelas']; ?></p>
                                    </div>
                                </li>
                        <?php
                            endwhile;
                            echo '</ul>';
                        } else {
                            echo '<p class="p-6 text-center text-gray-400 italic text-sm">Belum ada data santri.</p>';
                        }
                        ?>
                    </div>
                </div>
                <?php if ($role_user === 'admin') {

                ?>
                    <div class="bg-gradient-to-b from-blue-800 to-blue-900 rounded-2xl shadow-sm p-6 text-white">
                        <h3 class="font-bold mb-4 flex items-center gap-2"><i class="fas fa-bolt text-yellow-400"></i> Akses
                            Cepat</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="kelola_santri.php"
                                class="bg-blue-700/50 hover:bg-blue-700 border border-blue-600 p-3 rounded-xl text-center transition group">
                                <i
                                    class="fas fa-user-graduate text-blue-400 mb-2 text-xl group-hover:scale-110 transition"></i>
                                <p class="text-xs font-medium">Santri</p>
                            </a>
                            <a href="kelola_jadwal.php"
                                class="bg-blue-700/50 hover:bg-blue-700 border border-blue-600 p-3 rounded-xl text-center transition group">
                                <i
                                    class="fas fa-calendar-alt text-emerald-400 mb-2 text-xl group-hover:scale-110 transition"></i>
                                <p class="text-xs font-medium">Jadwal</p>
                            </a>
                            <a href="kelola_akademik.php"
                                class="bg-blue-700/50 hover:bg-blue-700 border border-blue-600 p-3 rounded-xl text-center transition group">
                                <i
                                    class="fas fa-book-open text-amber-400 mb-2 text-xl group-hover:scale-110 transition"></i>
                                <p class="text-xs font-medium">Raport</p>
                            </a>
                            <a href="kelola_user.php"
                                class="bg-blue-700/50 hover:bg-blue-700 border border-blue-600 p-3 rounded-xl text-center transition group">
                                <i
                                    class="fas fa-users-cog text-purple-400 mb-2 text-xl group-hover:scale-110 transition"></i>
                                <p class="text-xs font-medium">Akun</p>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
            &copy; <?php echo date('Y'); ?> Sistem Informasi Akademik & Keuangan. Dibuat dengan <i
                class="fas fa-heart text-red-500 mx-1"></i> untuk Pondok.
        </div>

    </main>
</div>

<?php include '../components/footer.php'; ?>