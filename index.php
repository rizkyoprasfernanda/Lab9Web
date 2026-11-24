<?php
// index.php — titik masuk aplikasi dan router (whitelist rute)
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';

$routes = [
    'dashboard'        => __DIR__ . '/views/dashboard.php',
    'profil'           => __DIR__ . '/views/profil.php',
    'kontak'           => __DIR__ . '/views/kontak.php',
    'latihan/function' => __DIR__ . '/modules/latihan/function.php',
    'barang/list'      => __DIR__ . '/modules/barang/list.php',
    'barang/add'       => __DIR__ . '/modules/barang/add.php',
    'barang/edit'      => __DIR__ . '/modules/barang/edit.php',
    'barang/delete'    => __DIR__ . '/modules/barang/delete.php',
];

// Nilai page harus string; selain itu dianggap tidak valid
$page = $_GET['page'] ?? 'dashboard';
if (!is_string($page)) {
    $page = '';
}

// Hanya rute yang ada di whitelist yang boleh dimuat. Input tidak pernah dipakai sebagai path.
if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    $contentFile = __DIR__ . '/views/404.php';
} else {
    $contentFile = $routes[$page];
}

// Konten dijalankan lebih dulu di dalam output buffer, sehingga modul
// (add/edit/delete) masih bisa melakukan redirect dengan header() setelah memproses data.
ob_start();
require $contentFile;
$konten = ob_get_clean();

require_once __DIR__ . '/views/header.php';
echo $konten;
require_once __DIR__ . '/views/footer.php';
