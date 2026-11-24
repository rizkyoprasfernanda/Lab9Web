<?php
// modules/barang/_form.php — form bersama untuk tambah dan ubah (template komponen)
// Variabel yang diharapkan: $judul, $action, $data, $errors, $gambarSekarang (opsional)
$gambarSekarang = $gambarSekarang ?? '';
?>
<section class="content">
  <h2><?= e($judul) ?></h2>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <ul>
        <?php foreach ($errors as $pesan): ?>
          <li><?= e($pesan) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="form-barang">
    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

    <label>Kategori
      <input type="text" name="kategori" value="<?= e($data['kategori']) ?>" required>
    </label>
    <label>Nama Barang
      <input type="text" name="nama" value="<?= e($data['nama']) ?>" required>
    </label>
    <label>Harga Beli
      <input type="number" name="harga_beli" min="0" step="any" value="<?= e((string) $data['harga_beli']) ?>" required>
    </label>
    <label>Harga Jual
      <input type="number" name="harga_jual" min="0" step="any" value="<?= e((string) $data['harga_jual']) ?>" required>
    </label>
    <label>Stok
      <input type="number" name="stok" min="0" step="1" value="<?= e((string) $data['stok']) ?>" required>
    </label>
    <label>Gambar <?= $gambarSekarang !== '' ? '(kosongkan jika tidak diubah)' : '' ?>
      <input type="file" name="gambar" accept="image/jpeg,image/png,image/gif"
             <?= $gambarSekarang === '' ? 'required' : '' ?>>
    </label>

    <?php if ($gambarSekarang !== ''): ?>
      <p>Gambar saat ini:</p>
      <img src="<?= e(baseUrl()) ?>assets/gambar/<?= e($gambarSekarang) ?>" alt="Gambar barang" width="120">
    <?php endif; ?>

    <div class="aksi-form">
      <button type="submit">Simpan</button>
      <a href="<?= e(urlRute('barang/list')) ?>">Batal</a>
    </div>
  </form>
</section>
