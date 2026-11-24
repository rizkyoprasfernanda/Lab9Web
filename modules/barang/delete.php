<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', 'Penghapusan hanya dapat dilakukan melalui tombol Hapus.');
    alihkan('barang/list');
}
if (!verifikasiCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Token keamanan tidak valid.');
    alihkan('barang/list');
}

$id = (isset($_POST['id_barang']) && is_numeric($_POST['id_barang'])) ? (int) $_POST['id_barang'] : 0;
if ($id <= 0) {
    setFlash('error', 'ID barang tidak valid.');
    alihkan('barang/list');
}

$stmt = mysqli_prepare($koneksi, 'SELECT gambar FROM data_barang WHERE id_barang = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$barang = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$barang) {
    setFlash('error', 'Data barang tidak ditemukan.');
    alihkan('barang/list');
}

$stmt = mysqli_prepare($koneksi, 'DELETE FROM data_barang WHERE id_barang = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);

if (mysqli_stmt_execute($stmt)) {
    if ($barang['gambar'] !== '') {
        @unlink(__DIR__ . '/../../assets/gambar/' . basename($barang['gambar']));
    }
    setFlash('success', 'Data barang berhasil dihapus.');
} else {
    error_log(mysqli_stmt_error($stmt));
    setFlash('error', 'Data gagal dihapus.');
}

alihkan('barang/list');
