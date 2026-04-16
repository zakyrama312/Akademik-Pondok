<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "walisantri") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Ambil ID Wali
$id_user_login = $_SESSION['id_user'];
$query_wali = mysqli_query($koneksi, "SELECT id_wali FROM wali_santri WHERE id_user = '$id_user_login'");
$data_wali = mysqli_fetch_assoc($query_wali);
$id_wali = $data_wali['id_wali'];

// ========================================================
// AMBIL DATA ANAK (SANTRI) MILIK WALI INI
// ========================================================
$q_anak_list = mysqli_query($koneksi, "SELECT id_santri, nama_santri, kelas FROM santri WHERE id_wali = $id_wali");
$punya_anak = mysqli_num_rows($q_anak_list) > 0;

// ========================================================
// SETTING FILTER (GET)
// ========================================================
// Default: Ambil anak pertama jika tidak ada id_anak di URL
$id_anak_aktif = isset($_GET['id_anak']) ? (int)$_GET['id_anak'] : 0;
if ($id_anak_aktif == 0 && $punya_anak) {
    $anak_pertama = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_santri FROM santri WHERE id_wali = $id_wali LIMIT 1"));
    $id_anak_aktif = $anak_pertama['id_santri'];
}

$ta_aktif = isset($_GET['ta']) ? $_GET['ta'] : '2025/2026';
$smt_aktif = isset($_GET['smt']) ? $_GET['smt'] : 'Genap';

// Ambil detail profil anak yang sedang dipilih
$profil_anak = null;
if ($id_anak_aktif > 0) {
    $q_profil = mysqli_query($koneksi, "SELECT * FROM santri WHERE id_santri = $id_anak_aktif AND id_wali = $id_wali");
    $profil_anak = mysqli_fetch_assoc($q_profil);
}

include '../components/header.php';
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper {
        padding: 1.5rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.35rem 0.75rem;
        margin-left: 0.5rem;
        outline: none;
        transition: all 0.2s;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.25rem 1rem 0.25rem 0.5rem;
        outline: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #6366f1 !important;
        color: white !important;
        border: 1px solid #6366f1 !important;
        border-radius: 0.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem;
        margin: 0 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e0e7ff !important;
        color: #4f46e5 !important;
        border: 1px solid #c7d2fe !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #f1f5f9;
    }

    table.dataTable thead th,
    table.dataTable thead td {
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<?php include '../components/sidebar_walisantri.php'; ?>

<div class="flex-1 flex flex-col h-screen overflow-hidden">

    <?php include '../components/navbar_walisantri.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative bg-gray-50">

        <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden mb-6">
            <div
                class="p-5 bg-indigo-50/50 border-b border-indigo-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-lg font-bold text-indigo-800"><i class="fas fa-search mr-2"></i> Filter Raport Anak
                    </h2>
                    <p class="text-sm text-indigo-600/80 mt-1">Pilih nama anak, tahun ajaran, dan semester.</p>
                </div>

                <?php if ($punya_anak): ?>
                    <form method="GET" action="akademik.php" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <select name="id_anak"
                            class="flex-1 px-4 py-2 rounded-xl border border-indigo-200 text-indigo-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <?php
                            mysqli_data_seek($q_anak_list, 0); // Reset pointer
                            while ($a = mysqli_fetch_assoc($q_anak_list)) {
                                $sel = ($id_anak_aktif == $a['id_santri']) ? 'selected' : '';
                                echo "<option value='" . $a['id_santri'] . "' $sel>" . $a['nama_santri'] . " (" . $a['kelas'] . ")</option>";
                            }
                            ?>
                        </select>
                        <select name="ta"
                            class="px-4 py-2 rounded-xl border border-indigo-200 text-indigo-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white w-32">
                            <option value="2024/2025" <?= $ta_aktif == '2024/2025' ? 'selected' : '' ?>>2024/2025</option>
                            <option value="2025/2026" <?= $ta_aktif == '2025/2026' ? 'selected' : '' ?>>2025/2026</option>
                        </select>
                        <select name="smt"
                            class="px-4 py-2 rounded-xl border border-indigo-200 text-indigo-800 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white w-32">
                            <option value="Ganjil" <?= $smt_aktif == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                            <option value="Genap" <?= $smt_aktif == 'Genap' ? 'selected' : '' ?>>Genap</option>
                        </select>
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-bold transition shadow-md shadow-indigo-500/30">
                            Lihat Nilai
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!$punya_anak): ?>
            <div
                class="flex flex-col items-center justify-center p-10 bg-white rounded-2xl border border-dashed border-gray-300 text-center">
                <div
                    class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center text-3xl mb-4">
                    <i class="fas fa-child"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Data Santri Belum Tersedia</h3>
                <p class="text-sm text-gray-500 mt-1">Belum ada data santri yang dihubungkan dengan akun Anda.</p>
            </div>
        <?php elseif ($profil_anak): ?>

            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 flex items-center gap-5 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-50 rounded-bl-full -z-0 opacity-50"></div>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($profil_anak['nama_santri']); ?>&background=e0e7ff&color=4f46e5"
                    class="w-20 h-20 rounded-2xl shadow-md z-10" alt="Foto">
                <div class="z-10">
                    <h3 class="text-2xl font-extrabold text-gray-800 leading-tight">
                        <?php echo htmlspecialchars($profil_anak['nama_santri']); ?></h3>
                    <p class="text-gray-500 mt-1">NIS: <span
                            class="font-bold text-gray-700"><?php echo htmlspecialchars($profil_anak['nis']); ?></span> |
                        Kelas: <span
                            class="font-bold text-indigo-600"><?php echo htmlspecialchars($profil_anak['kelas']); ?></span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800"><i class="fas fa-list-ol text-indigo-500 mr-2"></i> Rincian
                            Nilai Akademik</h3>
                    </div>
                    <div class="overflow-x-auto w-full">
                        <table id="tabel-nilai" class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="p-4 font-semibold w-16 text-center">No</th>
                                    <th class="p-4 font-semibold">Mata Pelajaran</th>
                                    <th class="p-4 font-semibold text-center w-32">Nilai Angka</th>
                                    <th class="p-4 font-semibold text-center w-32">Predikat</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                <?php
                                $no = 1;
                                $q_nilai = mysqli_query($koneksi, "
                                    SELECT n.nilai_angka, m.nama_mapel 
                                    FROM nilai_akademik n 
                                    JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
                                    WHERE n.id_santri = $id_anak_aktif AND n.semester = '$smt_aktif' AND n.tahun_ajaran = '$ta_aktif'
                                ");

                                while ($n = mysqli_fetch_array($q_nilai)):
                                    $angka = $n['nilai_angka'];
                                    // Logika Predikat Sederhana
                                    if ($angka >= 90) {
                                        $predikat = 'A';
                                        $warna = 'emerald';
                                    } elseif ($angka >= 80) {
                                        $predikat = 'B';
                                        $warna = 'blue';
                                    } elseif ($angka >= 70) {
                                        $predikat = 'C';
                                        $warna = 'amber';
                                    } else {
                                        $predikat = 'D';
                                        $warna = 'red';
                                    }
                                ?>
                                    <tr class="hover:bg-indigo-50/20 transition-colors border-b border-gray-50">
                                        <td class="p-4 text-center text-gray-500 font-medium"><?php echo $no++; ?></td>
                                        <td class="p-4 font-bold text-gray-800">
                                            <?php echo htmlspecialchars($n['nama_mapel']); ?></td>
                                        <td class="p-4 text-center font-extrabold text-lg text-gray-700"><?php echo $angka; ?>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span
                                                class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-<?php echo $warna; ?>-100 text-<?php echo $warna; ?>-700 font-bold"><?php echo $predikat; ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <?php
                    $q_sikap = mysqli_query($koneksi, "SELECT * FROM nilai_sikap_keaktifan WHERE id_santri = $id_anak_aktif AND semester = '$smt_aktif' AND tahun_ajaran = '$ta_aktif'");
                    $sikap = mysqli_fetch_assoc($q_sikap);
                    ?>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2"><i
                                class="fas fa-star text-amber-500 mr-2"></i> Sikap & Keaktifan</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="text-gray-600 font-medium">Nilai Sikap / Akhlak</span>
                                <?php if ($sikap): ?>
                                    <span
                                        class="font-extrabold text-lg text-indigo-600"><?php echo $sikap['nilai_sikap']; ?></span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                <?php endif; ?>
                            </div>

                            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="text-gray-600 font-medium">Nilai Keaktifan</span>
                                <?php if ($sikap): ?>
                                    <span
                                        class="font-extrabold text-lg text-indigo-600"><?php echo $sikap['nilai_keaktifan']; ?></span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl shadow-sm border border-indigo-100 p-6">
                        <h3 class="font-bold text-indigo-800 mb-3"><i class="fas fa-comment-dots text-indigo-500 mr-2"></i>
                            Catatan Wali Kelas</h3>
                        <p class="text-sm text-indigo-900 leading-relaxed italic">
                            <?php echo ($sikap && !empty($sikap['catatan_wali_kelas'])) ? '"' . htmlspecialchars($sikap['catatan_wali_kelas']) . '"' : 'Belum ada catatan dari wali kelas untuk semester ini.'; ?>
                        </p>
                    </div>

                </div>
            </div>

        <?php endif; ?>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-nilai').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                emptyTable: "Belum ada nilai yang diinput oleh pengajar pada semester ini." // <--- Solusi Anti Error TN/18!
            },
            paging: false, // Matikan paging karena nilai raport biasanya tidak terlalu panjang
            searching: false, // Matikan search box agar lebih clean
            info: false, // Matikan tulisan "Showing 1 to X of X entries"
            order: [
                [1, 'asc']
            ] // Urutkan berdasarkan nama mapel abjad A-Z
        });
    });

    const btn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    if (btn) {
        btn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
</script>

<?php include '../components/footer.php'; ?>