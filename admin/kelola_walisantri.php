<?php
session_start();
if($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin"){
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// ========================================================
// 1. PROSES TAMBAH DATA WALI & AKUN USER
// ========================================================
if (isset($_POST['tambah_wali'])) {
    $nama_ayah = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']); 
    
    // Insert ke tabel users dulu (Role: walisantri)
    $query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'walisantri')";
    if(mysqli_query($koneksi, $query_user)){
        $id_user = mysqli_insert_id($koneksi);
        
        // Insert ke tabel wali_santri
        mysqli_query($koneksi, "INSERT INTO wali_santri (id_user, nama_ayah, nama_ibu, no_hp, alamat) 
                                VALUES ($id_user, '$nama_ayah', '$nama_ibu', '$no_hp', '$alamat')");
        
        $_SESSION['pesan_sukses'] = "Berhasil! Biodata Wali dan akun login telah dibuat.";
    } else {
        $_SESSION['pesan_error'] = "Gagal! Username mungkin sudah digunakan orang lain.";
    }
    header("location:kelola_walisantri.php");
    exit;
}

// ========================================================
// 2. PROSES EDIT DATA WALI
// ========================================================
if (isset($_POST['edit_wali'])) {
    $id_wali = (int)$_POST['id_wali'];
    $id_user = (int)$_POST['id_user'];
    
    $nama_ayah = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    
    mysqli_query($koneksi, "UPDATE wali_santri SET nama_ayah='$nama_ayah', nama_ibu='$nama_ibu', no_hp='$no_hp', alamat='$alamat' WHERE id_wali=$id_wali");
    mysqli_query($koneksi, "UPDATE users SET username='$username' WHERE id_user=$id_user");
    
    if(!empty($_POST['password'])){
        $password = md5($_POST['password']);
        mysqli_query($koneksi, "UPDATE users SET password='$password' WHERE id_user=$id_user");
    }
    
    $_SESSION['pesan_sukses'] = "Data Wali Santri berhasil diperbarui!";
    header("location:kelola_walisantri.php");
    exit;
}

// ========================================================
// 3. PROSES HAPUS DATA
// ========================================================
if (isset($_GET['hapus_user'])) {
    $id_user = (int)$_GET['hapus_user'];
    // Hapus dari tabel users otomatis menghapus di tabel wali_santri (ON DELETE CASCADE)
    if(mysqli_query($koneksi, "DELETE FROM users WHERE id_user=$id_user")){
        $_SESSION['pesan_sukses'] = "Data Wali dan akun loginnya berhasil dihapus!";
    } else {
        $_SESSION['pesan_error'] = "Gagal menghapus data!";
    }
    header("location:kelola_walisantri.php");
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

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div
            class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Daftar Wali Santri</h2>
                <p class="text-sm text-gray-500">Kelola data orang tua santri dan akses portal mereka.</p>
            </div>
            <button onclick="bukaModal('modal-tambah')"
                class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Wali
            </button>
        </div>

        <div class="overflow-x-auto w-full">
            <table id="tabel-wali" class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold w-16 text-center">No</th>
                        <th class="p-4 font-semibold">Orang Tua / Wali</th>
                        <th class="p-4 font-semibold">Kontak & Alamat</th>
                        <th class="p-4 font-semibold">Akun Login</th>
                        <th class="p-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php 
                    $no = 1;
                    $data_wali = mysqli_query($koneksi, "SELECT w.*, u.username FROM wali_santri w JOIN users u ON w.id_user = u.id_user ORDER BY w.id_wali DESC");
                    while($d = mysqli_fetch_array($data_wali)): 
                    ?>
                    <tr class="hover:bg-blue-50/30 transition-colors group border-b border-gray-50">
                        <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                        <td class="p-4">
                            <p class="font-bold text-gray-800">Ayah:
                                <?php echo htmlspecialchars($d['nama_ayah'] ?: '-'); ?></p>
                            <p class="text-xs text-gray-500">Ibu: <?php echo htmlspecialchars($d['nama_ibu'] ?: '-'); ?>
                            </p>
                        </td>
                        <td class="p-4 text-gray-600">
                            <p class="mb-1"><i class="fab fa-whatsapp text-emerald-500 mr-1"></i>
                                <?php echo $d['no_hp'] ?: '-'; ?></p>
                            <p class="text-xs text-gray-400 truncate max-w-[200px]"><i
                                    class="fas fa-map-marker-alt mr-1"></i>
                                <?php echo htmlspecialchars($d['alamat'] ?: '-'); ?></p>
                        </td>
                        <td class="p-4">
                            <span
                                class="inline-flex px-2 py-1 rounded bg-slate-100 text-slate-700 font-mono text-xs border border-slate-200">@<?php echo htmlspecialchars($d['username']); ?></span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    onclick="bukaModalEdit(<?php echo $d['id_wali']; ?>, <?php echo $d['id_user']; ?>, '<?php echo addslashes($d['nama_ayah']); ?>', '<?php echo addslashes($d['nama_ibu']); ?>', '<?php echo addslashes($d['no_hp']); ?>', '<?php echo addslashes($d['alamat']); ?>', '<?php echo addslashes($d['username']); ?>')"
                                    class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition"
                                    title="Edit Data">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <a href="kelola_walisantri.php?hapus_user=<?php echo $d['id_user']; ?>"
                                    onclick="return confirm('Menghapus data Wali akan menghapus akun loginnya juga. Lanjutkan?');"
                                    class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition"
                                    title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
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
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto"
            id="modal-tambah-content">
            <div
                class="px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="text-xl font-bold text-gray-800">Tambah Wali Santri</h3>
                <button type="button" onclick="tutupModal('modal-tambah')"
                    class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><svg class="w-6 h-6"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
            <form action="kelola_walisantri.php" method="POST" class="p-6">
                <div class="space-y-4">
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 mb-4">
                        <p class="text-xs text-blue-700 font-semibold uppercase tracking-wider">1. Biodata Orang Tua</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ayah</label><input
                                type="text" name="nama_ayah"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ibu</label><input
                                type="text" name="nama_ibu"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">No. Handphone (WA)</label><input
                            type="text" name="no_hp"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white text-blue-600 font-bold">
                    </div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label><textarea
                            name="alamat" rows="2"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white"></textarea>
                    </div>

                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 mt-6 mb-4">
                        <p class="text-xs text-blue-700 font-semibold uppercase tracking-wider">2. Akun Portal Wali</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Username</label><input
                                type="text" name="username" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Password</label><input
                                type="password" name="password" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>
                <div class="mt-8"><button type="submit" name="tambah_wali"
                        class="w-full px-4 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition">Simpan
                        & Buat Akun</button></div>
            </form>
        </div>
    </div>

    <div id="modal-edit"
        class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto"
            id="modal-edit-content">
            <div
                class="px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="text-xl font-bold text-gray-800">Edit Wali Santri</h3>
                <button type="button" onclick="tutupModal('modal-edit')"
                    class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><svg class="w-6 h-6"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
            <form action="kelola_walisantri.php" method="POST" class="p-6">
                <input type="hidden" name="id_wali" id="input_edit_id_wali">
                <input type="hidden" name="id_user" id="input_edit_id_user">

                <div class="space-y-4">
                    <div class="bg-amber-50 p-3 rounded-lg border border-amber-100 mb-4">
                        <p class="text-xs text-amber-700 font-semibold uppercase tracking-wider">Biodata Orang Tua</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ayah</label><input
                                type="text" name="nama_ayah" id="input_edit_ayah"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ibu</label><input
                                type="text" name="nama_ibu" id="input_edit_ibu"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">No. Handphone</label><input
                            type="text" name="no_hp" id="input_edit_hp"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white font-bold">
                    </div>
                    <div><label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label><textarea
                            name="alamat" id="input_edit_alamat" rows="2"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white"></textarea>
                    </div>

                    <div class="bg-amber-50 p-3 rounded-lg border border-amber-100 mt-6 mb-4">
                        <p class="text-xs text-amber-700 font-semibold uppercase tracking-wider">Akun Portal Wali</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Username</label><input
                                type="text" name="username" id="input_edit_username" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                        <div><label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label><input
                                type="password" name="password" placeholder="Kosongkan jika tetap"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white placeholder-gray-400">
                        </div>
                    </div>
                </div>
                <div class="mt-8"><button type="submit" name="edit_wali"
                        class="w-full px-4 py-3.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">Simpan
                        Perubahan</button></div>
            </form>
        </div>
    </div>

</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabel-wali').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        columnDefs: [{
            orderable: false,
            targets: 4
        }],
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

function bukaModalEdit(id_wali, id_user, ayah, ibu, hp, alamat, username) {
    document.getElementById('input_edit_id_wali').value = id_wali;
    document.getElementById('input_edit_id_user').value = id_user;
    document.getElementById('input_edit_ayah').value = ayah;
    document.getElementById('input_edit_ibu').value = ibu;
    document.getElementById('input_edit_hp').value = hp;
    document.getElementById('input_edit_alamat').value = alamat;
    document.getElementById('input_edit_username').value = username;
    bukaModal('modal-edit');
}

setTimeout(() => {
    const alertEl = document.getElementById('alert-msg');
    if (alertEl) {
        alertEl.classList.add('opacity-0');
        setTimeout(() => alertEl.style.display = 'none', 300);
    }
}, 4000);
</script>

<?php include '../components/footer.php'; ?>