<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// ========================================================
// 1. PROSES TAMBAH USER (Hanya Admin / Pimpinan)
// ========================================================
if (isset($_POST['tambah_user'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Cek apakah username sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['pesan_error'] = "Gagal! Username '$username' sudah digunakan.";
    } else {
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan_sukses'] = "Berhasil membuat akun $role baru!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menyimpan data!";
        }
    }
    header("location:kelola_user.php");
    exit;
}

// ========================================================
// 2. PROSES EDIT USER
// ========================================================
if (isset($_POST['edit_user'])) {
    $id_user = (int)$_POST['id_user'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Update username dan role
    $query = "UPDATE users SET username='$username', role='$role' WHERE id_user=$id_user";
    mysqli_query($koneksi, $query);

    // Jika password diisi, update password
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        mysqli_query($koneksi, "UPDATE users SET password='$password' WHERE id_user=$id_user");
    }

    $_SESSION['pesan_sukses'] = "Data akun berhasil diperbarui!";
    header("location:kelola_user.php");
    exit;
}

// ========================================================
// 3. PROSES HAPUS USER
// ========================================================
if (isset($_GET['hapus'])) {
    $id_user = (int)$_GET['hapus'];

    // Proteksi: Jangan sampai Admin menghapus akunnya sendiri yang sedang dipakai!
    if ($id_user == $_SESSION['id_user']) {
        $_SESSION['pesan_error'] = "Gagal! Anda tidak bisa menghapus akun yang sedang Anda gunakan saat ini.";
    } else {
        if (mysqli_query($koneksi, "DELETE FROM users WHERE id_user=$id_user")) {
            $_SESSION['pesan_sukses'] = "Akun berhasil dihapus permanen!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus akun!";
        }
    }
    header("location:kelola_user.php");
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

<div class="flex-1 flex flex-col h-screen overflow-hidden">

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
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
                <button onclick="this.parentElement.style.display='none'" class="text-red-500"><svg class="w-5 h-5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
            <?php unset($_SESSION['pesan_error']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div
                class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Manajemen Akses Sistem</h2>
                    <p class="text-sm text-gray-500">Kelola akun khusus level Admin dan Pimpinan Yayasan.</p>
                </div>
                <button onclick="bukaModal('modal-tambah')"
                    class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/30">
                    <i class="fas fa-user-shield"></i> Tambah Akun
                </button>
            </div>

            <div class="overflow-x-auto w-full">
                <table id="tabel-user" class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold w-16 text-center">No</th>
                            <th class="p-4 font-semibold">Username Akun</th>
                            <th class="p-4 font-semibold text-center">Level Akses (Role)</th>
                            <th class="p-4 font-semibold">Tanggal Dibuat</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <?php
                        $no = 1;
                        // HANYA tampilkan Admin dan Pimpinan. Wali dan Pengajar diurus di menu masing-masing
                        $data_user = mysqli_query($koneksi, "SELECT * FROM users WHERE role IN ('admin', 'pimpinan') ORDER BY id_user DESC");
                        while ($d = mysqli_fetch_array($data_user)):
                        ?>
                            <tr class="hover:bg-blue-50/30 transition-colors border-b border-gray-50">
                                <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white <?php echo $d['role'] == 'admin' ? 'bg-blue-500' : 'bg-purple-500'; ?>">
                                            <i
                                                class="fas <?php echo $d['role'] == 'admin' ? 'fa-user-cog' : 'fa-user-tie'; ?>"></i>
                                        </div>
                                        <span
                                            class="font-bold text-gray-800 text-base">@<?php echo htmlspecialchars($d['username']); ?></span>

                                        <?php if ($d['id_user'] == $_SESSION['id_user']): ?>
                                            <span
                                                class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded ml-2">Anda</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <?php if ($d['role'] == 'admin'): ?>
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">Administrator</span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200">Pimpinan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-gray-500 text-sm">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?php echo date('d M Y', strtotime($d['created_at'])); ?>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="bukaModalEdit(<?php echo $d['id_user']; ?>, '<?php echo addslashes($d['username']); ?>', '<?php echo $d['role']; ?>')"
                                            class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition"
                                            title="Edit Akun">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <?php if ($d['id_user'] != $_SESSION['id_user']): ?>
                                            <a href="kelola_user.php?hapus=<?php echo $d['id_user']; ?>"
                                                onclick="return confirm('Yakin ingin mencabut akses dan menghapus akun ini permanen?');"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition"
                                                title="Hapus Akun">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </a>
                                        <?php else: ?>
                                            <span class="p-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed"
                                                title="Anda tidak bisa menghapus akun Anda sendiri"><i
                                                    class="fas fa-ban"></i></span>
                                        <?php endif; ?>
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
            <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300"
                id="modal-tambah-content">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Akun Sistem</h3>
                    <button type="button" onclick="tutupModal('modal-tambah')"
                        class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_user.php" method="POST" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                            <input type="text" name="username" required placeholder="Contoh: admin_pusat"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white text-gray-800 font-bold">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hak Akses (Role)</label>
                            <select name="role" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-blue-500 outline-none bg-blue-50 focus:bg-white text-blue-700 font-bold">
                                <option value="admin">Administrator</option>
                                <option value="pimpinan">Pimpinan Yayasan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" name="tambah_user"
                            class="w-full px-4 py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="modal-edit"
            class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300"
                id="modal-edit-content">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Edit Akses Sistem</h3>
                    <button type="button" onclick="tutupModal('modal-edit')"
                        class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_user.php" method="POST" class="p-6">
                    <input type="hidden" name="id_user" id="input_edit_id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                            <input type="text" name="username" id="input_edit_username" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white text-gray-800 font-bold">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru <span
                                    class="font-normal text-amber-500 text-xs">(Kosongkan jika tetap)</span></label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hak Akses (Role)</label>
                            <select name="role" id="input_edit_role" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-amber-50 focus:bg-white text-amber-700 font-bold">
                                <option value="admin">Administrator</option>
                                <option value="pimpinan">Pimpinan Yayasan</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" name="edit_user"
                            class="w-full px-4 py-3.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabel-user').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [{
                orderable: false,
                targets: 4
            }], // Matiin sorting di kolom aksi
            order: [
                [0, 'asc']
            ], // Urutkan dari No 1
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

    // Fungsi lempar data ke Modal Edit
    function bukaModalEdit(id, username, role) {
        document.getElementById('input_edit_id').value = id;
        document.getElementById('input_edit_username').value = username;
        document.getElementById('input_edit_role').value = role;
        bukaModal('modal-edit');
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