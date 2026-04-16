<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "walisantri") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Ambil ID Wali dari User yang login
$id_user_login = $_SESSION['id_user'];
$query_wali = mysqli_query($koneksi, "SELECT * FROM wali_santri WHERE id_user = '$id_user_login'");
$data_wali = mysqli_fetch_assoc($query_wali);
$id_wali = $data_wali['id_wali'];

// ========================================================
// PROSES UPLOAD BUKTI TRANSFER
// ========================================================
if (isset($_POST['upload_bukti'])) {
    $id_tagihan = (int)$_POST['id_tagihan'];

    // Pastikan folder uploads ada
    $upload_dir = __DIR__ . '/../uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['bukti_transfer']['tmp_name'];
        $file_name = $_FILES['bukti_transfer']['name'];
        $file_size = $_FILES['bukti_transfer']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($file_ext, $allowed_ext)) {
            if ($file_size <= 2097152) { // Maksimal 2MB
                $nama_file_baru = 'tf_' . time() . '_' . uniqid() . '.' . $file_ext;
                $path_tujuan = $upload_dir . $nama_file_baru;

                if (move_uploaded_file($file_tmp, $path_tujuan)) {
                    // 1. Masukkan data ke tabel pembayaran
                    mysqli_query($koneksi, "INSERT INTO pembayaran (id_tagihan, bukti_transfer, status_acc) VALUES ($id_tagihan, '$nama_file_baru', 'Pending')");

                    // 2. Ubah status tagihan jadi Menunggu Konfirmasi
                    mysqli_query($koneksi, "UPDATE tagihan SET status_tagihan='Menunggu Konfirmasi' WHERE id_tagihan=$id_tagihan");

                    $_SESSION['pesan_sukses'] = "Bukti transfer berhasil diunggah! Mohon tunggu konfirmasi dari Admin.";
                } else {
                    $_SESSION['pesan_error'] = "Gagal memindahkan file ke folder uploads.";
                }
            } else {
                $_SESSION['pesan_error'] = "Ukuran file terlalu besar! Maksimal 2MB.";
            }
        } else {
            $_SESSION['pesan_error'] = "Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau WEBP.";
        }
    } else {
        $_SESSION['pesan_error'] = "Pilih file gambar bukti transfer terlebih dahulu!";
    }

    header("location:pembayaran.php");
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

        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div id="alert-msg"
                class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm transition-opacity duration-300">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-lg"></i>
                    <p class="font-medium text-sm"><?php echo $_SESSION['pesan_sukses']; ?></p>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-emerald-500"><i
                        class="fas fa-times"></i></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['pesan_error'])): ?>
            <div id="alert-msg"
                class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm transition-opacity duration-300">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    <p class="font-medium text-sm"><?php echo $_SESSION['pesan_error']; ?></p>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-red-500"><i
                        class="fas fa-times"></i></button>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 overflow-hidden mb-8">
            <div
                class="p-6 border-b border-indigo-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-indigo-50/50">
                <div>
                    <h2 class="text-lg font-bold text-indigo-800"><i class="fas fa-file-invoice-dollar mr-2"></i>
                        Tagihan Administrasi</h2>
                    <p class="text-sm text-indigo-600/80">Daftar tagihan SPP dan administrasi lainnya untuk putra/putri
                        Anda.</p>
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table id="tabel-tagihan" class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold w-16 text-center">No</th>
                            <th class="p-4 font-semibold">Nama Anak (Santri)</th>
                            <th class="p-4 font-semibold">Rincian Tagihan</th>
                            <th class="p-4 font-semibold text-right">Nominal</th>
                            <th class="p-4 font-semibold text-center">Status</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <?php
                        $no = 1;
                        // Query mengambil tagihan HANYA untuk anak dari wali yang sedang login
                        $q_tagihan = mysqli_query($koneksi, "
                            SELECT t.*, s.nama_santri, s.kelas 
                            FROM tagihan t 
                            JOIN santri s ON t.id_santri = s.id_santri 
                            WHERE s.id_wali = $id_wali 
                            ORDER BY FIELD(t.status_tagihan, 'Belum Lunas', 'Menunggu Konfirmasi', 'Lunas'), t.id_tagihan DESC
                        ");

                        if (mysqli_num_rows($q_tagihan) > 0) {
                            while ($t = mysqli_fetch_array($q_tagihan)):
                                // Cek alasan ditolak jika statusnya Belum Lunas (bisa jadi admin habis nolak bukti tf)
                                $catatan_admin = '';
                                if ($t['status_tagihan'] == 'Belum Lunas') {
                                    $id_t = $t['id_tagihan'];
                                    $q_tolak = mysqli_query($koneksi, "SELECT catatan_admin FROM pembayaran WHERE id_tagihan=$id_t AND status_acc='Ditolak' ORDER BY id_pembayaran DESC LIMIT 1");
                                    if ($tolak = mysqli_fetch_assoc($q_tolak)) {
                                        $catatan_admin = $tolak['catatan_admin'];
                                    }
                                }
                        ?>
                                <tr class="hover:bg-indigo-50/20 transition-colors border-b border-gray-50">
                                    <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                                    <td class="p-4">
                                        <p class="font-bold text-gray-800"><?php echo htmlspecialchars($t['nama_santri']); ?>
                                        </p>
                                        <p class="text-xs text-indigo-500 font-medium">Kelas
                                            <?php echo htmlspecialchars($t['kelas']); ?></p>
                                    </td>
                                    <td class="p-4 text-gray-600">
                                        <p class="font-semibold"><?php echo $t['jenis_tagihan']; ?></p>
                                        <p class="text-xs text-gray-400">
                                            <?php echo $t['bulan'] ? $t['bulan'] . ' ' : ''; ?><?php echo $t['tahun']; ?></p>
                                    </td>
                                    <td class="p-4 text-right font-bold text-gray-800">
                                        Rp <?php echo number_format($t['nominal'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if ($t['status_tagihan'] == 'Lunas'): ?>
                                            <span
                                                class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-200"><i
                                                    class="fas fa-check mr-1"></i> Lunas</span>
                                        <?php elseif ($t['status_tagihan'] == 'Menunggu Konfirmasi'): ?>
                                            <span
                                                class="inline-flex px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold border border-amber-200"><i
                                                    class="fas fa-clock mr-1"></i> Menunggu ACC</span>
                                        <?php else: ?>
                                            <span
                                                class="inline-flex px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold border border-red-200"><i
                                                    class="fas fa-exclamation-circle mr-1"></i> Belum Bayar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <?php if ($t['status_tagihan'] == 'Lunas'): ?>
                                            <span class="text-emerald-500 text-sm font-bold"><i class="fas fa-shield-check"></i>
                                                Selesai</span>

                                        <?php elseif ($t['status_tagihan'] == 'Menunggu Konfirmasi'): ?>
                                            <button disabled
                                                class="bg-gray-100 text-gray-400 px-4 py-2 rounded-lg text-xs font-bold cursor-not-allowed">
                                                Sedang Dicek
                                            </button>

                                        <?php else: ?>
                                            <div class="flex flex-col items-center gap-1">
                                                <button
                                                    onclick="bukaModalBayar(<?php echo $t['id_tagihan']; ?>, '<?php echo addslashes($t['nama_santri']); ?>', '<?php echo $t['jenis_tagihan'] . ' ' . ($t['bulan'] ? $t['bulan'] . ' ' : '') . $t['tahun']; ?>', '<?php echo number_format($t['nominal'], 0, ',', '.'); ?>')"
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-md shadow-indigo-500/30 transition">
                                                    <i class="fas fa-upload mr-1"></i> Bayar
                                                </button>
                                                <?php if ($catatan_admin): ?>
                                                    <p class="text-[10px] text-red-500 max-w-[120px] leading-tight mt-1"
                                                        title="<?php echo htmlspecialchars($catatan_admin); ?>">*Ditolak:
                                                        <?php echo htmlspecialchars($catatan_admin); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php
                            endwhile;
                        }
                        // else {
                        //     echo '<tr><td colspan="6" class="p-8 text-center text-gray-400 italic">Alhamdulillah, tidak ada tagihan saat ini.</td></tr>';
                        // }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<div id="modal-bayar"
    class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300"
        id="modal-bayar-content">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50/50 rounded-t-2xl">
            <h3 class="text-xl font-bold text-indigo-800">Upload Bukti Pembayaran</h3>
            <button type="button" onclick="tutupModal('modal-bayar')"
                class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form action="pembayaran.php" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="id_tagihan" id="input_bayar_id_tagihan">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-5 text-center">
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">Tagihan Untuk</p>
                <h4 id="teks_nama_santri" class="font-bold text-lg text-gray-800">Nama Santri</h4>
                <p id="teks_jenis_tagihan" class="text-sm text-indigo-600 font-medium mt-1">Jenis Tagihan</p>
                <div class="mt-3 inline-block bg-white border border-gray-200 rounded-lg px-4 py-2">
                    <p class="text-xs text-gray-500">Total Dibayar:</p>
                    <p class="text-xl font-extrabold text-gray-800">Rp <span id="teks_nominal">0</span></p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-lg">
                    <p class="text-xs text-amber-700 font-medium">Silakan transfer ke rekening resmi sekolah:<br>
                        <strong>BSI: 1234567890 (a.n SMK Negeri 1 Slawi)</strong>
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih File Bukti Transfer (Struk /
                        Screenshot)</label>
                    <input type="file" name="bukti_transfer" accept=".jpg,.jpeg,.png,.webp" required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-gray-200 rounded-xl bg-gray-50 focus:outline-none">
                    <p class="text-[10px] text-gray-400 mt-1">*Maksimal ukuran file 2MB. Format: JPG, PNG.</p>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="tutupModal('modal-bayar')"
                    class="w-1/3 px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                <button type="submit" name="upload_bukti"
                    class="w-2/3 px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> Kirim Bukti
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-tagihan').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [{
                orderable: false,
                targets: 5
            }], // Nonaktifkan sorting di kolom aksi
            pageLength: 10
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

    // Lempar data tagihan ke Modal Bayar
    function bukaModalBayar(id_tagihan, nama, jenis, nominal) {
        document.getElementById('input_bayar_id_tagihan').value = id_tagihan;
        document.getElementById('teks_nama_santri').innerText = nama;
        document.getElementById('teks_jenis_tagihan').innerText = jenis;
        document.getElementById('teks_nominal').innerText = nominal;
        bukaModal('modal-bayar');
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