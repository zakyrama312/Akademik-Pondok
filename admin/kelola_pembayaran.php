<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Pastikan folder uploads ada (untuk nyimpen bukti transfer dari wali santri nanti)
$upload_dir = __DIR__ . '/../uploads/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

// ========================================================
// 1. PROSES GENERATE TAGIHAN BARU (Bisa Massal)
// ========================================================
if (isset($_POST['generate_tagihan'])) {
    $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis_tagihan']);
    $bulan = mysqli_real_escape_string($koneksi, $_POST['bulan']);
    $tahun = mysqli_real_escape_string($koneksi, $_POST['tahun']);
    $nominal = (int)$_POST['nominal'];
    $target = $_POST['target_santri']; // 'semua' atau ID Santri

    $berhasil = 0;
    if ($target == 'semua') {
        // Ambil semua santri aktif
        $q_santri = mysqli_query($koneksi, "SELECT id_santri FROM santri WHERE status_aktif = 'Aktif'");
        while ($s = mysqli_fetch_assoc($q_santri)) {
            $id = $s['id_santri'];
            mysqli_query($koneksi, "INSERT INTO tagihan (id_santri, jenis_tagihan, bulan, tahun, nominal, status_tagihan) 
                                    VALUES ($id, '$jenis', '$bulan', '$tahun', $nominal, 'Belum Lunas')");
            $berhasil++;
        }
        $_SESSION['pesan_sukses'] = "Berhasil membuat tagihan masal untuk $berhasil santri aktif!";
    } else {
        // Hanya 1 santri
        $id = (int)$target;
        if (mysqli_query($koneksi, "INSERT INTO tagihan (id_santri, jenis_tagihan, bulan, tahun, nominal, status_tagihan) 
                                VALUES ($id, '$jenis', '$bulan', '$tahun', $nominal, 'Belum Lunas')")) {
            $_SESSION['pesan_sukses'] = "Tagihan berhasil dibuat untuk santri tersebut.";
        }
    }
    header("location:kelola_pembayaran.php");
    exit;
}

// ========================================================
// 2. PROSES ACC BUKTI TRANSFER (TERIMA / TOLAK)
// ========================================================
if (isset($_POST['proses_acc'])) {
    $id_pembayaran = (int)$_POST['id_pembayaran'];
    $id_tagihan = (int)$_POST['id_tagihan'];
    $keputusan = $_POST['keputusan']; // 'Diterima' atau 'Ditolak'
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan_admin']);

    if ($keputusan == 'Diterima') {
        mysqli_query($koneksi, "UPDATE pembayaran SET status_acc='Diterima', catatan_admin='$catatan' WHERE id_pembayaran=$id_pembayaran");
        mysqli_query($koneksi, "UPDATE tagihan SET status_tagihan='Lunas' WHERE id_tagihan=$id_tagihan");
        $_SESSION['pesan_sukses'] = "Pembayaran DITERIMA. Tagihan otomatis menjadi Lunas.";
    } else if ($keputusan == 'Ditolak') {
        mysqli_query($koneksi, "UPDATE pembayaran SET status_acc='Ditolak', catatan_admin='$catatan' WHERE id_pembayaran=$id_pembayaran");
        mysqli_query($koneksi, "UPDATE tagihan SET status_tagihan='Belum Lunas' WHERE id_tagihan=$id_tagihan");
        $_SESSION['pesan_error'] = "Pembayaran DITOLAK. Status tagihan dikembalikan menjadi Belum Lunas.";
    }
    header("location:kelola_pembayaran.php");
    exit;
}

// ========================================================
// 3. PROSES BAYAR CASH / OFFLINE (Tanpa Upload)
// ========================================================
if (isset($_POST['bayar_cash'])) {
    $id_tagihan = (int)$_POST['id_tagihan_cash'];
    // Buat record di tabel pembayaran dengan status langsung Diterima
    mysqli_query($koneksi, "INSERT INTO pembayaran (id_tagihan, bukti_transfer, status_acc, catatan_admin) 
                            VALUES ($id_tagihan, 'Bayar Tunai di Sekolah', 'Diterima', 'Dibayar tunai via Admin')");
    // Update tagihan jadi Lunas
    mysqli_query($koneksi, "UPDATE tagihan SET status_tagihan='Lunas' WHERE id_tagihan=$id_tagihan");

    $_SESSION['pesan_sukses'] = "Pembayaran tunai berhasil dicatat!";
    header("location:kelola_pembayaran.php");
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

    <?php if (isset($_SESSION['pesan_error'])): ?>
        <div id="alert-msg"
            class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm transition-opacity duration-300">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"></path>
                </svg>
                <p class="font-medium text-sm"><?php echo $_SESSION['pesan_error']; ?></p>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-red-500"><svg class="w-5 h-5" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg></button>
        </div>
        <?php unset($_SESSION['pesan_error']); ?>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-amber-200 overflow-hidden mb-8 relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
        <div class="p-6 border-b border-amber-100 bg-amber-50/30 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-amber-800 flex items-center gap-2">
                    <i class="fas fa-bell text-amber-500"></i> Menunggu ACC & Validasi
                </h2>
                <p class="text-sm text-amber-600/80">Wali santri telah mengupload bukti transfer, silakan divalidasi.
                </p>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left whitespace-nowrap tabel-biasa">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 font-semibold">Tgl Bayar</th>
                        <th class="p-4 font-semibold">Santri & Tagihan</th>
                        <th class="p-4 font-semibold text-right">Nominal</th>
                        <th class="p-4 font-semibold text-center">Bukti Transfer</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php
                    $q_pending = mysqli_query($koneksi, "
                        SELECT p.*, t.jenis_tagihan, t.bulan, t.tahun, t.nominal, s.nama_santri, s.kelas 
                        FROM pembayaran p 
                        JOIN tagihan t ON p.id_tagihan = t.id_tagihan 
                        JOIN santri s ON t.id_santri = s.id_santri 
                        WHERE p.status_acc = 'Pending' 
                        ORDER BY p.tanggal_bayar ASC
                    ");
                    if (mysqli_num_rows($q_pending) > 0) {
                        while ($p = mysqli_fetch_array($q_pending)):
                    ?>
                            <tr class="hover:bg-amber-50/20 transition-colors border-b border-gray-50">
                                <td class="p-4 text-gray-500 font-medium">
                                    <?php echo date('d M Y, H:i', strtotime($p['tanggal_bayar'])); ?></td>
                                <td class="p-4">
                                    <p class="font-bold text-gray-800"><?php echo htmlspecialchars($p['nama_santri']); ?> <span
                                            class="text-xs font-normal bg-gray-100 px-2 py-0.5 rounded text-gray-600"><?php echo $p['kelas']; ?></span>
                                    </p>
                                    <p class="text-xs text-amber-600 font-semibold mt-0.5"><?php echo $p['jenis_tagihan']; ?> -
                                        <?php echo $p['bulan'] ? $p['bulan'] . ' ' : ''; ?><?php echo $p['tahun']; ?></p>
                                </td>
                                <td class="p-4 text-right font-bold text-gray-700">Rp
                                    <?php echo number_format($p['nominal'], 0, ',', '.'); ?></td>
                                <td class="p-4 text-center">
                                    <button onclick="lihatBukti('<?php echo $p['bukti_transfer']; ?>')"
                                        class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                        <i class="fas fa-image"></i> Lihat Foto
                                    </button>
                                </td>
                                <td class="p-4 text-center">
                                    <button
                                        onclick="bukaModalAcc(<?php echo $p['id_pembayaran']; ?>, <?php echo $p['id_tagihan']; ?>, '<?php echo addslashes($p['nama_santri']); ?>')"
                                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-1.5 rounded-lg text-sm font-bold shadow-sm shadow-emerald-500/30 transition">
                                        Proses <i class="fas fa-chevron-right text-xs ml-1"></i>
                                    </button>
                                </td>
                            </tr>
                    <?php
                        endwhile;
                    } else {
                        echo '<tr><td colspan="5" class="p-8 text-center text-gray-400 italic">Tidak ada pembayaran yang menunggu validasi.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div
            class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Manajemen & Histori Tagihan</h2>
                <p class="text-sm text-gray-500">Daftar tunggakan dan tagihan yang sudah lunas.</p>
            </div>
            <button onclick="bukaModal('modal-generate')"
                class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/30">
                <i class="fas fa-plus-circle"></i> Generate Tagihan
            </button>
        </div>

        <div class="overflow-x-auto w-full">
            <table id="tabel-tagihan" class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Santri</th>
                        <th class="p-4 font-semibold">Tagihan Untuk</th>
                        <th class="p-4 font-semibold text-right">Nominal</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-center">Aksi Offline</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php
                    $no = 1;
                    $q_tagihan = mysqli_query($koneksi, "
                        SELECT t.*, s.nama_santri, s.kelas 
                        FROM tagihan t 
                        JOIN santri s ON t.id_santri = s.id_santri 
                        ORDER BY t.id_tagihan DESC
                    ");
                    while ($t = mysqli_fetch_array($q_tagihan)):
                    ?>
                        <tr class="hover:bg-blue-50/30 transition-colors border-b border-gray-50">
                            <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                            <td class="p-4">
                                <p class="font-bold text-gray-800"><?php echo htmlspecialchars($t['nama_santri']); ?></p>
                                <p class="text-xs text-gray-500">Kelas: <?php echo $t['kelas']; ?></p>
                            </td>
                            <td class="p-4 text-gray-600 font-medium">
                                <?php echo $t['jenis_tagihan']; ?> <span
                                    class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded ml-1"><?php echo $t['bulan'] ? $t['bulan'] . ' ' : ''; ?><?php echo $t['tahun']; ?></span>
                            </td>
                            <td class="p-4 text-right font-bold text-gray-700">Rp
                                <?php echo number_format($t['nominal'], 0, ',', '.'); ?></td>
                            <td class="p-4 text-center">
                                <?php if ($t['status_tagihan'] == 'Lunas'): ?>
                                    <span
                                        class="inline-flex px-2 py-1 rounded bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100"><i
                                            class="fas fa-check mr-1"></i> Lunas</span>
                                <?php elseif ($t['status_tagihan'] == 'Menunggu Konfirmasi'): ?>
                                    <span
                                        class="inline-flex px-2 py-1 rounded bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100"><i
                                            class="fas fa-clock mr-1"></i> Pending</span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex px-2 py-1 rounded bg-red-50 text-red-600 text-xs font-bold border border-red-100">Tunggakan</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-center">
                                <?php if ($t['status_tagihan'] == 'Belum Lunas'): ?>
                                    <button
                                        onclick="bukaModalCash(<?php echo $t['id_tagihan']; ?>, '<?php echo addslashes($t['nama_santri']); ?>', '<?php echo $t['jenis_tagihan']; ?>')"
                                        class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition border border-blue-200">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Bayar Tunai
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-generate"
        class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto"
            id="modal-generate-content">
            <div
                class="px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="text-xl font-bold text-gray-800">Generate Tagihan Baru</h3>
                <button type="button" onclick="tutupModal('modal-generate')"
                    class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="kelola_pembayaran.php" method="POST" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Penerima Tagihan</label>
                        <select name="target_santri" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-blue-50 focus:bg-white text-blue-700 font-bold">
                            <option value="semua">-- BUAT UNTUK SEMUA SANTRI AKTIF --</option>
                            <optgroup label="Atau Pilih Satu Santri:">
                                <?php
                                $q_s = mysqli_query($koneksi, "SELECT id_santri, nama_santri, kelas FROM santri WHERE status_aktif='Aktif'");
                                while ($s = mysqli_fetch_assoc($q_s)) {
                                    echo "<option value='" . $s['id_santri'] . "'>" . $s['kelas'] . " - " . $s['nama_santri'] . "</option>";
                                }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Tagihan</label>
                            <select name="jenis_tagihan" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                                <option value="SPP">SPP Bulanan</option>
                                <option value="Daftar Ulang">Daftar Ulang</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nominal (Rp)</label>
                            <input type="number" name="nominal" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white text-gray-800 font-bold"
                                placeholder="Cth: 150000">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Bulan <span
                                    class="text-xs font-normal text-gray-400">(Khusus SPP)</span></label>
                            <select name="bulan"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                                <option value="">-- Pilih Bulan --</option>
                                <?php
                                $bulans = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                foreach ($bulans as $b) {
                                    echo "<option value='$b'>$b</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun</label>
                            <input type="number" name="tahun" required value="<?php echo date('Y'); ?>"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" name="generate_tagihan"
                        onclick="return confirm('Peringatan: Generate untuk SEMUA SANTRI akan membuat data yang banyak sekaligus. Lanjutkan?')"
                        class="w-full px-4 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                        <i class="fas fa-paper-plane mr-2"></i> Terbitkan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-acc"
        class="fixed inset-0 bg-gray-900/60 z-[70] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300"
            id="modal-acc-content">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800">Validasi Pembayaran</h3>
                <button type="button" onclick="tutupModal('modal-acc')"
                    class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form action="kelola_pembayaran.php" method="POST" class="p-6">
                <input type="hidden" name="id_pembayaran" id="input_acc_id_pembayaran">
                <input type="hidden" name="id_tagihan" id="input_acc_id_tagihan">

                <div class="mb-5 text-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Memproses Pembayaran Untuk:</p>
                    <h4 id="nama_santri_acc" class="font-bold text-lg text-gray-800">Nama Santri</h4>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Keputusan ACC</label>
                        <select name="keputusan" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-blue-500 outline-none text-gray-800 font-bold">
                            <option value="Diterima" class="text-emerald-600">✅ DITERIMA (Tagihan Lunas)</option>
                            <option value="Ditolak" class="text-red-600">❌ DITOLAK (Kembali Menunggak)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan ke Wali Santri
                            (Opsional)</label>
                        <input type="text" name="catatan_admin"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white"
                            placeholder="Contoh: Foto bukti kurang jelas...">
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="tutupModal('modal-acc')"
                        class="w-1/3 px-4 py-3.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" name="proses_acc"
                        class="w-2/3 px-4 py-3.5 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">Simpan
                        Keputusan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-cash"
        class="fixed inset-0 bg-gray-900/60 z-[70] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300 text-center p-6"
            id="modal-cash-content">
            <div
                class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Pembayaran Tunai</h3>
            <p class="text-sm text-gray-500 mb-6">Anda akan mencatat pembayaran secara offline/tunai untuk <br><strong
                    id="nama_santri_cash" class="text-gray-800">Nama Santri</strong> (<span
                    id="jenis_tagihan_cash">SPP</span>).</p>

            <form action="kelola_pembayaran.php" method="POST">
                <input type="hidden" name="id_tagihan_cash" id="input_cash_id_tagihan">
                <div class="flex gap-3">
                    <button type="button" onclick="tutupModal('modal-cash')"
                        class="w-1/2 px-4 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" name="bayar_cash"
                        class="w-1/2 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition">Lunaskan</button>
                </div>
            </form>
        </div>
    </div>

</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-tagihan').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [
                [0, 'desc']
            ],
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

    // Modal Validasi ACC
    function bukaModalAcc(id_pembayaran, id_tagihan, nama_santri) {
        document.getElementById('input_acc_id_pembayaran').value = id_pembayaran;
        document.getElementById('input_acc_id_tagihan').value = id_tagihan;
        document.getElementById('nama_santri_acc').innerText = nama_santri;
        bukaModal('modal-acc');
    }

    // Modal Bayar Cash Offline
    function bukaModalCash(id_tagihan, nama_santri, jenis) {
        document.getElementById('input_cash_id_tagihan').value = id_tagihan;
        document.getElementById('nama_santri_cash').innerText = nama_santri;
        document.getElementById('jenis_tagihan_cash').innerText = jenis;
        bukaModal('modal-cash');
    }

    // Fungsi Lihat Bukti Transfer buka Tab Baru
    function lihatBukti(nama_file) {
        if (nama_file && nama_file !== 'Bayar Tunai di Sekolah') {
            window.open('../uploads/' + nama_file, '_blank');
        } else {
            alert('Pembayaran ini dilakukan secara tunai offline.');
        }
    }

    // Auto-hide alert after 4 seconds
    setTimeout(() => {
        const alertEl = document.getElementById('alert-msg');
        if (alertEl) {
            alertEl.classList.add('opacity-0');
            setTimeout(() => alertEl.style.display = 'none', 300);
        }
    }, 4000);
</script>

<?php include '../components/footer.php'; ?>