<?php
$menu = [
  'dashboard'        => 'Home',
  'profil'           => 'Profil',
  'kontak'           => 'Kontak',
  'barang/list'      => 'Data Barang',
  'latihan/function' => 'Latihan Function',
];
$grupAktif = explode('/', $page ?? 'dashboard')[0];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi PHP Modular - Lab 9</title>
  <link rel="stylesheet" href="<?= e(baseUrl()) ?>assets/css/style.css">
</head>

<body>
  <div class="container">
    <header>
      <h1>Aplikasi PHP Modular</h1>
      <p>Lab 9 Pemrograman Web - Modularisasi dan Routing</p>
    </header>
    <nav>
      <?php foreach ($menu as $rute => $label): ?>
        <a href="<?= e(urlRute($rute)) ?>"
          class="<?= explode('/', $rute)[0] === $grupAktif ? 'aktif' : '' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <main>