<?php
// config/database.php
// Koneksi database dipusatkan di sini (Latihan 4: koneksi hanya dikelola pada satu file).
// Melanjutkan koneksi.php dari Lab8Web (MySQLi), database praktikum8.

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'praktikum8';

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    // Detail error hanya dicatat di log, tidak ditampilkan ke pengguna
    error_log('Koneksi database gagal: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Terjadi kesalahan pada koneksi database.');
}
mysqli_set_charset($koneksi, 'utf8mb4');
