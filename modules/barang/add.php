<?php
$errors = [];
$data = ['kategori' => '', 'nama' => '', 'harga_beli' => '', 'harga_jual' => '', 'stok' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifikasiCsrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.';
    } else {
        $data   = ambilDataBarang($errors);
        $gambar = uploadGambar($_FILES['gambar'] ?? null, $errors);

        if (empty($errors) && $gambar === null) {
            $errors[] = 'Gambar wajib dipilih.';
        }

        if (empty($errors)) {
            $hargaBeli = (float) $data['harga_beli'];
            $hargaJual = (float) $data['harga_jual'];
            $stok      = (int) $data['stok'];

            $stmt = mysqli_prepare(
                $koneksi,
                'INSERT INTO data_barang (kategori, nama, gambar, harga_beli, harga_jual, stok)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    'sssddi',
                    $data['kategori'],
                    $data['nama'],
                    $gambar,
                    $hargaBeli,
                    $hargaJual,
                    $stok
                );
                if (mysqli_stmt_execute($stmt)) {
                    setFlash('success', 'Data barang berhasil ditambahkan.');
                    alihkan('barang/list');
                }
                error_log(mysqli_stmt_error($stmt));
            } else {
                error_log(mysqli_error($koneksi));
            }

            // Gagal simpan: hapus gambar yang terlanjur diunggah
            $errors[] = 'Data gagal disimpan. Silakan coba lagi.';
            @unlink(__DIR__ . '/../../assets/gambar/' . $gambar);
        }
    }
}

$judul  = 'Tambah Barang';
$action = urlRute('barang/add');
require __DIR__ . '/_form.php';
