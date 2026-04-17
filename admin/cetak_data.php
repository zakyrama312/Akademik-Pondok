<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || !in_array($_SESSION['role'], ['admin', 'pimpinan'])) {
    die("Akses Ditolak.");
}
require_once '../koneksi.php';

$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'santri';
$format = isset($_GET['format']) ? $_GET['format'] : 'pdf';

if ($jenis == 'santri') {
    $judul = "Laporan Data Induk Santri";
    $query = "SELECT s.*, w.nama_ayah FROM santri s LEFT JOIN wali_santri w ON s.id_wali=w.id_wali ORDER BY s.kelas ASC, s.nama_santri ASC";
} elseif ($jenis == 'wali') {
    $judul = "Laporan Data Wali Santri";
    $query = "SELECT * FROM wali_santri ORDER BY nama_ayah ASC";
} else {
    $judul = "Laporan Data Tenaga Pengajar";
    $query = "
        SELECT p.id_pengajar, p.nama_pengajar, p.no_hp, 
               GROUP_CONCAT(DISTINCT m.nama_mapel SEPARATOR ', ') as mapel_diajar 
        FROM pengajar p 
        LEFT JOIN jadwal_pengampu j ON p.id_pengajar = j.id_pengajar 
        LEFT JOIN mata_pelajaran m ON j.id_mapel = m.id_mapel 
        GROUP BY p.id_pengajar 
        ORDER BY p.nama_pengajar ASC
    ";
}

$q_data = mysqli_query($koneksi, $query);

if ($format == 'excel') {
    $nama_file = str_replace(" ", "_", $judul) . "_" . date('Ymd') . ".xls";
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=$nama_file");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?php echo $judul; ?></title>
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
                margin-bottom: 20px;
                text-transform: uppercase;
            }

            /* Kembalikan style tabel yang di-reset oleh Tailwind */
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 20px;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                text-align: left;
                vertical-align: top;
            }

            table th {
                background-color: #f2f2f2 !important;
                text-align: center;
                font-weight: bold;
            }

            .text-center {
                text-align: center !important;
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

    <div class="judul-laporan"><?php echo $judul; ?></div>

    <table border="1">
        <?php if ($jenis == 'santri'): ?>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">NIS</th>
                    <th width="30%">Nama Lengkap Santri</th>
                    <th width="10%">Kelas</th>
                    <th width="25%">Nama Wali</th>
                    <th width="15%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($d = mysqli_fetch_assoc($q_data)): ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td class="text-center" style="mso-number-format:'\@';"><?php echo $d['nis']; ?></td>
                        <td><?php echo htmlspecialchars($d['nama_santri']); ?></td>
                        <td class="text-center"><?php echo $d['kelas']; ?></td>
                        <td><?php echo htmlspecialchars($d['nama_ayah'] ?: '-'); ?></td>
                        <td class="text-center"><?php echo $d['status_aktif']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

        <?php elseif ($jenis == 'wali'): ?>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Ayah</th>
                    <th width="25%">Nama Ibu</th>
                    <th width="15%">No HP / WA</th>
                    <th width="30%">Alamat Lengkap</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($d = mysqli_fetch_assoc($q_data)): ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($d['nama_ayah']); ?></td>
                        <td><?php echo htmlspecialchars($d['nama_ibu']); ?></td>
                        <td style="mso-number-format:'\@';"><?php echo htmlspecialchars($d['no_hp']); ?></td>
                        <td><?php echo htmlspecialchars($d['alamat']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

        <?php elseif ($jenis == 'pengajar'): ?>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Nama Tenaga Pengajar</th>
                    <th width="40%">Mata Pelajaran (Diampu)</th>
                    <th width="25%">No HP / Kontak</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($d = mysqli_fetch_assoc($q_data)): ?>
                    <tr>
                        <td class="text-center"><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($d['nama_pengajar']); ?></td>
                        <td><?php echo htmlspecialchars($d['mapel_diajar'] ?: 'Belum ada jadwal'); ?></td>
                        <td style="mso-number-format:'\@';"><?php echo htmlspecialchars($d['no_hp']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        <?php endif; ?>
    </table>

    <?php if ($format == 'pdf'): ?>
        <div style="margin-top: 50px; text-align: right; float: right; width: 30%;">
            <p style="margin-bottom: 70px;">Mengetahui,<br>Kepala Sekolah / Pimpinan</p>
            <p style="font-weight: bold; text-decoration: underline;">........................................</p>
        </div>
    <?php endif; ?>

</body>

</html>