<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===================== Escaping ===================== */

function e(string $nilai): string
{
    return htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
}

/* ===================== CSRF ===================== */

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifikasiCsrf($token): bool
{
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/* ===================== URL & Routing ===================== */

function baseUrl(): string
{
    return rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';
}

function urlRute(string $page, array $parameter = []): string
{
    $url = baseUrl() . 'index.php?page=' . $page;
    if (!empty($parameter)) {
        $url .= '&' . http_build_query($parameter);
    }
    return $url;
}

function alihkan(string $page, array $parameter = []): void
{
    header('Location: ' . urlRute($page, $parameter));
    exit;
}

/* ===================== Input ===================== */

// Mengambil nilai POST sebagai string yang sudah di-trim (mencegah array masuk ke trim())
function ambilPost(string $kunci): string
{
    $nilai = $_POST[$kunci] ?? '';
    return is_string($nilai) ? trim($nilai) : '';
}

// Validasi dan pengambilan data form barang (dipakai add.php dan edit.php)
function ambilDataBarang(array &$errors): array
{
    $data = [
        'kategori'   => ambilPost('kategori'),
        'nama'       => ambilPost('nama'),
        'harga_beli' => ambilPost('harga_beli'),
        'harga_jual' => ambilPost('harga_jual'),
        'stok'       => ambilPost('stok'),
    ];

    if ($data['kategori'] === '' || $data['nama'] === '') {
        $errors[] = 'Kategori dan nama barang wajib diisi.';
    }
    if (!is_numeric($data['harga_beli']) || (float) $data['harga_beli'] < 0) {
        $errors[] = 'Harga beli harus berupa angka tidak negatif.';
    }
    if (!is_numeric($data['harga_jual']) || (float) $data['harga_jual'] < 0) {
        $errors[] = 'Harga jual harus berupa angka tidak negatif.';
    }
    if (filter_var($data['stok'], FILTER_VALIDATE_INT) === false || (int) $data['stok'] < 0) {
        $errors[] = 'Stok harus berupa bilangan bulat tidak negatif.';
    }

    return $data;
}

// Upload gambar: validasi ekstensi, ukuran, dan isi file, lalu simpan ke assets/gambar
function uploadGambar(?array $file, array &$errors): ?string
{
    if ($file === null || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Gagal mengunggah gambar (kode ' . $file['error'] . ').';
        return null;
    }

    $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ekstensi, ['jpg', 'jpeg', 'png', 'gif'], true)) {
        $errors[] = 'Format gambar harus JPG, PNG, atau GIF.';
        return null;
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        $errors[] = 'Ukuran gambar maksimal 2 MB.';
        return null;
    }
    if (@getimagesize($file['tmp_name']) === false) {
        $errors[] = 'File yang diunggah bukan gambar yang valid.';
        return null;
    }

    $namaBaru = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ekstensi;
    $tujuan   = __DIR__ . '/../assets/gambar/' . $namaBaru;
    if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
        $errors[] = 'Gagal menyimpan gambar ke folder assets/gambar.';
        return null;
    }
    return $namaBaru;
}

/* ===================== Flash message ===================== */

function setFlash(string $tipe, string $pesan): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function ambilFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/* ===================== Format & perhitungan ===================== */

function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function hitungDiskon(float $harga, float $persen = 10): float
{
    return $harga * ($persen / 100);
}

function hitungTotal($harga, $jumlah)
{
    return $harga * $jumlah;
}

function konversiCelciusKeFahrenheit($celsius)
{
    return ($celsius * 9 / 5) + 32;
}

function tentukanStatusNilai($nilai)
{
    if ($nilai >= 85) {
        return 'Sangat Baik';
    }
    if ($nilai >= 70) {
        return 'Baik';
    }
    if ($nilai >= 55) {
        return 'Cukup';
    }
    return 'Perlu Perbaikan';
}

function validasiEmail(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

function ringkasTeks(string $teks, int $batas = 100): string
{
    if (mb_strlen($teks) <= $batas) {
        return $teks;
    }
    return rtrim(mb_substr($teks, 0, $batas)) . '...';
}
