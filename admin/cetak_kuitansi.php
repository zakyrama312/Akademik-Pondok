<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || !in_array($_SESSION['role'], ['admin', 'pimpinan'])) {
    die("Akses Ditolak.");
}
require_once '../koneksi.php';

// Ambil ID Pembayaran dari URL
$id_pembayaran = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_pembayaran == 0) {
    die("Data transaksi tidak valid.");
}

// Query tarik data pembayaran, tagihan, santri, dan wali santri
$query = "
    SELECT p.tanggal_bayar, p.status_acc,
           t.jenis_tagihan, t.nominal, t.bulan, t.tahun,
           s.nama_santri, w.nama_ayah, w.alamat
    FROM pembayaran p
    JOIN tagihan t ON p.id_tagihan = t.id_tagihan
    JOIN santri s ON t.id_santri = s.id_santri
    LEFT JOIN wali_santri w ON s.id_wali = w.id_wali
    WHERE p.id_pembayaran = $id_pembayaran
";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data transaksi tidak ditemukan.");
}

// Fungsi konversi tanggal ke format Indonesia
function tgl_indo($tanggal)
{
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $split = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Hitung Jatuh Tempo (Contoh otomatis: 30 hari setelah tanggal bayar, atau sesuaikan logika pondok)
$jatuh_tempo = date('Y-m-d', strtotime($data['tanggal_bayar'] . ' + 30 days'));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi _ <?php echo htmlspecialchars($data['nama_santri']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white;
                padding: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-4 md:p-8 text-slate-800" onload="window.print()">

    <div class="no-print max-w-4xl mx-auto mb-6 flex justify-end gap-3">
        <button onclick="window.print()"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl shadow-md font-bold flex items-center gap-2 transition">
            <i class="fas fa-print"></i> Cetak Kuitansi
        </button>
        <button onclick="window.close()"
            class="bg-white hover:bg-gray-50 text-slate-600 px-5 py-2 rounded-xl shadow-md font-bold border border-gray-200 transition">
            Tutup
        </button>
    </div>

    <div
        class="max-w-4xl mx-auto bg-white p-12 shadow-sm border border-gray-100 min-h-[500px] flex flex-col justify-between relative">

        <div>
            <div class="flex justify-between items-start border-b border-gray-100 pb-6 mb-8">
                <div>
                    <h1 class="text-4xl font-black tracking-wider text-slate-900">KUITANSI</h1>
                    <p class="text-sm font-bold tracking-widest text-slate-500 mt-1">PEMBAYARAN</p>
                </div>
                <div class="flex items-center gap-1 text-right">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-800">PP. AL FALAH SALAFIYAH</h2>
                        <p class="text-sm font-bold text-slate-600 tracking-wide">JATIROKEH SONGGOM</p>
                    </div>
                    <div class="w-16 h-16  ">
                        <img src="../uploads/img/Logo_AlFalah.png" alt="Logo" class="w-16 h-16 object-contain">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8 mb-10 text-[13px]">
                <table class="w-full">
                    <tr class="align-top">
                        <td class="w-24 py-1 font-bold text-slate-700 uppercase tracking-wider">Nama</td>
                        <td class="w-4 py-1 text-center">:</td>
                        <td class="py-1 font-semibold text-slate-900">
                            <?php echo htmlspecialchars($data['nama_santri']); ?></td>
                    </tr>
                    <tr class="align-top">
                        <td class="py-1 font-bold text-slate-700 uppercase tracking-wider">Wali Santri</td>
                        <td class="text-center">:</td>
                        <td class="py-1 text-slate-800"><?php echo htmlspecialchars($data['nama_ayah'] ?? '-'); ?></td>
                    </tr>
                    <tr class="align-top">
                        <td class="py-1 font-bold text-slate-700 uppercase tracking-wider">Alamat</td>
                        <td class="text-center">:</td>
                        <td class="py-1 text-slate-600 leading-relaxed">
                            <?php echo htmlspecialchars($data['alamat'] ?? '-'); ?></td>
                    </tr>
                </table>

                <div class="flex justify-end">
                    <table class="w-2/3">
                        <tr>
                            <td class="py-1 font-semibold text-slate-600">Tanggal Dibuat</td>
                            <td class="w-4 text-center">:</td>
                            <td class="py-1 text-right font-medium text-slate-900">
                                <?php echo tgl_indo($data['tanggal_bayar']); ?></td>
                        </tr>
                        <tr>
                            <td class="py-1 text-slate-500">Jatuh Tempo</td>
                            <td class="text-center">:</td>
                            <td class="py-1 text-right text-slate-600"><?php echo tgl_indo($jatuh_tempo); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <table class="w-full text-left border-collapse text-[13px]">
                <thead>
                    <tr class="border-b-2 border-slate-900 text-slate-700 font-bold uppercase tracking-wider">
                        <th class="pb-3 w-7/12">Jenis Pembayaran</th>
                        <th class="pb-3 w-1/12 text-center">Jumlah</th>
                        <th class="pb-3 w-2/12 text-right">Harga Satuan</th>
                        <th class="pb-3 w-2/12 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-slate-800">
                    <tr class="font-medium">
                        <td class="py-4 font-bold text-slate-900 uppercase">
                            <?php echo htmlspecialchars($data['jenis_tagihan']); ?>
                            <?php echo !empty($data['bulan']) ? 'BULAN ' . strtoupper($data['bulan']) : ''; ?>
                        </td>
                        <td class="py-4 text-center">1</td>
                        <td class="py-4 text-right">Rp <?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
                        <td class="py-4 text-right font-bold text-slate-900">Rp
                            <?php echo number_format($data['nominal'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td class="py-4"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-12">
            <div class="flex justify-between items-end border-t border-slate-300 pt-4">
                <div class="text-[11px] text-slate-600 max-w-sm leading-relaxed">
                    <p class="font-bold text-slate-800 text-xs mb-1">Metode Pembayaran</p>
                    <p>BRI a.n PONPES PENDIDIKAN AL FALAH SALAFY</p>
                    <p class="text-sm font-black text-slate-950 tracking-wide my-0.5">0676 0100 1198 300</p>
                    <p class="italic text-slate-500">*Mohon konfirmasi setelah bertransaksi</p>
                </div>

                <div class="w-5/12 flex flex-col items-end">
                    <div class="flex justify-between w-full text-sm border-b border-gray-200 pb-3 mb-12 font-bold">
                        <span class="text-slate-500 uppercase tracking-wider">TOTAL</span>
                        <span class="text-slate-900 text-base">Rp
                            <?php echo number_format($data['nominal'], 0, ',', '.'); ?></span>
                    </div>

                    <div class="text-center text-[12px] pr-6">
                        <p class="text-slate-700 font-semibold mb-16">Hormat Kami</p>
                        <p class="font-bold text-slate-900 uppercase tracking-wide">ADMIN</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>