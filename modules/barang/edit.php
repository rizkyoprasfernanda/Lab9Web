<?php
$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    setFlash('error', 'ID barang tidak valid.');
    alihkan('barang/list');
}

$stmt = mysqli_prepare($koneksi, 'SELECT * FROM data_barang WHERE id_barang = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$barang = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$barang) {
    setFlash('error', 'Data barang tidak ditemukan.');
    alihkan('barang/list');
}

$errors = [];
$data = [
    'kategori'   => $barang['kategori'],
    'nama'       => $barang['nama'],
    'harga_beli' => $barang['harga_beli'],
    'harga_jual' => $barang['harga_jual'],
    'stok'       => $barang['stok'],
];
$gambarSekarang = $barang['gambar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifikasiCsrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $data       = ambilDataBarang($errors);
        $gambarBaru = uploadGambar($_FILES['gambar'] ?? null, $errors);

        if (empty($errors)) {
            $gambarFinal = $gambarBaru ?? $gambarSekarang;
            $hargaBeli   = (float) $data['harga_beli'];
            $hargaJual   = (float) $data['harga_jual'];
            $stok        = (int) $data['stok'];

            $stmt = mysqli_prepare(
                $koneksi,
                'UPDATE data_barang
                    SET kategori = ?, nama = ?, gambar = ?, harga_beli = ?, harga_jual = ?, stok = ?
                  WHERE id_barang = ?'
            );
            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    'sssddii',
                    $data['kategori'],
                    $data['nama'],
                    $gambarFinal,
                    $hargaBeli,
                    $hargaJual,
                    $stok,
                    $id
                );
                if (mysqli_stmt_execute($stmt)) {
                    // Gambar lama dihapus hanya jika diganti dengan gambar baru
                    if ($gambarBaru !== null && $gambarSekarang !== '') {
                        @unlink(__DIR__ . '/../../assets/gambar/' . basename($gambarSekarang));
                    }
                    setFlash('success', 'Data barang berhasil diperbarui.');
                    alihkan('barang/list');
                }
                error_log(mysqli_stmt_error($stmt));
            } else {
                error_log(mysqli_error($koneksi));
            }

            $errors[] = 'Data gagal diperbarui. Silakan coba lagi.';
            if ($gambarBaru !== null) {
                @unlink(__DIR__ . '/../../assets/gambar/' . $gambarBaru);
            }
        }
    }
}

$judul  = 'Ubah Barang';
$action = urlRute('barang/edit', ['id' => $id]);
require __DIR__ . '/_form.php';
