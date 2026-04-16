<?php

$host = "localhost";
$user = "root"; // Sesuaikan dengan username phpMyAdmin kamu
$pass = "";     // Kosongkan jika pakai XAMPP default
$db   = "db_akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
