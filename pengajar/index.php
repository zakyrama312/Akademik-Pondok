<?php
session_start();
// Validasi ketat: Hanya user dengan role 'pengajar' yang boleh masuk!
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "pengajar") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Ambil data profil guru berdasarkan id_user yang sedang login
$id_user_login = $_SESSION['id_user'];
$query_guru = mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_user = '$id_user_login'");
$data_guru = mysqli_fetch_assoc($query_guru);

// Karena guru mungkin belum di-set datanya oleh admin, kita kasih antisipasi error
$nama_guru = $data_guru ? $data_guru['nama_pengajar'] : $_SESSION['username'];
$id_pengajar = $data_guru ? $data_guru['id_pengajar'] : 0;

// ========================================================
// LOGIKA HITUNG DASHBOARD DINAMIS (FIXED ANTI-ERROR 500)
// ========================================================
$total_kelas = 0;
$status_nilai = "Belum Ada Kelas";

if ($id_pengajar > 0) {
    // 1. Hitung jumlah kelas unik yang diampu dari tabel jadwal_pengampu
    $q_kelas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM jadwal_pengampu WHERE id_pengajar = $id_pengajar");
    $r_kelas = mysqli_fetch_assoc($q_kelas);
    $total_kelas = $r_kelas['total'] ?? 0;

    if ($total_kelas > 0) {
        // 2. Cek apakah pengajar ini sudah menginput nilai di tabel nilai_akademik
        // Perbaikan JOIN: nilai_akademik disambungkan ke santri (untuk dapat data kelas santri), baru di-join ke jadwal_pengampu
        $query_cek = "
            SELECT COUNT(*) as jumlah_nilai 
            FROM nilai_akademik n
            JOIN santri s ON n.id_santri = s.id_santri
            JOIN jadwal_pengampu j ON n.id_mapel = j.id_mapel AND s.kelas = j.kelas
            WHERE j.id_pengajar = $id_pengajar
        ";
        $q_cek_nilai = mysqli_query($koneksi, $query_cek);

        if ($q_cek_nilai) {
            $r_cek_nilai = mysqli_fetch_assoc($q_cek_nilai);
            if (($r_cek_nilai['jumlah_nilai'] ?? 0) > 0) {
                $status_nilai = "Sudah Diinput";
            } else {
                $status_nilai = "Menunggu Input";
            }
        } else {
            // Jika query gagal karena hal lain, amankan biar tidak Error 500
            $status_nilai = "Menunggu Input";
        }
    }
}

include '../components/header.php';
include '../components/sidebar_pengajar.php'; // Panggil sidebar khusus pengajar
?>

<div class="flex-1 flex flex-col h-screen overflow-hidden">

    <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
        <div class="flex items-center">
            <button id="menu-toggle" class="text-gray-500 focus:outline-none md:hidden">
                <i class="fas fa-bars fa-lg"></i>
            </button>
            <h2 class="text-xl font-semibold text-gray-800 ml-4 hidden md:block">Portal Pengajar</h2>
        </div>

        <div class="flex items-center relative group">
            <button class="flex items-center gap-3 text-gray-600 hover:text-gray-800 focus:outline-none">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($nama_guru); ?></p>
                    <p class="text-xs text-emerald-600 font-medium">Pengajar</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($nama_guru); ?>&background=10b981&color=fff"
                    class="w-9 h-9 rounded-full shadow-sm" alt="Profile">
            </button>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative bg-gray-50">

        <div
            class="bg-gradient-to-r from-emerald-600 to-teal-500 rounded-2xl p-8 shadow-lg shadow-emerald-500/20 text-white mb-8 relative overflow-hidden">
            <i class="fas fa-book-reader absolute -right-4 -bottom-4 text-[120px] opacity-20"></i>

            <div class="relative z-10">
                <h2 class="text-3xl font-extrabold mb-2">Ahlan wa Sahlan, <?php echo htmlspecialchars($nama_guru); ?>!
                    👋</h2>
                <p class="text-emerald-50 text-lg max-w-2xl">Selamat datang di Portal Pengajar. Di sini Anda dapat
                    memantau jadwal mengajar dan menginput nilai akademik santri dengan mudah dan cepat.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div
                    class="w-14 h-14 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Total Jadwal Diampu</p>
                    <h3 class="text-2xl font-bold text-gray-800">
                        <?php echo $total_kelas > 0 ? $total_kelas . ' Kelas & Mapel' : 'Menunggu Jadwal'; ?>
                    </h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                <div
                    class="w-14 h-14 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl shadow-inner">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Status Nilai Santri</p>
                    <h3
                        class="text-2xl font-bold <?php echo $status_nilai == 'Sudah Diinput' ? 'text-emerald-600' : 'text-amber-500'; ?>">
                        <?php echo $status_nilai; ?>
                    </h3>
                </div>
            </div>

        </div>

    </main>
</div>

<script>
    const btn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    if (btn) {
        btn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
</script>

<?php
echo "</body></html>";
?>