<?php
$total = 0;
$hasil = mysqli_query($koneksi, 'SELECT COUNT(*) AS total FROM data_barang');
if ($hasil) {
  $total = (int) mysqli_fetch_assoc($hasil)['total'];
}
?>
<section class="content">
  <h2>Halaman Dashboard</h2>
  <p>Selamat datang di Aplikasi PHP Modular. Halaman ini dimuat melalui router <code>index.php</code>.</p>
  <div class="kartu">Total data barang: <strong><?= $total ?></strong></div>
  <p>Contoh helper: diskon 10% dari Rp 200.000 adalah <?= e(formatRupiah(hitungDiskon(200000))) ?>.</p>
</section>