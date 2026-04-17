<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// ========================================================
// 1. PROSES CRUD MATA PELAJARAN
// ========================================================
if (isset($_POST['tambah_mapel'])) {
    $nama_mapel = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
    if (mysqli_query($koneksi, "INSERT INTO mata_pelajaran (nama_mapel) VALUES ('$nama_mapel')")) {
        $_SESSION['pesan_sukses'] = "Mata Pelajaran berhasil ditambahkan!";
    } else {
        $_SESSION['pesan_error'] = "Gagal menambah mata pelajaran.";
    }
    header("location:kelola_jadwal.php");
    exit;
}

if (isset($_POST['edit_mapel'])) {
    $id_mapel = (int)$_POST['id_mapel'];
    $nama_mapel = mysqli_real_escape_string($koneksi, $_POST['nama_mapel']);
    if (mysqli_query($koneksi, "UPDATE mata_pelajaran SET nama_mapel='$nama_mapel' WHERE id_mapel=$id_mapel")) {
        $_SESSION['pesan_sukses'] = "Mata Pelajaran berhasil diperbarui!";
    }
    header("location:kelola_jadwal.php");
    exit;
}

if (isset($_GET['hapus_mapel'])) {
    $id_mapel = (int)$_GET['hapus_mapel'];
    if (mysqli_query($koneksi, "DELETE FROM mata_pelajaran WHERE id_mapel=$id_mapel")) {
        $_SESSION['pesan_sukses'] = "Mata Pelajaran beserta jadwal terkait berhasil dihapus!";
    }
    header("location:kelola_jadwal.php");
    exit;
}

// ========================================================
// 2. PROSES CRUD JADWAL PENGAMPU
// ========================================================
if (isset($_POST['tambah_jadwal'])) {
    $id_pengajar = (int)$_POST['id_pengajar'];
    $id_mapel = (int)$_POST['id_mapel'];
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);

    // Cek apakah jadwal ini sudah ada (mencegah duplikat guru ngajar mapel yang sama di kelas yang sama)
    $cek = mysqli_query($koneksi, "SELECT * FROM jadwal_pengampu WHERE id_pengajar=$id_pengajar AND id_mapel=$id_mapel AND kelas='$kelas'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['pesan_error'] = "Gagal! Guru tersebut sudah terdaftar mengajar mapel ini di kelas $kelas.";
    } else {
        if (mysqli_query($koneksi, "INSERT INTO jadwal_pengampu (id_pengajar, id_mapel, kelas) VALUES ($id_pengajar, $id_mapel, '$kelas')")) {
            $_SESSION['pesan_sukses'] = "Jadwal Mengajar berhasil ditambahkan!";
        }
    }
    header("location:kelola_jadwal.php");
    exit;
}

if (isset($_POST['edit_jadwal'])) {
    $id_pengampu = (int)$_POST['id_pengampu'];
    $id_pengajar = (int)$_POST['id_pengajar'];
    $id_mapel = (int)$_POST['id_mapel'];
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);

    if (mysqli_query($koneksi, "UPDATE jadwal_pengampu SET id_pengajar=$id_pengajar, id_mapel=$id_mapel, kelas='$kelas' WHERE id_pengampu=$id_pengampu")) {
        $_SESSION['pesan_sukses'] = "Jadwal Mengajar berhasil diperbarui!";
    }
    header("location:kelola_jadwal.php");
    exit;
}

if (isset($_GET['hapus_jadwal'])) {
    $id_pengampu = (int)$_GET['hapus_jadwal'];
    if (mysqli_query($koneksi, "DELETE FROM jadwal_pengampu WHERE id_pengampu=$id_pengampu")) {
        $_SESSION['pesan_sukses'] = "Jadwal Mengajar berhasil dihapus!";
    }
    header("location:kelola_jadwal.php");
    exit;
}

include '../components/header.php';
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* -------------------------------------- */
    /* STYLING DATATABLES                     */
    /* -------------------------------------- */
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

    table.dataTable.no-footer,
    table.dataTable thead th,
    table.dataTable thead td {
        border-bottom: 1px solid #f1f5f9;
    }

    /* -------------------------------------- */
    /* STYLING CUSTOM SELECT2 ALA TAILWIND    */
    /* -------------------------------------- */
    .select2-container .select2-selection--single {
        height: 42px !important;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
        border-radius: 0.75rem !important;
        border-color: #e5e7eb !important;
        /* gray-200 */
        background-color: #f9fafb !important;
        /* gray-50 */
        outline: none;
        transition: all 0.2s;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 10px !important;
    }

    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #0d9488 !important;
        /* teal-600 */
        background-color: #ffffff !important;
        box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.2) !important;
    }

    .select2-dropdown {
        border-color: #99f6e4 !important;
        /* teal-200 */
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
        margin-top: 4px;
        z-index: 9999;
        /* Pastikan selalu di atas modal */
    }

    .select2-search__field {
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        padding: 0.5rem 0.75rem !important;
        outline: none !important;
    }

    .select2-search__field:focus {
        border-color: #5eead4 !important;
        /* teal-300 */
        box-shadow: 0 0 0 2px rgba(94, 234, 212, 0.2) !important;
    }

    .select2-results__option--highlighted {
        background-color: #0d9488 !important;
        /* teal-600 */
        color: white !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #374151;
        /* gray-700 */
        font-weight: 600;
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
                    <h2 class="text-lg font-bold text-indigo-800"><i class="fas fa-book mr-2"></i> Daftar Mata Pelajaran
                    </h2>
                    <p class="text-sm text-indigo-600/80">Kelola master data mata pelajaran yang ada di sekolah.</p>
                </div>
                <button onclick="bukaModal('modal-mapel-tambah')"
                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition flex items-center gap-2 shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-plus"></i> Tambah Mapel
                </button>
            </div>

            <div class="overflow-x-auto w-full">
                <table id="tabel-mapel" class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold w-16 text-center">No</th>
                            <th class="p-4 font-semibold">Nama Mata Pelajaran</th>
                            <th class="p-4 font-semibold text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <?php
                        $no = 1;
                        $q_mapel = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY id_mapel DESC");
                        while ($m = mysqli_fetch_array($q_mapel)):
                        ?>
                            <tr class="hover:bg-indigo-50/30 transition-colors border-b border-gray-50">
                                <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                                <td class="p-4 font-bold text-gray-800"><?php echo htmlspecialchars($m['nama_mapel']); ?>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="bukaModalMapelEdit(<?php echo $m['id_mapel']; ?>, '<?php echo addslashes($m['nama_mapel']); ?>')"
                                            class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="kelola_jadwal.php?hapus_mapel=<?php echo $m['id_mapel']; ?>"
                                            onclick="return confirm('Peringatan: Menghapus mapel akan menghapus semua jadwal terkait! Lanjutkan?');"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition"
                                            title="Hapus">
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

        <div class="bg-white rounded-2xl shadow-sm border border-teal-100 overflow-hidden">
            <div
                class="p-6 border-b border-teal-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-teal-50/50">
                <div>
                    <h2 class="text-lg font-bold text-teal-800"><i class="fas fa-calendar-alt mr-2"></i> Jadwal Mengajar
                        Guru</h2>
                    <p class="text-sm text-teal-600/80">Tentukan guru mana yang mengajar mata pelajaran di kelas
                        tertentu.
                    </p>
                </div>
                <button onclick="bukaModal('modal-jadwal-tambah')"
                    class="px-5 py-2.5 bg-teal-600 text-white rounded-xl font-semibold hover:bg-teal-700 transition flex items-center gap-2 shadow-lg shadow-teal-500/30">
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </button>
            </div>

            <div class="overflow-x-auto w-full">
                <table id="tabel-jadwal" class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold w-16 text-center">No</th>
                            <th class="p-4 font-semibold">Nama Guru</th>
                            <th class="p-4 font-semibold">Mata Pelajaran</th>
                            <th class="p-4 font-semibold text-center">Kelas</th>
                            <th class="p-4 font-semibold text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <?php
                        $no = 1;
                        $q_jadwal = mysqli_query($koneksi, "
                        SELECT j.*, p.nama_pengajar, m.nama_mapel 
                        FROM jadwal_pengampu j 
                        JOIN pengajar p ON j.id_pengajar = p.id_pengajar 
                        JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel 
                        ORDER BY j.id_pengampu DESC
                    ");
                        while ($j = mysqli_fetch_array($q_jadwal)):
                        ?>
                            <tr class="hover:bg-teal-50/30 transition-colors border-b border-gray-50">
                                <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                                <td class="p-4 font-bold text-gray-800"><i
                                        class="fas fa-user-tie text-teal-500 mr-2"></i><?php echo htmlspecialchars($j['nama_pengajar']); ?>
                                </td>
                                <td class="p-4 text-gray-600 font-medium"><?php echo htmlspecialchars($j['nama_mapel']); ?>
                                </td>
                                <td class="p-4 text-center">
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold border border-teal-200"><?php echo htmlspecialchars($j['kelas']); ?></span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="bukaModalJadwalEdit(<?php echo $j['id_pengampu']; ?>, <?php echo $j['id_pengajar']; ?>, <?php echo $j['id_mapel']; ?>, '<?php echo addslashes($j['kelas']); ?>')"
                                            class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="kelola_jadwal.php?hapus_jadwal=<?php echo $j['id_pengampu']; ?>"
                                            onclick="return confirm('Hapus jadwal ini?');"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition"
                                            title="Hapus">
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

        <div id="modal-mapel-tambah"
            class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300"
                id="modal-mapel-tambah-content">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-indigo-50/50 rounded-t-2xl">
                    <h3 class="text-xl font-bold text-indigo-800">Tambah Mapel</h3>
                    <button type="button" onclick="tutupModal('modal-mapel-tambah')"
                        class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_jadwal.php" method="POST" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" required placeholder="Cth: Matematika Dasar"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 outline-none bg-gray-50 focus:bg-white text-gray-800 font-bold">
                    </div>
                    <button type="submit" name="tambah_mapel"
                        class="w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">Simpan
                        Data</button>
                </form>
            </div>
        </div>

        <div id="modal-mapel-edit"
            class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl transform scale-95 transition-transform duration-300"
                id="modal-mapel-edit-content">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-amber-50/50 rounded-t-2xl">
                    <h3 class="text-xl font-bold text-amber-800">Edit Mapel</h3>
                    <button type="button" onclick="tutupModal('modal-mapel-edit')"
                        class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_jadwal.php" method="POST" class="p-6">
                    <input type="hidden" name="id_mapel" id="input_edit_id_mapel">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" id="input_edit_nama_mapel" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white text-gray-800 font-bold">
                    </div>
                    <button type="submit" name="edit_mapel"
                        class="w-full px-4 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">Update
                        Data</button>
                </form>
            </div>
        </div>

        <div id="modal-jadwal-tambah"
            class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300"
                id="modal-jadwal-tambah-content">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-teal-50/50 rounded-t-2xl">
                    <h3 class="text-xl font-bold text-teal-800">Tambah Jadwal Mengajar</h3>
                    <button type="button" onclick="tutupModal('modal-jadwal-tambah')"
                        class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_jadwal.php" method="POST" class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Guru / Pengajar</label>
                            <select name="id_pengajar" id="select-guru-tambah" required class="w-full">
                                <option value="">-- Pilih Guru --</option>
                                <?php
                                $gurus = mysqli_query($koneksi, "SELECT id_pengajar, nama_pengajar FROM pengajar");
                                while ($g = mysqli_fetch_array($gurus)) {
                                    echo "<option value='" . $g['id_pengajar'] . "'>" . $g['nama_pengajar'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Pelajaran</label>
                            <select name="id_mapel" id="select-mapel-tambah" required class="w-full">
                                <option value="">-- Pilih Mapel --</option>
                                <?php
                                $mapels = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran");
                                while ($m = mysqli_fetch_array($mapels)) {
                                    echo "<option value='" . $m['id_mapel'] . "'>" . $m['nama_mapel'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas yang Diajar</label>
                            <input type="text" name="kelas" required placeholder="Cth: 10"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-teal-500 outline-none bg-gray-50 focus:bg-white font-bold text-teal-700">
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" name="tambah_jadwal"
                            class="w-full px-4 py-3 bg-teal-600 text-white font-bold rounded-xl hover:bg-teal-700 transition shadow-lg shadow-teal-500/30">Tugaskan
                            Guru</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="modal-jadwal-edit"
            class="fixed inset-0 bg-gray-900/60 z-[60] backdrop-blur-sm flex justify-center items-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl transform scale-95 transition-transform duration-300"
                id="modal-jadwal-edit-content">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-amber-50/50 rounded-t-2xl">
                    <h3 class="text-xl font-bold text-amber-800">Edit Jadwal Mengajar</h3>
                    <button type="button" onclick="tutupModal('modal-jadwal-edit')"
                        class="text-gray-400 hover:text-red-500 p-1.5 rounded-lg transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form action="kelola_jadwal.php" method="POST" class="p-6">
                    <input type="hidden" name="id_pengampu" id="input_edit_id_pengampu">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Guru / Pengajar</label>
                            <select name="id_pengajar" id="input_edit_id_pengajar" required class="w-full">
                                <?php
                                $gurus_edit = mysqli_query($koneksi, "SELECT id_pengajar, nama_pengajar FROM pengajar");
                                while ($g = mysqli_fetch_array($gurus_edit)) {
                                    echo "<option value='" . $g['id_pengajar'] . "'>" . $g['nama_pengajar'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Pelajaran</label>
                            <select name="id_mapel" id="input_edit_id_mapel" required class="w-full">
                                <?php
                                $mapels_edit = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran");
                                while ($m = mysqli_fetch_array($mapels_edit)) {
                                    echo "<option value='" . $m['id_mapel'] . "'>" . $m['nama_mapel'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas yang Diajar</label>
                            <input type="text" name="kelas" id="input_edit_kelas_jadwal" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 outline-none bg-gray-50 focus:bg-white font-bold text-amber-700">
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="submit" name="edit_jadwal"
                            class="w-full px-4 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition shadow-lg shadow-amber-500/30">Update
                            Jadwal</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Init DataTables
        $('#tabel-mapel').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [{
                orderable: false,
                targets: 2
            }],
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ]
        });

        $('#tabel-jadwal').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            columnDefs: [{
                orderable: false,
                targets: 4
            }],
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ]
        });

        // ----------------------------------------
        // INISIALISASI SELECT2 UNTUK MODAL TAMBAH
        // ----------------------------------------
        $('#select-guru-tambah, #select-mapel-tambah').select2({
            dropdownParent: $('#modal-jadwal-tambah'), // Supaya dropdown tidak ketutup Modal
            width: '100%',
            placeholder: "Ketik untuk mencari..."
        });

        // ----------------------------------------
        // INISIALISASI SELECT2 UNTUK MODAL EDIT
        // ----------------------------------------
        $('#input_edit_id_pengajar, #input_edit_id_mapel').select2({
            dropdownParent: $('#modal-jadwal-edit'),
            width: '100%'
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

    function bukaModalMapelEdit(id, nama) {
        document.getElementById('input_edit_id_mapel').value = id;
        document.getElementById('input_edit_nama_mapel').value = nama;
        bukaModal('modal-mapel-edit');
    }

    // Fungsi bukaModalJadwalEdit sudah diupdate untuk Select2
    function bukaModalJadwalEdit(id_pengampu, id_pengajar, id_mapel, kelas) {
        document.getElementById('input_edit_id_pengampu').value = id_pengampu;

        // Update Select2 Guru & Mapel via jQuery lalu panggil .trigger('change')
        $('#input_edit_id_pengajar').val(id_pengajar).trigger('change');
        $('#input_edit_id_mapel').val(id_mapel).trigger('change');

        document.getElementById('input_edit_kelas_jadwal').value = kelas;
        bukaModal('modal-jadwal-edit');
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