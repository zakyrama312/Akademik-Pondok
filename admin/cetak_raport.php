<?php
session_start();
if ($_SESSION['status'] != "sudah_login" || $_SESSION['role'] != "admin") {
    header("location:../login.php?pesan=belum_login");
    exit;
}
require_once '../koneksi.php';

// Ambil parameter dari URL
$id_santri = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ta = isset($_GET['ta']) ? mysqli_real_escape_string($koneksi, $_GET['ta']) : '';
$smt = isset($_GET['smt']) ? mysqli_real_escape_string($koneksi, $_GET['smt']) : '';

if ($id_santri == 0 || $ta == '' || $smt == '') {
    die("Data tidak valid. Silakan kembali ke halaman sebelumnya.");
}

// 1. Ambil Data Santri
$q_santri = mysqli_query($koneksi, "
    SELECT s.*, w.nama_ayah 
    FROM santri s 
    LEFT JOIN wali_santri w ON s.id_wali = w.id_wali 
    WHERE s.id_santri = $id_santri
");
$santri = mysqli_fetch_assoc($q_santri);

if (!$santri) {
    die("Santri tidak ditemukan.");
}

// 2. Ambil Nilai Akademik
$q_nilai = mysqli_query($koneksi, "
    SELECT n.nilai_angka, m.nama_mapel 
    FROM nilai_akademik n 
    JOIN mata_pelajaran m ON n.id_mapel = m.id_mapel 
    WHERE n.id_santri = $id_santri AND n.semester = '$smt' AND n.tahun_ajaran = '$ta'
    ORDER BY m.nama_mapel ASC
");

// 3. Ambil Nilai Sikap & Keaktifan
$q_sikap = mysqli_query($koneksi, "
    SELECT * FROM nilai_sikap_keaktifan 
    WHERE id_santri = $id_santri AND semester = '$smt' AND tahun_ajaran = '$ta'
");
$sikap = mysqli_fetch_assoc($q_sikap);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport - <?php echo htmlspecialchars($santri['nama_santri']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Pengaturan Khusus untuk Print A4 */
        body {
            background-color: #f3f4f6;
            /* Abu-abu untuk layar */
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .kertas-a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Saat tombol print ditekan (mode cetak) */
        @media print {
            body {
                background-color: white;
            }

            .kertas-a4 {
                margin: 0;
                padding: 10mm;
                box-shadow: none;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            /* Menghindari baris tabel terpotong di tengah saat pindah halaman */
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body class="text-gray-800 font-serif text-sm">

    <div class="no-print fixed top-5 right-5 flex flex-col gap-3">
        <button onclick="window.print()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-full shadow-lg font-bold font-sans flex items-center justify-center gap-2 transition transform hover:scale-105">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-3 rounded-full shadow-lg font-bold font-sans flex items-center justify-center gap-2 transition border border-gray-300">
            <i class="fas fa-times"></i> Tutup Tab
        </button>
    </div>

    <div class="kertas-a4 relative">

        <div class="border-b-4 border-double border-gray-900 pb-4 mb-6 text-center">
            <h1 class="text-2xl font-extrabold uppercase tracking-wide">SMK Negeri 1 Slawi</h1>
            <h2 class="text-lg font-bold uppercase mt-1">Program Keahlian Rekayasa Perangkat Lunak (RPL)</h2>
            <p class="text-xs mt-2">Jl. KH. Agus Salim, Slawi, Kabupaten Tegal, Jawa Tengah 52419</p>
            <p class="text-xs">Website: smkn1slawi.sch.id | Email: info@smkn1slawi.sch.id</p>
        </div>

        <h3 class="text-center text-xl font-bold uppercase mb-8 underline underline-offset-4">Laporan Hasil Belajar
            Santri / Siswa</h3>

        <div class="flex justify-between mb-6">
            <table class="w-1/2">
                <tr>
                    <td class="w-32 py-1 font-semibold">Nama Peserta Didik</td>
                    <td class="w-4">:</td>
                    <td class="font-bold uppercase"><?php echo htmlspecialchars($santri['nama_santri']); ?></td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">Nomor Induk (NIS)</td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($santri['nis']); ?></td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">Nama Orang Tua</td>
                    <td>:</td>
                    <td><?php echo htmlspecialchars($santri['nama_ayah'] ?: '-'); ?></td>
                </tr>
            </table>
            <table class="w-5/12">
                <tr>
                    <td class="w-32 py-1 font-semibold">Kelas</td>
                    <td class="w-4">:</td>
                    <td class="font-bold"><?php echo htmlspecialchars($santri['kelas']); ?></td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">Semester</td>
                    <td>:</td>
                    <td><?php echo $smt; ?></td>
                </tr>
                <tr>
                    <td class="py-1 font-semibold">Tahun Ajaran</td>
                    <td>:</td>
                    <td><?php echo $ta; ?></td>
                </tr>
            </table>
        </div>

        <div class="mb-8">
            <h4 class="font-bold mb-2">A. Nilai Akademik (Pengetahuan & Keterampilan)</h4>
            <table class="w-full border-collapse border border-gray-800 text-center">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-800 p-2 w-12">No</th>
                        <th class="border border-gray-800 p-2 text-left">Mata Pelajaran</th>
                        <th class="border border-gray-800 p-2 w-32">Nilai Angka</th>
                        <th class="border border-gray-800 p-2 w-32">Predikat Huruf</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_nilai = 0;
                    $jumlah_mapel = mysqli_num_rows($q_nilai);

                    if ($jumlah_mapel > 0):
                        while ($n = mysqli_fetch_array($q_nilai)):
                            $angka = $n['nilai_angka'];
                            $total_nilai += $angka;

                            // Logika Predikat
                            if ($angka >= 90) {
                                $predikat = 'A';
                            } elseif ($angka >= 80) {
                                $predikat = 'B';
                            } elseif ($angka >= 70) {
                                $predikat = 'C';
                            } else {
                                $predikat = 'D';
                            }
                    ?>
                            <tr>
                                <td class="border border-gray-800 p-2"><?php echo $no++; ?></td>
                                <td class="border border-gray-800 p-2 text-left">
                                    <?php echo htmlspecialchars($n['nama_mapel']); ?></td>
                                <td class="border border-gray-800 p-2 font-bold"><?php echo $angka; ?></td>
                                <td class="border border-gray-800 p-2 font-bold"><?php echo $predikat; ?></td>
                            </tr>
                        <?php
                        endwhile;
                        $rata_rata = $total_nilai / $jumlah_mapel;
                        ?>
                        <tr class="bg-gray-50 font-bold">
                            <td colspan="2" class="border border-gray-800 p-2 text-right">Nilai Rata-rata:</td>
                            <td class="border border-gray-800 p-2"><?php echo number_format($rata_rata, 1); ?></td>
                            <td class="border border-gray-800 p-2"></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="border border-gray-800 p-4 italic text-gray-500">Nilai mata pelajaran
                                belum diinput oleh pengajar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <h4 class="font-bold mb-2">B. Pengembangan Diri & Karakter</h4>
            <table class="w-full border-collapse border border-gray-800">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-800 p-2 w-12 text-center">No</th>
                        <th class="border border-gray-800 p-2 text-left">Aspek Penilaian</th>
                        <th class="border border-gray-800 p-2 w-48 text-center">Nilai / Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-800 p-2 text-center">1</td>
                        <td class="border border-gray-800 p-2">Sikap, Akhlak, dan Budi Pekerti</td>
                        <td class="border border-gray-800 p-2 text-center font-bold text-lg">
                            <?php echo isset($sikap['nilai_sikap']) ? $sikap['nilai_sikap'] : '-'; ?></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-800 p-2 text-center">2</td>
                        <td class="border border-gray-800 p-2">Keaktifan dan Kedisiplinan</td>
                        <td class="border border-gray-800 p-2 text-center font-bold text-lg">
                            <?php echo isset($sikap['nilai_keaktifan']) ? $sikap['nilai_keaktifan'] : '-'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-12 border border-gray-800 p-4 min-h-[100px] relative">
            <h4 class="font-bold mb-1 absolute -top-3 left-4 bg-white px-2">Catatan Wali Kelas:</h4>
            <p class="mt-2 text-justify leading-relaxed">
                <?php echo isset($sikap['catatan_wali_kelas']) && !empty($sikap['catatan_wali_kelas']) ? htmlspecialchars($sikap['catatan_wali_kelas']) : '<span class="text-gray-400 italic">Tidak ada catatan untuk semester ini.</span>'; ?>
            </p>
        </div>

        <div class="flex justify-between text-center mt-12 px-8 break-inside-avoid">
            <div class="w-1/3">
                <p class="mb-20">Mengetahui,<br>Orang Tua / Wali Santri</p>
                <p class="font-bold underline uppercase">
                    <?php echo htmlspecialchars($santri['nama_ayah'] ?: '..........................................'); ?>
                </p>
            </div>

            <div class="w-1/3">
                <p class="mb-20">Slawi, <?php echo date('d F Y'); ?><br>Wali Kelas</p>
                <p class="font-bold underline uppercase">..........................................</p>
                <p class="text-xs">NIP. ..............................</p>
            </div>
        </div>

        <div class="flex justify-center text-center mt-12 break-inside-avoid">
            <div class="w-1/3">
                <p class="mb-20">Mengesahkan,<br>Kepala Sekolah / Mudir</p>
                <p class="font-bold underline uppercase">..........................................</p>
                <p class="text-xs">NIP. ..............................</p>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            // Uncomment baris di bawah ini kalau kamu mau begitu halamannya diklik, langsung nge-print otomatis
            // window.print();
        };
    </script>
</body>

</html>