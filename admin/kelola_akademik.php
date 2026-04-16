<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// ========================================================
// 0. SETTING FILTER TAHUN AJARAN & SEMESTER
// ========================================================
// Default ke tahun ajaran sekarang kalau belum ada filter
$ta_aktif = isset($_GET['ta']) ? $_GET['ta'] : '2025/2026';
$smt_aktif = isset($_GET['smt']) ? $_GET['smt'] : 'Genap';

// ========================================================
// 1. PROSES SIMPAN / UPDATE NILAI SIKAP & KEAKTIFAN
// ========================================================
if (isset($_POST['simpan_nilai'])) {
    $id_santri = (int)$_POST['id_santri'];
    $ta = mysqli_real_escape_string($koneksi, $_POST['tahun_ajaran']);
    $smt = mysqli_real_escape_string($koneksi, $_POST['semester']);
    $sikap = mysqli_real_escape_string($koneksi, $_POST['nilai_sikap']);
    $keaktifan = mysqli_real_escape_string($koneksi, $_POST['nilai_keaktifan']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan_wali_kelas']);

    // Cek apakah data untuk santri di semester ini sudah ada?
    $cek = mysqli_query($koneksi, "SELECT id_sikap FROM nilai_sikap_keaktifan WHERE id_santri=$id_santri AND tahun_ajaran='$ta' AND semester='$smt'");

    if (mysqli_num_rows($cek) > 0) {
        // UPDATE JIKA SUDAH ADA
        $query = "UPDATE nilai_sikap_keaktifan SET nilai_sikap='$sikap', nilai_keaktifan='$keaktifan', catatan_wali_kelas='$catatan' WHERE id_santri=$id_santri AND tahun_ajaran='$ta' AND semester='$smt'";
    } else {
        // INSERT JIKA BELUM ADA
        $query = "INSERT INTO nilai_sikap_keaktifan (id_santri, semester, tahun_ajaran, nilai_sikap, nilai_keaktifan, catatan_wali_kelas) VALUES ($id_santri, '$smt', '$ta', '$sikap', '$keaktifan', '$catatan')";
    }

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan_sukses'] = "Berhasil! Nilai Sikap & Keaktifan telah disimpan.";
    } else {
        $_SESSION['pesan_error'] = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }

    // Redirect kembali dengan filter yang sama
    header("location:kelola_akademik.php?ta=$ta&smt=$smt");
    exit;
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
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.25rem 1rem 0.25rem 0.5rem;
        outline: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        color: white !important;
        border: 1px solid #3b82f6 !important;
        border-radius: 0.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem;
        margin: 0 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #f1f5f9;
    }

    table.dataTable thead th,
    table.dataTable thead td {
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<?php include '../components/sidebar.php'; ?>
<?php include '../components/navbar.php'; ?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative bg-gray-50">

    <?php if (isset($_SESSION['pesan_sukses'])): ?>
        <div id="alert-msg"
            class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm transition-opacity duration-300">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <p class="font-medium text-sm"><?php echo $_SESSION['pesan_sukses']; ?></p>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-emerald-500"><svg class="w-5 h-5"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg></button>
        </div>
        <?php unset($_SESSION['pesan_sukses']); ?>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden mb-6">
        <div
            class="p-5 bg-blue-50/50 border-b border-blue-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-blue-800"><i class="fas fa-book-open mr-2"></i> Pengelolaan Raport
                    Santri</h2>
                <p class="text-sm text-blue-600/80 mt-1">Pilih Tahun Ajaran dan Semester untuk menginput Nilai Sikap &
                    Keaktifan.</p>
            </div>

            <form method="GET" action="kelola_akademik.php" class="flex items-center gap-3 w-full md:w-auto">
                <select name="ta"
                    class="px-4 py-2 rounded-xl border border-blue-200 text-blue-800 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="2024/2025" <?= $ta_aktif == '2024/2025' ? 'selected' : '' ?>>2024/2025</option>
                    <option value="2025/2026" <?= $ta_aktif == '2025/2026' ? 'selected' : '' ?>>2025/2026</option>
                    <option value="2026/2027" <?= $ta_aktif == '2026/2027' ? 'selected' : '' ?>>2026/2027</option>
                </select>
                <select name="smt"
                    class="px-4 py-2 rounded-xl border border-blue-200 text-blue-800 font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="Ganjil" <?= $smt_aktif == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                    <option value="Genap" <?= $smt_aktif == 'Genap' ? 'selected' : '' ?>>Genap</option>
                </select>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-bold transition shadow-md shadow-blue-500/30">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table id="tabel-akademik" class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Profil Santri</th>
                        <th class="p-4 font-semibold text-center">Sikap</th>
                        <th class="p-4 font-semibold text-center">Keaktifan</th>
                        <th class="p-4 font-semibold text-center">Status Raport</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php
                    $no = 1;
                    // Pakai LEFT JOIN biar semua santri aktif muncul, walaupun nilai sikapnya belum diisi
                    $query_santri = mysqli_query($koneksi, "
                        SELECT s.id_santri, s.nis, s.nama_santri, s.kelas, 
                               n.nilai_sikap, n.nilai_keaktifan, n.catatan_wali_kelas 
                        FROM santri s 
                        LEFT JOIN nilai_sikap_keaktifan n ON s.id_santri = n.id_santri 
                             AND n.tahun_ajaran = '$ta_aktif' AND n.semester = '$smt_aktif'
                        WHERE s.status_aktif = 'Aktif'
                        ORDER BY s.kelas ASC, s.nama_santri ASC
                    ");

                    while ($d = mysqli_fetch_array($query_santri)):
                        $status_isi = ($d['nilai_sikap'] != null) ? true : false;
                    ?>
                        <tr class="hover:bg-blue-50/30 transition-colors border-b border-gray-50">
                            <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                            <td class="p-4">
                                <p class="font-bold text-gray-800"><?php echo htmlspecialchars($d['nama_santri']); ?></p>
                                <p class="text-xs text-gray-500">Kelas: <span
                                        class="font-bold text-blue-600"><?php echo $d['kelas']; ?></span> | NIS:
                                    <?php echo htmlspecialchars($d['nis']); ?></p>
                            </td>
                            <td
                                class="p-4 text-center font-bold <?php echo $d['nilai_sikap'] == 'A' ? 'text-emerald-600' : ($d['nilai_sikap'] == 'B' ? 'text-blue-600' : 'text-amber-600'); ?>">
                                <?php echo $status_isi ? $d['nilai_sikap'] : '-'; ?>
                            </td>
                            <td
                                class="p-4 text-center font-bold <?php echo $d['nilai_keaktifan'] == 'A' ? 'text-emerald-600' : ($d['nilai_keaktifan'] == 'B' ? 'text-blue-600' : 'text-amber-600'); ?>">
                                <?php echo $status_isi ? $d['nilai_keaktifan'] : '-'; ?>
                            </td>
                            <td class="p-4 text-center">
                                <?php if ($status_isi): ?>
                                    <span
                                        class="inline-flex px-2 py-1 rounded bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100"><i
                                            class="fas fa-check-circle mr-1"></i> Siap Cetak</span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex px-2 py-1 rounded bg-red-50 text-red-600 text-xs font-bold border border-red-100"><i
                                            class="fas fa-exclamation-circle mr-1"></i> Belum Diisi</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        onclick="bukaModalNilai(<?php echo $d['id_santri']; ?>, '<?php echo addslashes($d['nama_santri']); ?>', '<?php echo $d['nilai_sikap']; ?>', '<?php echo $d['nilai_keaktifan']; ?>', '<?php echo addslashes($d['catatan_wali_kelas']); ?>')"
                                        class="p-2 <?php echo $status_isi ? 'bg-amber-50 text-amber-600 hover:bg-amber-500' : 'bg-blue-50 text-blue-600 hover:bg-blue-600'; ?> hover:text-white rounded-lg transition"
                                        title="<?php echo $status_isi ? 'Edit Nilai' : 'Input Nilai Baru'; ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>

                                    <?php if ($status_isi): ?>
                                        <a href="cetak_raport.php?id=<?php echo $d['id_santri']; ?>&ta=<?php echo urlencode($ta_aktif); ?>&smt=<?php echo $smt_aktif; ?>"
                                            target="_blank"
                                            class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition"
                                            title="Cetak Raport">
                                            <i class="fas fa-print text-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="p-2 bg-gray-100 text-gray-300 rounded-lg cursor-not-allowed"
                                            title="Isi nilai sikap dahulu untuk mencetak raport"><i
                                                class="fas fa-print text-lg"></i></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-nilai"
        class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300"
            id="modal-nilai-content">
            <div
                class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-blue-50/50 rounded-t-2xl">
                <h3 class="text-xl font-bold text-blue-800"><i class="fas fa-star text-amber-400 mr-2"></i> Input Nilai
                    Akhir</h3>
                <button type="button" onclick="tutupModal('modal-nilai')"
                    class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                        class="fas fa-times text-xl"></i></button>
            </div>

            <form action="kelola_akademik.php?ta=<?php echo urlencode($ta_aktif); ?>&smt=<?php echo $smt_aktif; ?>"
                method="POST" class="p-6">
                <input type="hidden" name="id_santri" id="input_id_santri">
                <input type="hidden" name="tahun_ajaran" value="<?php echo $ta_aktif; ?>">
                <input type="hidden" name="semester" value="<?php echo $smt_aktif; ?>">

                <div class="mb-5 text-center">
                    <p class="text-sm text-gray-500">Santri:</p>
                    <h4 id="teks_nama_santri" class="font-bold text-lg text-gray-800">Nama Santri</h4>
                    <p class="text-xs bg-gray-100 text-gray-600 inline-block px-2 py-1 rounded mt-1">
                        <?php echo $smt_aktif; ?> - <?php echo $ta_aktif; ?></p>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nilai Sikap</label>
                            <select name="nilai_sikap" id="input_sikap" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white font-bold text-gray-800">
                                <option value="A">A (Sangat Baik)</option>
                                <option value="B">B (Baik)</option>
                                <option value="C">C (Cukup)</option>
                                <option value="D">D (Kurang)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nilai Keaktifan</label>
                            <select name="nilai_keaktifan" id="input_keaktifan" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white font-bold text-gray-800">
                                <option value="A">A (Sangat Aktif)</option>
                                <option value="B">B (Aktif)</option>
                                <option value="C">C (Cukup)</option>
                                <option value="D">D (Pasif)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Wali Kelas</label>
                        <textarea name="catatan_wali_kelas" id="input_catatan" rows="3" required
                            placeholder="Contoh: Terus pertahankan semangat belajarmu..."
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white"></textarea>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" name="simpan_nilai"
                        class="w-full px-4 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                        <i class="fas fa-save mr-2"></i> Simpan Data Raport
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-akademik').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [{
                orderable: false,
                targets: 5
            }], // Matiin sorting di kolom aksi
            pageLength: 25 // Biar langsung nampil satu kelas full
        });
    });

    function bukaModal(idModal) {
        const modal = document.getElementById(idModal);
        const content = document.getElementById(idModal + '-content');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
    }

    function tutupModal(idModal) {
        const modal = document.getElementById(idModal);
        const content = document.getElementById(idModal + '-content');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('opacity-0', 'pointer-events-none'), 200);
    }

    // Fungsi lempar data ke Modal Form Nilai (Bisa untuk Insert / Update otomatis)
    function bukaModalNilai(id_santri, nama_santri, sikap, keaktifan, catatan) {
        document.getElementById('input_id_santri').value = id_santri;
        document.getElementById('teks_nama_santri').innerText = nama_santri;

        // Set dropdown dan textarea sesuai data di database (Kalau kosong, set default 'B')
        document.getElementById('input_sikap').value = (sikap && sikap != 'null') ? sikap : 'B';
        document.getElementById('input_keaktifan').value = (keaktifan && keaktifan != 'null') ? keaktifan : 'B';
        document.getElementById('input_catatan').value = (catatan && catatan != 'null') ? catatan : '';

        bukaModal('modal-nilai');
    }

    // Auto-hide alert
    setTimeout(() => {
        const alertEl = document.getElementById('alert-msg');
        if (alertEl) {
            alertEl.classList.add('opacity-0');
            setTimeout(() => alertEl.style.display = 'none', 300);
        }
    }, 4000);
</script>

<?php include '../components/footer.php'; ?>