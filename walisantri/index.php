<?php
session_start();
// Validasi ketat: Hanya user dengan role 'walisantri' yang boleh masuk!
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "walisantri") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Ambil data profil Wali Santri berdasarkan id_user yang sedang login
$id_user_login = $_SESSION['id_user'];
$query_wali = mysqli_query($koneksi, "SELECT * FROM wali_santri WHERE id_user = '$id_user_login'");
$data_wali = mysqli_fetch_assoc($query_wali);

// Antisipasi jika data belum lengkap
if (!$data_wali) {
    die("<div style='padding:20px; font-family:sans-serif;'>Maaf, profil data Anda belum lengkap. Silakan hubungi Admin Sekolah. <a href='../logout.php'>Kembali Login</a></div>");
}

$id_wali = $data_wali['id_wali'];

include '../components/header.php';
include '../components/sidebar_walisantri.php';
?>

<div class="flex-1 flex flex-col h-screen overflow-hidden">

    <?php include '../components/navbar_walisantri.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative bg-gray-50">

        <div
            class="bg-gradient-to-r from-indigo-600 to-blue-500 rounded-2xl p-8 shadow-lg shadow-indigo-500/20 text-white mb-8 relative overflow-hidden">
            <i class="fas fa-home absolute -right-4 -bottom-4 text-[120px] opacity-20"></i>

            <div class="relative z-10">
                <p class="text-indigo-100 font-semibold mb-1 uppercase tracking-wider text-sm">Selamat Datang, Bapak/Ibu
                </p>
                <h2 class="text-3xl font-extrabold mb-2">
                    <?php echo htmlspecialchars($data_wali['nama_ayah'] ?: $data_wali['nama_ibu']); ?></h2>
                <p class="text-indigo-50 text-lg max-w-2xl">Pantau perkembangan akademik, nilai, dan informasi tagihan
                    putra/putri Anda dengan mudah melalui portal ini.</p>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-800 border-b border-gray-200 pb-2"><i
                    class="fas fa-users text-indigo-500 mr-2"></i> Data Putra/Putri Anda</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            // Query untuk mencari santri yang terhubung dengan id_wali ini
            $q_anak = mysqli_query($koneksi, "SELECT * FROM santri WHERE id_wali = $id_wali");

            if (mysqli_num_rows($q_anak) > 0):
                while ($anak = mysqli_fetch_assoc($q_anak)):
            ?>
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
                        <div
                            class="absolute top-0 left-0 w-full h-1 <?php echo $anak['status_aktif'] == 'Aktif' ? 'bg-emerald-500' : 'bg-red-500'; ?>">
                        </div>

                        <div class="flex items-start gap-4 mb-4 mt-2">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($anak['nama_santri']); ?>&background=f1f5f9&color=475569"
                                class="w-14 h-14 rounded-xl shadow-sm" alt="Foto">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 leading-tight">
                                    <?php echo htmlspecialchars($anak['nama_santri']); ?></h4>
                                <p class="text-sm text-gray-500 font-mono mt-1">NIS:
                                    <?php echo htmlspecialchars($anak['nis']); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Kelas</p>
                                <p class="font-bold text-indigo-600"><?php echo htmlspecialchars($anak['kelas']); ?></p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Status</p>
                                <?php if ($anak['status_aktif'] == 'Aktif'): ?>
                                    <p class="font-bold text-emerald-600"><i class="fas fa-check-circle text-xs"></i> Aktif</p>
                                <?php else: ?>
                                    <p class="font-bold text-red-600"><i class="fas fa-times-circle text-xs"></i> Lulus/Pindah</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-2">
                            <a href="akademik.php?id_anak=<?php echo $anak['id_santri']; ?>"
                                class="flex-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-center py-2 rounded-lg text-sm font-bold transition">Lihat
                                Nilai</a>
                            <a href="pembayaran.php?id_anak=<?php echo $anak['id_santri']; ?>"
                                class="flex-1 bg-amber-50 hover:bg-amber-100 text-amber-700 text-center py-2 rounded-lg text-sm font-bold transition">Cek
                                Tagihan</a>
                        </div>
                    </div>
                <?php
                endwhile;
            else:
                ?>
                <div
                    class="col-span-full flex flex-col items-center justify-center p-10 bg-white rounded-2xl border border-dashed border-gray-300 text-center">
                    <div
                        class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center text-3xl mb-4">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Data Santri Belum Tersedia</h3>
                    <p class="text-sm text-gray-500 mt-1">Belum ada data santri yang dihubungkan dengan akun Anda.
                        <br>Silakan hubungi Tata Usaha Sekolah.
                    </p>
                </div>
            <?php endif; ?>
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

<?php include '../components/footer.php'; ?>