<?php
session_start();
if($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "pengajar"){
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Ambil ID Pengajar dari User yang sedang login
$id_user_login = $_SESSION['id_user'];
$query_guru = mysqli_query($koneksi, "SELECT * FROM pengajar WHERE id_user = '$id_user_login'");
$data_guru = mysqli_fetch_assoc($query_guru);

// Jika admin belum membuatkan profil guru untuk user ini, hentikan akses
if(!$data_guru) {
    die("<div style='padding:20px; font-family:sans-serif;'>Maaf, profil data guru Anda belum di-set oleh Admin. Silakan hubungi Admin. <a href='../logout.php'>Kembali Login</a></div>");
}

$id_pengajar = $data_guru['id_pengajar'];

// ========================================================
// 1. PROSES SIMPAN NILAI MASSAL
// ========================================================
if(isset($_POST['simpan_semua_nilai'])) {
    $ta = mysqli_real_escape_string($koneksi, $_POST['tahun_ajaran']);
    $smt = mysqli_real_escape_string($koneksi, $_POST['semester']);
    $id_mapel = (int)$_POST['id_mapel'];
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $id_pengampu = (int)$_POST['id_pengampu']; // Untuk redirect

    // $_POST['nilai'] adalah Array dengan key = id_santri dan value = nilai_angka
    if(isset($_POST['nilai']) && is_array($_POST['nilai'])) {
        foreach($_POST['nilai'] as $id_santri => $nilai_angka) {
            if($nilai_angka !== "") { // Hanya proses jika form nilainya diisi
                $id_santri = (int)$id_santri;
                $nilai_angka = (int)$nilai_angka;

                // Cek apakah nilai sudah ada di database?
                $cek = mysqli_query($koneksi, "SELECT id_nilai FROM nilai_akademik WHERE id_santri=$id_santri AND id_mapel=$id_mapel AND semester='$smt' AND tahun_ajaran='$ta'");
                
                if(mysqli_num_rows($cek) > 0) {
                    // UPDATE
                    mysqli_query($koneksi, "UPDATE nilai_akademik SET nilai_angka=$nilai_angka WHERE id_santri=$id_santri AND id_mapel=$id_mapel AND semester='$smt' AND tahun_ajaran='$ta'");
                } else {
                    // INSERT
                    mysqli_query($koneksi, "INSERT INTO nilai_akademik (id_santri, id_mapel, semester, tahun_ajaran, nilai_angka) VALUES ($id_santri, $id_mapel, '$smt', '$ta', $nilai_angka)");
                }
            }
        }
        $_SESSION['pesan_sukses'] = "Alhamdulillah! Nilai satu kelas berhasil disimpan.";
    }
    
    // Redirect ke halaman yang sama persis
    header("location:input_nilai.php?ta=$ta&smt=$smt&jadwal=$id_pengampu");
    exit;
}

// ========================================================
// 2. SETTING FILTER (GET)
// ========================================================
$ta_aktif = isset($_GET['ta']) ? $_GET['ta'] : '2025/2026';
$smt_aktif = isset($_GET['smt']) ? $_GET['smt'] : 'Genap';
$jadwal_aktif = isset($_GET['jadwal']) ? (int)$_GET['jadwal'] : 0;

include '../components/header.php';
include '../components/sidebar_pengajar.php';
?>

<div class="flex-1 flex flex-col h-screen overflow-hidden">
    <?php include '../components/navbar_pengajar.php'; ?>

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

        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden mb-6">
            <div
                class="p-5 bg-emerald-50/50 border-b border-emerald-100 flex flex-col lg:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-lg font-bold text-emerald-800"><i class="fas fa-filter mr-2"></i> Pilih Kelas & Mata
                        Pelajaran</h2>
                    <p class="text-sm text-emerald-600/80 mt-1">Tentukan kelas yang ingin Anda berikan nilai.</p>
                </div>

                <form method="GET" action="input_nilai.php" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <select name="jadwal" required
                        class="flex-1 lg:w-64 px-4 py-2 rounded-xl border border-emerald-200 text-emerald-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="">-- Pilih Kelas Anda --</option>
                        <?php 
                        $q_jadwal = mysqli_query($koneksi, "
                            SELECT j.id_pengampu, j.kelas, m.nama_mapel 
                            FROM jadwal_pengampu j 
                            JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel 
                            WHERE j.id_pengajar = $id_pengajar
                        ");
                        while($j = mysqli_fetch_array($q_jadwal)) {
                            $selected = ($jadwal_aktif == $j['id_pengampu']) ? 'selected' : '';
                            echo "<option value='".$j['id_pengampu']."' $selected>".$j['kelas']." - ".$j['nama_mapel']."</option>";
                        }
                        ?>
                    </select>

                    <select name="ta"
                        class="px-4 py-2 rounded-xl border border-emerald-200 text-emerald-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="2024/2025" <?= $ta_aktif == '2024/2025' ? 'selected' : '' ?>>2024/2025</option>
                        <option value="2025/2026" <?= $ta_aktif == '2025/2026' ? 'selected' : '' ?>>2025/2026</option>
                    </select>
                    <select name="smt"
                        class="px-4 py-2 rounded-xl border border-emerald-200 text-emerald-800 font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="Ganjil" <?= $smt_aktif == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                        <option value="Genap" <?= $smt_aktif == 'Genap' ? 'selected' : '' ?>>Genap</option>
                    </select>
                    <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl font-bold transition shadow-md shadow-emerald-500/30">
                        Buka Form
                    </button>
                </form>
            </div>
        </div>

        <?php 
        if ($jadwal_aktif > 0): 
            // Ambil info mapel dan kelas dari id_pengampu
            $q_info = mysqli_query($koneksi, "SELECT j.kelas, j.id_mapel, m.nama_mapel FROM jadwal_pengampu j JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel WHERE j.id_pengampu = $jadwal_aktif");
            $info = mysqli_fetch_assoc($q_info);
            
            if($info):
                $kelas_aktif = $info['kelas'];
                $id_mapel_aktif = $info['id_mapel'];
                $nama_mapel_aktif = $info['nama_mapel'];
        ?>

        <form action="input_nilai.php" method="POST">
            <input type="hidden" name="tahun_ajaran" value="<?php echo $ta_aktif; ?>">
            <input type="hidden" name="semester" value="<?php echo $smt_aktif; ?>">
            <input type="hidden" name="id_mapel" value="<?php echo $id_mapel_aktif; ?>">
            <input type="hidden" name="kelas" value="<?php echo $kelas_aktif; ?>">
            <input type="hidden" name="id_pengampu" value="<?php echo $jadwal_aktif; ?>">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Form Nilai: <?php echo $nama_mapel_aktif; ?></h2>
                        <p class="text-sm text-gray-500">Kelas <span
                                class="font-bold text-emerald-600"><?php echo $kelas_aktif; ?></span> |
                            <?php echo $smt_aktif; ?> (<?php echo $ta_aktif; ?>)</p>
                    </div>
                    <button type="submit" name="simpan_semua_nilai"
                        class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 transition flex items-center gap-2 shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-save"></i> Simpan Nilai Kelas
                    </button>
                </div>

                <div class="overflow-x-auto w-full p-4">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-100 text-gray-500 text-xs uppercase tracking-wider rounded-lg">
                                <th class="p-4 font-semibold w-16 text-center rounded-l-lg">No</th>
                                <th class="p-4 font-semibold w-40 text-center">NIS</th>
                                <th class="p-4 font-semibold">Nama Santri</th>
                                <th class="p-4 font-semibold w-48 text-center rounded-r-lg">Nilai Akademik (0-100)</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            <?php 
                            $no = 1;
                            // Query semua santri di kelas tsb, gabungkan (LEFT JOIN) dengan tabel nilai jika sudah ada nilainya
                            $query_santri = mysqli_query($koneksi, "
                                SELECT s.id_santri, s.nis, s.nama_santri, n.nilai_angka 
                                FROM santri s 
                                LEFT JOIN nilai_akademik n ON s.id_santri = n.id_santri 
                                     AND n.id_mapel = $id_mapel_aktif 
                                     AND n.semester = '$smt_aktif' 
                                     AND n.tahun_ajaran = '$ta_aktif'
                                WHERE s.kelas = '$kelas_aktif' AND s.status_aktif = 'Aktif'
                                ORDER BY s.nama_santri ASC
                            ");
                            
                            if(mysqli_num_rows($query_santri) > 0) {
                                while($d = mysqli_fetch_array($query_santri)): 
                            ?>
                            <tr class="hover:bg-emerald-50/20 transition-colors border-b border-gray-50">
                                <td class="p-4 text-center text-gray-500 font-medium">#<?php echo $no++; ?></td>
                                <td class="p-4 text-center font-mono text-gray-500 text-xs"><?php echo $d['nis']; ?>
                                </td>
                                <td class="p-4 font-bold text-gray-800">
                                    <?php echo htmlspecialchars($d['nama_santri']); ?></td>
                                <td class="p-4 text-center">
                                    <input type="number" min="0" max="100" name="nilai[<?php echo $d['id_santri']; ?>]"
                                        value="<?php echo $d['nilai_angka'] !== null ? $d['nilai_angka'] : ''; ?>"
                                        placeholder="0"
                                        class="w-24 px-3 py-2 text-center rounded-lg border <?php echo $d['nilai_angka'] !== null ? 'border-emerald-300 bg-emerald-50 text-emerald-800' : 'border-gray-300 bg-gray-50 focus:bg-white'; ?> focus:border-emerald-500 outline-none font-bold transition-all shadow-inner">
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            } else {
                                echo '<tr><td colspan="4" class="p-8 text-center text-gray-400 italic">Belum ada data santri di kelas ini.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <?php if(mysqli_num_rows($query_santri) > 0): ?>
                <div class="p-6 border-t border-gray-100 bg-gray-50 text-right">
                    <button type="submit" name="simpan_semua_nilai"
                        class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-save mr-2"></i> Simpan Semua Nilai
                    </button>
                </div>
                <?php endif; ?>

            </div>
        </form>

        <?php 
            endif;
        else: 
        ?>
        <div
            class="flex flex-col items-center justify-center h-64 bg-white rounded-2xl border border-dashed border-emerald-200">
            <div
                class="w-16 h-16 bg-emerald-50 text-emerald-400 rounded-full flex items-center justify-center text-3xl mb-4">
                <i class="fas fa-hand-pointer"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Silakan Pilih Kelas</h3>
            <p class="text-sm text-gray-500 text-center mt-1">Gunakan panel di atas untuk memilih <br>kelas dan mata
                pelajaran yang ingin Anda nilai.</p>
        </div>
        <?php endif; ?>

    </main>
</div>

<script>
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