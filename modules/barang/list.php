<?php
$flash = ambilFlash();
$token = csrfToken();
$hasil = mysqli_query($koneksi, 'SELECT * FROM data_barang ORDER BY id_barang ASC');
?>
<section class="content">
  <h2>Data Barang</h2>

  <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div>
  <?php endif; ?>

  <p><a class="tombol" href="<?= e(urlRute('barang/add')) ?>">+ Tambah Barang</a></p>

  <?php if ($hasil === false): ?>
    <div class="alert alert-error">Gagal mengambil data barang.</div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($hasil) === 0): ?>
            <tr>
              <td colspan="8">Belum ada data barang.</td>
            </tr>
          <?php endif; ?>

          <?php $no = 1;
          while ($row = mysqli_fetch_assoc($hasil)): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><img src="<?= e(baseUrl()) ?>assets/gambar/<?= e($row['gambar']) ?>" alt="" width="60"></td>
              <td><?= e($row['nama']) ?></td>
              <td><?= e($row['kategori']) ?></td>
              <td><?= e(formatRupiah((float) $row['harga_beli'])) ?></td>
              <td><?= e(formatRupiah((float) $row['harga_jual'])) ?></td>
              <td><?= (int) $row['stok'] ?></td>
              <td class="aksi">
                <a href="<?= e(urlRute('barang/edit', ['id' => (int) $row['id_barang']])) ?>">Ubah</a>
                <form method="post" action="<?= e(urlRute('barang/delete')) ?>"
                  onsubmit="return confirm('Hapus data ini?');">
                  <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
                  <input type="hidden" name="id_barang" value="<?= (int) $row['id_barang'] ?>">
                  <button type="submit" class="btn-hapus">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>