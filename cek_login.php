<?php
// Mengaktifkan session pada php
session_start();

// Menghubungkan php dengan koneksi database
include 'koneksi.php';

// Menangkap data yang dikirim dari form login
$username = $_POST['username'];
$password = md5($_POST['password']); // Enkripsi MD5 sesuai data dummy kita

// Menyeleksi data user dengan username dan password yang sesuai
$login = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");

// Menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);

// Cek apakah username dan password ditemukan pada database
if ($cek > 0) {
    $data = mysqli_fetch_assoc($login);

    // Buat session login dan username
    $_SESSION['id_user'] = $data['id_user'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['status'] = "sudah_login";

    // Alihkan ke halaman dashboard sesuai role
    if ($data['role'] == "admin") {
        header("location:admin/index.php");
    } else if ($data['role'] == "pengajar") {
        header("location:pengajar/index.php"); // Nanti kita buat foldernya
    } else if ($data['role'] == "walisantri") {
        header("location:walisantri/index.php"); // Nanti kita buat foldernya
    } else if ($data['role'] == "pimpinan") {
        header("location:pimpinan/index.php"); // Nanti kita buat foldernya
    } else {
        // Jika role tidak dikenali
        header("location:login.php?pesan=gagal");
    }
} else {
    header("location:login.php?pesan=gagal");
}
