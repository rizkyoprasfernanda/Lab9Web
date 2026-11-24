<?php
// views/kontak.php — Latihan 1 (halaman Kontak) dan Latihan 2 (validasiEmail, ringkasTeks)
$status = null;
$pesanError = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = ambilPost('nama');
    $email = ambilPost('email');
    $pesan = ambilPost('pesan');

    if ($nama === '' || $pesan === '') {
        $pesanError = 'Nama dan pesan wajib diisi.';
    } elseif (!validasiEmail($email)) {
        $pesanError = 'Format email tidak valid.';
    } else {
        $status = 'Terima kasih, ' . $nama . '. Pesan Anda diterima: "' . ringkasTeks($pesan, 50) . '"';
    }
}
?>
<section class="content">
  <h2>Kontak</h2>

  <?php if ($status): ?>
    <div class="alert alert-success"><?= e($status) ?></div>
  <?php endif; ?>
  <?php if ($pesanError): ?>
    <div class="alert alert-error"><?= e($pesanError) ?></div>
  <?php endif; ?>

  <form method="post" action="<?= e(urlRute('kontak')) ?>" class="form-barang">
    <label>Nama <input type="text" name="nama" required></label>
    <label>Email <input type="email" name="email" required></label>
    <label>Pesan <textarea name="pesan" rows="4" required></textarea></label>
    <div class="aksi-form"><button type="submit">Kirim</button></div>
  </form>
</section>
