<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || !in_array($_SESSION['role'], ['admin', 'pimpinan'])) {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Tentukan jenis data yang sedang aktif (default: santri)
$jenis_aktif = isset($_GET['jenis']) ? $_GET['jenis'] : 'santri';

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

    table.dataTable.no-footer,
    table.dataTable thead th,
    table.dataTable thead td {
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<?php include '../components/sidebar.php'; ?>

<div class="flex-1 flex flex-col h-screen overflow-hidden">
    <?php include '../components/navbar.php'; ?>

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative bg-gray-50">

        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden mb-6">
            <div
                class="p-6 bg-blue-50/50 border-b border-blue-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-blue-800"><i class="fas fa-users-viewfinder mr-2"></i>
                        Laporan Master Data</h2>
                    <p class="text-sm text-blue-600/80 mt-1">Export data seluruh civitas akademika pondok pesantren.</p>
                </div>

                <div class="flex gap-3">
                    <a href="cetak_data.php?jenis=<?php echo $jenis_aktif; ?>&format=pdf" target="_blank"
                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-red-500/30 flex items-center gap-2">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </a>
                    <a href="cetak_data.php?jenis=<?php echo $jenis_aktif; ?>&format=excel"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>

            <div class="px-6 pt-4 border-b border-gray-100 flex gap-4 overflow-x-auto custom-scrollbar">
                <a href="laporan_data.php?jenis=santri"
                    class="pb-3 px-2 font-bold whitespace-nowrap transition border-b-2 <?php echo ($jenis_aktif == 'santri') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-500'; ?>">
                    <i class="fas fa-user-graduate mr-1"></i> Data Santri
                </a>
                <a href="laporan_data.php?jenis=wali"
                    class="pb-3 px-2 font-bold whitespace-nowrap transition border-b-2 <?php echo ($jenis_aktif == 'wali') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-500'; ?>">
                    <i class="fas fa-user-friends mr-1"></i> Data Wali Santri
                </a>
                <a href="laporan_data.php?jenis=pengajar"
                    class="pb-3 px-2 font-bold whitespace-nowrap transition border-b-2 <?php echo ($jenis_aktif == 'pengajar') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-500'; ?>">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> Data Pengajar
                </a>
            </div>

            <div class="overflow-x-auto w-full">
                <table id="tabel-master" class="w-full text-left whitespace-nowrap">
                    <?php if ($jenis_aktif == 'santri'): ?>
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 w-12 text-center">No</th>
                                <th class="p-4">NIS</th>
                                <th class="p-4">Nama Santri</th>
                                <th class="p-4">Kelas</th>
                                <th class="p-4">Wali Santri</th>
                                <th class="p-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            <?php
                            $no = 1;
                            $q = mysqli_query($koneksi, "SELECT s.*, w.nama_ayah FROM santri s LEFT JOIN wali_santri w ON s.id_wali=w.id_wali ORDER BY s.kelas ASC, s.nama_santri ASC");
                            while ($d = mysqli_fetch_array($q)): ?>
                                <tr class="border-b border-gray-50 hover:bg-blue-50/20">
                                    <td class="p-4 text-center"><?php echo $no++; ?></td>
                                    <td class="p-4 font-mono text-gray-500"><?php echo $d['nis']; ?></td>
                                    <td class="p-4 font-bold text-gray-800"><?php echo htmlspecialchars($d['nama_santri']); ?>
                                    </td>
                                    <td class="p-4 font-bold text-blue-600"><?php echo $d['kelas']; ?></td>
                                    <td class="p-4"><?php echo htmlspecialchars($d['nama_ayah'] ?: '-'); ?></td>
                                    <td class="p-4 text-center">
                                        <?php echo ($d['status_aktif'] == 'Aktif') ? '<span class="text-emerald-500 font-bold"><i class="fas fa-check"></i> Aktif</span>' : '<span class="text-red-500 font-bold">Lulus/Pindah</span>'; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    <?php elseif ($jenis_aktif == 'wali'): ?>
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 w-12 text-center">No</th>
                                <th class="p-4">Nama Ayah</th>
                                <th class="p-4">Nama Ibu</th>
                                <th class="p-4">No. HP (WhatsApp)</th>
                                <th class="p-4">Alamat Lengkap</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            <?php
                            $no = 1;
                            $q = mysqli_query($koneksi, "SELECT * FROM wali_santri ORDER BY nama_ayah ASC");
                            while ($d = mysqli_fetch_array($q)): ?>
                                <tr class="border-b border-gray-50 hover:bg-blue-50/20">
                                    <td class="p-4 text-center"><?php echo $no++; ?></td>
                                    <td class="p-4 font-bold text-gray-800"><?php echo htmlspecialchars($d['nama_ayah']); ?>
                                    </td>
                                    <td class="p-4"><?php echo htmlspecialchars($d['nama_ibu']); ?></td>
                                    <td class="p-4 text-emerald-600 font-bold"><?php echo htmlspecialchars($d['no_hp']); ?></td>
                                    <td class="p-4 whitespace-normal min-w-[200px]">
                                        <?php echo htmlspecialchars($d['alamat']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    <?php elseif ($jenis_aktif == 'pengajar'): ?>
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 w-12 text-center">No</th>
                                <th class="p-4">Nama Pengajar / Guru</th>
                                <th class="p-4">Mata Pelajaran (Diampu)</th>
                                <th class="p-4">No. HP</th>
                                <th class="p-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            <?php
                            $no = 1;
                            $query_guru = "
                                SELECT p.id_pengajar, p.nama_pengajar, p.no_hp, 
                                       GROUP_CONCAT(DISTINCT m.nama_mapel SEPARATOR ', ') as mapel_diajar 
                                FROM pengajar p 
                                LEFT JOIN jadwal_pengampu j ON p.id_pengajar = j.id_pengajar 
                                LEFT JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel 
                                GROUP BY p.id_pengajar 
                                ORDER BY p.nama_pengajar ASC
                            ";
                            $q = mysqli_query($koneksi, $query_guru);
                            while ($d = mysqli_fetch_array($q)): ?>
                                <tr class="border-b border-gray-50 hover:bg-blue-50/20">
                                    <td class="p-4 text-center"><?php echo $no++; ?></td>
                                    <td class="p-4 font-bold text-gray-800"><?php echo htmlspecialchars($d['nama_pengajar']); ?>
                                    </td>
                                    <td class="p-4 whitespace-normal">
                                        <?php if ($d['mapel_diajar']): ?>
                                            <span
                                                class="px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded text-xs font-bold leading-relaxed">
                                                <?php echo htmlspecialchars($d['mapel_diajar']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Belum ada jadwal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 font-medium"><?php echo htmlspecialchars($d['no_hp']); ?></td>
                                    <td class="p-4"><i class="fas fa-circle text-emerald-500 text-[10px] mr-1"></i> Aktif</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabel-master').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ]
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