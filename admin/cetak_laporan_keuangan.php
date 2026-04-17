<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || !in_array($_SESSION['role'], ['admin', 'pimpinan'])) {
    die("Akses Ditolak.");
}
require_once '../koneksi.php';

$tgl_mulai = isset($_GET['mulai']) ? $_GET['mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['selesai']) ? $_GET['selesai'] : date('Y-m-t');
$format = isset($_GET['format']) ? $_GET['format'] : 'pdf';

if ($format == 'excel') {
    $nama_file = "Laporan_Keuangan_" . $tgl_mulai . "_sd_" . $tgl_selesai . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$nama_file");
    header("Pragma: no-cache");
    header("Expires: 0");
}

$query = "
    SELECT p.tanggal_bayar, p.status_acc, t.jenis_tagihan, t.nominal, t.bulan, t.tahun, s.nama_santri, s.kelas 
    FROM pembayaran p 
    JOIN tagihan t ON p.id_tagihan = t.id_tagihan 
    JOIN santri s ON t.id_santri = s.id_santri 
    WHERE DATE(p.tanggal_bayar) BETWEEN '$tgl_mulai' AND '$tgl_selesai'
    ORDER BY p.tanggal_bayar ASC
";
$q_laporan = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <?php if ($format == 'pdf'): ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #333;
                margin: 0;
                padding: 20px;
            }

            .judul-laporan {
                text-align: center;
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 5px;
                text-transform: uppercase;
            }

            .periode {
                text-align: center;
                margin-bottom: 20px;
                font-style: italic;
            }

            /* Kembalikan style tabel yang di-reset oleh Tailwind */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                text-align: left;
            }

            table th {
                background-color: #f2f2f2 !important;
                text-align: center;
                font-weight: bold;
            }

            .text-center {
                text-align: center !important;
            }

            .text-right {
                text-align: right !important;
            }

            .total-row {
                font-weight: bold;
                background-color: #e6ffe6 !important;
            }

            @media print {
                .btn-print {
                    display: none;
                }
            }
        </style>
    <?php endif; ?>
</head>

<body <?php echo ($format == 'pdf') ? 'onload="window.print()"' : ''; ?>>

    <?php if ($format == 'pdf'): ?>
        <div style="text-align: right; margin-bottom: 10px;" class="btn-print">
            <button onclick="window.print()"
                style="padding: 10px 20px; background: #16a34a; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Cetak
                / Simpan PDF</button>
        </div>

        <div class="flex items-center justify-center pb-6 mb-8 border-b-4 border-double border-green-600">
            <div class="w-24 h-24 mr-6 shrink-0">
                <img src="../uploads/img/Logo_AlFalah.png" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col text-left">
                <span class="font-bold text-2xl mb-1 text-green-600"
                    style="font-family: 'Amiri', 'Traditional Arabic', serif;">مُؤَسَّسَةُ الفَلَاحِ لِلتَّرْبِيَةِ
                    الإِسْلَامِيَّةِ</span>
                <span class="font-semibold text-lg text-slate-800 tracking-wide">YAYASAN PONDOK PESANTREN &
                    PENDIDIKAN</span>
                <span class="font-extrabold text-3xl leading-tight text-green-600">AL FALAH SALAFIYAH JATIROKEH</span>
                <span class="text-xs text-slate-600 mt-1">Jl. Raya Brebes - Purwokerto Desa Jatirokeh Kecamatan Songgom Kab.
                    Brebes 52266</span>
                <span class="text-xs text-slate-600">email : alfalahsalafyonline@gmail.com | telp/wa : 0857 2898 1547</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="judul-laporan">Laporan Penerimaan Pembayaran</div>
    <div class="periode">Periode: <?php echo date('d/m/Y', strtotime($tgl_mulai)); ?> s.d
        <?php echo date('d/m/Y', strtotime($tgl_selesai)); ?></div>

    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tgl Bayar</th>
                <th width="25%">Nama Santri</th>
                <th width="10%">Kelas</th>
                <th width="25%">Rincian Tagihan</th>
                <th width="15%">Nominal (Rp)</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total = 0;
            if (mysqli_num_rows($q_laporan) > 0) {
                while ($d = mysqli_fetch_array($q_laporan)) {
                    $nominal = $d['nominal'];
                    if (in_array(strtolower($d['status_acc']), ['lunas', 'disetujui', 'diterima', 'selesai'])) {
                        $total += $nominal;
                    }
            ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center"><?php echo date('d/m/Y', strtotime($d['tanggal_bayar'])); ?></td>
                        <td><?php echo htmlspecialchars($d['nama_santri']); ?></td>
                        <td class="text-center"><?php echo $d['kelas']; ?></td>
                        <td><?php echo $d['jenis_tagihan'] . ' ' . ($d['bulan'] ? $d['bulan'] . ' ' : '') . $d['tahun']; ?></td>
                        <td class="text-right">
                            <?php echo ($format == 'excel') ? $nominal : number_format($nominal, 0, ',', '.'); ?></td>
                        <td class="text-center"><?php echo $d['status_acc']; ?></td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            <?php } ?>

            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL PEMASUKAN DITERIMA:</td>
                <td class="text-right"><?php echo ($format == 'excel') ? $total : number_format($total, 0, ',', '.'); ?>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <?php if ($format == 'pdf'): ?>
        <div style="margin-top: 50px; text-align: right; float: right; width: 30%;">
            <p style="margin-bottom: 70px;">Mengetahui,<br>Kepala Sekolah / Pimpinan</p>
            <p style="font-weight: bold; text-decoration: underline;">........................................</p>
        </div>
    <?php endif; ?>

</body>

</html>