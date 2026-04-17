<?php
session_start();
// Hanya Admin dan Pimpinan yang boleh masuk!
if ($_SESSION['status'] != "sudah_login" || !in_array($_SESSION['role'], ['admin', 'pimpinan'])) {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Ambil nilai filter jika ada
$tgl_mulai = isset($_GET['mulai']) ? $_GET['mulai'] : date('Y-m-01'); // Default awal bulan
$tgl_selesai = isset($_GET['selesai']) ? $_GET['selesai'] : date('Y-m-t'); // Default akhir bulan

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
        border-color: #9333ea;
        box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.25rem 1rem 0.25rem 0.5rem;
        outline: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #9333ea !important;
        color: white !important;
        border: 1px solid #9333ea !important;
        border-radius: 0.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem;
        margin: 0 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #faf5ff !important;
        color: #7e22ce !important;
        border: 1px solid #e9d5ff !important;
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

        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden mb-6">
            <div class="p-6 bg-purple-50/50 border-b border-purple-100">
                <h2 class="text-xl font-extrabold text-purple-800"><i class="fas fa-file-invoice-dollar mr-2"></i>
                    Laporan Keuangan & Pembayaran</h2>
                <p class="text-sm text-purple-600/80 mt-1">Filter data berdasarkan tanggal masuknya pembayaran (Bukti
                    Transfer/Bayar).</p>
            </div>

            <div class="p-6">
                <form method="GET" action="laporan.php" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Dari Tanggal</label>
                        <input type="date" name="mulai" value="<?php echo $tgl_mulai; ?>" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 outline-none bg-gray-50 focus:bg-white font-bold text-gray-700">
                    </div>
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sampai Tanggal</label>
                        <input type="date" name="selesai" value="<?php echo $tgl_selesai; ?>" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-purple-500 outline-none bg-gray-50 focus:bg-white font-bold text-gray-700">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl font-bold transition shadow-lg shadow-purple-500/30 flex items-center gap-2">
                            <i class="fas fa-search"></i> Filter Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-6">
            <a href="cetak_laporan_keuangan.php?mulai=<?php echo $tgl_mulai; ?>&selesai=<?php echo $tgl_selesai; ?>&format=pdf"
                target="_blank"
                class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-red-500/30 flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Cetak / PDF
            </a>
            <a href="cetak_laporan_keuangan.php?mulai=<?php echo $tgl_mulai; ?>&selesai=<?php echo $tgl_selesai; ?>&format=excel"
                class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800">Preview Data (<?php echo date('d/m/Y', strtotime($tgl_mulai)); ?>
                    s.d <?php echo date('d/m/Y', strtotime($tgl_selesai)); ?>)</h3>
            </div>
            <div class="overflow-x-auto w-full">
                <table id="tabel-laporan" class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold w-16 text-center">No</th>
                            <th class="p-4 font-semibold">Tgl Bayar</th>
                            <th class="p-4 font-semibold">Nama Santri</th>
                            <th class="p-4 font-semibold">Rincian Tagihan</th>
                            <th class="p-4 font-semibold text-right">Nominal</th>
                            <th class="p-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <?php
                        $no = 1;
                        $total_pemasukan = 0;
                        $query = "
                            SELECT p.tanggal_bayar, p.status_acc, t.jenis_tagihan, t.nominal, t.bulan, t.tahun, s.nama_santri, s.kelas 
                            FROM pembayaran p 
                            JOIN tagihan t ON p.id_tagihan = t.id_tagihan 
                            JOIN santri s ON t.id_santri = s.id_santri 
                            WHERE DATE(p.tanggal_bayar) BETWEEN '$tgl_mulai' AND '$tgl_selesai'
                            ORDER BY p.tanggal_bayar ASC
                        ";
                        $q_laporan = mysqli_query($koneksi, $query);

                        if (mysqli_num_rows($q_laporan) > 0) {
                            while ($d = mysqli_fetch_array($q_laporan)):
                                if (in_array(strtolower($d['status_acc']), ['lunas', 'disetujui', 'diterima', 'selesai'])) {
                                    $total_pemasukan += $d['nominal'];
                                }
                        ?>
                                <tr class="hover:bg-purple-50/20 border-b border-gray-50">
                                    <td class="p-4 text-center text-gray-500"><?php echo $no++; ?></td>
                                    <td class="p-4 text-gray-600"><?php echo date('d/m/Y', strtotime($d['tanggal_bayar'])); ?>
                                    </td>
                                    <td class="p-4 font-bold text-gray-800"><?php echo htmlspecialchars($d['nama_santri']); ?>
                                        <span class="text-xs text-gray-400 font-normal"> (Kls <?php echo $d['kelas']; ?>)</span>
                                    </td>
                                    <td class="p-4 text-gray-600">
                                        <?php echo $d['jenis_tagihan'] . ' ' . ($d['bulan'] ? $d['bulan'] . ' ' : '') . $d['tahun']; ?>
                                    </td>
                                    <td class="p-4 text-right font-bold">Rp
                                        <?php echo number_format($d['nominal'], 0, ',', '.'); ?></td>
                                    <td class="p-4 text-center">
                                        <?php
                                        $st = strtolower($d['status_acc']);
                                        if (in_array($st, ['lunas', 'disetujui', 'diterima', 'selesai'])) echo '<span class="text-emerald-500 font-bold"><i class="fas fa-check-circle"></i> Selesai</span>';
                                        elseif ($st == 'pending' || $st == 'menunggu') echo '<span class="text-amber-500 font-bold"><i class="fas fa-clock"></i> Pending</span>';
                                        else echo '<span class="text-red-500 font-bold"><i class="fas fa-times-circle"></i> Ditolak</span>';
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-emerald-50 border-t-2 border-emerald-200 font-extrabold text-emerald-800">
                            <th colspan="4" class="p-4 text-right uppercase tracking-wider text-xs">Total Pemasukan
                                (Status Selesai):</th>
                            <th class="p-4 text-right text-lg">Rp
                                <?php echo number_format($total_pemasukan, 0, ',', '.'); ?></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabel-laporan').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                emptyTable: "Tidak ada transaksi pembayaran pada rentang tanggal ini."
            },
            order: [
                [1, 'asc']
            ], // Default urutkan dari Tgl Bayar terlama
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