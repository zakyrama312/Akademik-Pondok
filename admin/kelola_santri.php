<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// ========================================================
// 1. PROSES TAMBAH DATA
// ========================================================
if (isset($_POST['tambah_santri'])) {
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_santri']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);
    $id_wali = !empty($_POST['id_wali']) ? $_POST['id_wali'] : "NULL";

    $query = "INSERT INTO santri (id_wali, nis, nama_santri, tempat_lahir, tanggal_lahir, jenis_kelamin, kelas, status_aktif) 
              VALUES ($id_wali, '$nis', '$nama', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$kelas', '$status')";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan_sukses'] = "Berhasil! Data santri baru telah ditambahkan.";
    } else {
        $_SESSION['pesan_error'] = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
    header("location:kelola_santri.php");
    exit;
}

// ========================================================
// 2. PROSES EDIT DATA
// ========================================================
if (isset($_POST['edit_santri'])) {
    $id = (int)$_POST['id_santri'];
    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_santri']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);
    $id_wali = !empty($_POST['id_wali']) ? $_POST['id_wali'] : "NULL";

    $query = "UPDATE santri SET 
                id_wali=$id_wali, 
                nis='$nis', 
                nama_santri='$nama', 
                tempat_lahir='$tempat_lahir',
                tanggal_lahir='$tanggal_lahir',
                jenis_kelamin='$jenis_kelamin',
                kelas='$kelas', 
                status_aktif='$status' 
              WHERE id_santri=$id";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan_sukses'] = "Data santri berhasil diperbarui!";
    } else {
        $_SESSION['pesan_error'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
    header("location:kelola_santri.php");
    exit;
}

// ========================================================
// 3. PROSES HAPUS DATA (Tetap Sama)
// ========================================================
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($koneksi, "DELETE FROM santri WHERE id_santri=$id")) {
        $_SESSION['pesan_sukses'] = "Data santri berhasil dihapus bersih!";
    } else {
        $_SESSION['pesan_error'] = "Gagal menghapus data!";
    }
    header("location:kelola_santri.php");
    exit;
}

include '../components/header.php';
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    /* Custom Scrollbar Tipis untuk Modal */
    .custom-slim-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .custom-slim-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .custom-slim-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .custom-slim-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* DataTables Styling (Sesuai punya kamu) */
    .dataTables_wrapper {
        padding: 1.5rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.35rem 0.75rem;
        margin-left: 0.5rem;
        outline: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        color: white !important;
        border: 1px solid #3b82f6 !important;
        border-radius: 0.5rem;
    }
</style>

<?php include '../components/sidebar.php'; ?>

<div class="flex-1 flex flex-col h-screen overflow-hidden">
    <?php include '../components/navbar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative bg-gray-50">
        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div id="alert-msg"
                class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
                <p class="font-medium text-sm"><?php echo $_SESSION['pesan_sukses']; ?></p>
                <button onclick="this.parentElement.remove()" class="text-emerald-500"><i class="fas fa-times"></i></button>
            </div>
        <?php unset($_SESSION['pesan_sukses']);
        endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div
                class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Santri Aktif</h2>
                    <p class="text-sm text-gray-500">Kelola data induk santri dan status akademik.</p>
                </div>
                <button onclick="bukaModal('modal-tambah')"
                    class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Santri
                </button>
            </div>

            <div class="overflow-x-auto w-full">
                <table id="tabel-santri" class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold w-16 text-center">No</th>
                            <th class="p-4 font-semibold">Profil Santri</th>
                            <th class="p-4 font-semibold text-center">Kelas</th>
                            <th class="p-4 font-semibold">Wali Santri</th>
                            <th class="p-4 font-semibold text-center">Status</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <?php
                        $no = 1;
                        $data_santri = mysqli_query($koneksi, "SELECT s.*, w.nama_ayah FROM santri s LEFT JOIN wali_santri w ON s.id_wali = w.id_wali ORDER BY s.id_santri DESC");
                        while ($d = mysqli_fetch_array($data_santri)):
                        ?>
                            <tr class="hover:bg-blue-50/30 transition-colors border-b border-gray-50">
                                <td class="p-4 text-center text-gray-500">#<?php echo $no++; ?></td>
                                <td class="p-4">
                                    <div>
                                        <p class="font-bold text-gray-800">
                                            <?php echo htmlspecialchars($d['nama_santri']); ?></p>
                                        <p class="text-xs text-gray-500">NIS: <?php echo htmlspecialchars($d['nis']); ?> |
                                            <?php echo $d['jenis_kelamin']; ?></p>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span
                                        class="px-3 py-1 rounded bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200"><?php echo htmlspecialchars($d['kelas']); ?></span>
                                </td>
                                <td class="p-4 text-gray-600">
                                    <?php echo $d['nama_ayah'] ? '<i class="fas fa-user-tie text-gray-400 mr-1"></i> ' . htmlspecialchars($d['nama_ayah']) : '<span class="text-red-400 italic text-xs">Belum di-set</span>'; ?>
                                </td>
                                <td class="p-4 text-center">
                                    <span
                                        class="px-2 py-1 rounded text-xs font-bold border <?php echo ($d['status_aktif'] == 'Aktif') ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100'; ?>">
                                        <?php echo $d['status_aktif']; ?>
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="bukaModalEdit(<?php echo $d['id_santri']; ?>, '<?php echo $d['nis']; ?>', '<?php echo addslashes($d['nama_santri']); ?>', '<?php echo addslashes($d['tempat_lahir']); ?>', '<?php echo $d['tanggal_lahir']; ?>', '<?php echo $d['jenis_kelamin']; ?>', '<?php echo $d['kelas']; ?>', '<?php echo $d['status_aktif']; ?>', '<?php echo $d['id_wali']; ?>')"
                                            class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="kelola_santri.php?hapus=<?php echo $d['id_santri']; ?>"
                                            onclick="return confirm('Hapus data santri ini?');"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="modal-tambah"
            class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto custom-slim-scroll"
                id="modal-tambah-content">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Santri Baru</h3>
                    <button type="button" onclick="tutupModal('modal-tambah')"
                        class="text-gray-400 hover:text-red-500 transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_santri.php" method="POST" class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-gray-700">NIS</label><input type="text" name="nis"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500">
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700">Kelas</label><input type="text"
                                name="kelas" required placeholder="Cth: 10"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500 font-bold text-blue-600">
                        </div>
                    </div>
                    <div><label class="text-sm font-semibold text-gray-700">Nama Lengkap</label><input type="text"
                            name="nama_santri" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-gray-700">Tempat Lahir</label><input type="text"
                                name="tempat_lahir" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500">
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700">Tanggal Lahir</label><input type="date"
                                name="tanggal_lahir" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Wali Santri (Opsional)</label>
                        <select name="id_wali"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500">
                            <option value="">-- Pilih Wali Santri --</option>
                            <?php
                            $wali = mysqli_query($koneksi, "SELECT * FROM wali_santri");
                            while ($w = mysqli_fetch_array($wali)) echo "<option value='" . $w['id_wali'] . "'>" . $w['nama_ayah'] . "</option>";
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Status</label>
                        <select name="status_aktif"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-blue-500">
                            <option value="Aktif">Aktif</option>
                            <option value="Lulus">Lulus</option>
                            <option value="Pindah">Pindah</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_santri"
                        class="w-full py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition">Simpan
                        Data</button>
                </form>
            </div>
        </div>

        <div id="modal-edit"
            class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto custom-slim-scroll"
                id="modal-edit-content">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                    <h3 class="text-xl font-bold text-gray-800">Edit Data Santri</h3>
                    <button type="button" onclick="tutupModal('modal-edit')"
                        class="text-gray-400 hover:text-red-500 transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_santri.php" method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="id_santri" id="edit_id">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-gray-700">NIS</label><input type="text" name="nis"
                                id="edit_nis" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500">
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700">Kelas</label><input type="text"
                                name="kelas" id="edit_kelas" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500 font-bold">
                        </div>
                    </div>
                    <div><label class="text-sm font-semibold text-gray-700">Nama Lengkap</label><input type="text"
                            name="nama_santri" id="edit_nama" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-sm font-semibold text-gray-700">Tempat Lahir</label><input type="text"
                                name="tempat_lahir" id="edit_tempat" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500">
                        </div>
                        <div><label class="text-sm font-semibold text-gray-700">Tanggal Lahir</label><input type="date"
                                name="tanggal_lahir" id="edit_tanggal" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit_jk" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Wali Santri</label>
                        <select name="id_wali" id="edit_wali"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500">
                            <option value="">-- Pilih Wali Santri --</option>
                            <?php
                            $wali_edit = mysqli_query($koneksi, "SELECT * FROM wali_santri");
                            while ($w = mysqli_fetch_array($wali_edit)) echo "<option value='" . $w['id_wali'] . "'>" . $w['nama_ayah'] . "</option>";
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Status</label>
                        <select name="status_aktif" id="edit_status"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none focus:border-amber-500">
                            <option value="Aktif">Aktif</option>
                            <option value="Lulus">Lulus</option>
                            <option value="Pindah">Pindah</option>
                        </select>
                    </div>
                    <button type="submit" name="edit_santri"
                        class="w-full py-3.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">Simpan
                        Perubahan</button>
                </form>
            </div>
        </div>

    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-santri').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [{
                orderable: false,
                targets: 5
            }],
            order: [
                [0, 'asc']
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

    // FUNGSI EDIT YANG SUDAH FULL KOMPLIT
    function bukaModalEdit(id, nis, nama, tempat, tanggal, jk, kelas, status, id_wali) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nis').value = nis;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_tempat').value = tempat;
        document.getElementById('edit_tanggal').value = tanggal;
        document.getElementById('edit_jk').value = jk;
        document.getElementById('edit_kelas').value = kelas;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_wali').value = id_wali;
        bukaModal('modal-edit');
    }

    setTimeout(() => {
        const alertEl = document.getElementById('alert-msg');
        if (alertEl) {
            alertEl.classList.add('opacity-0');
            setTimeout(() => alertEl.remove(), 300);
        }
    }, 4000);
</script>

<?php include '../components/footer.php'; ?>