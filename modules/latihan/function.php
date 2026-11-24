<?php
$contoh = [
  ['Konversi 30 °C ke Fahrenheit', konversiCelciusKeFahrenheit(30) . ' °F'],
  ['Status nilai 78', tentukanStatusNilai(78)],
  ['Total 3 x Rp 15.000', formatRupiah(hitungTotal(15000, 3))],
  ['Diskon 10% dari Rp 200.000', formatRupiah(hitungDiskon(200000))],
  ['Diskon 15% dari Rp 200.000', formatRupiah(hitungDiskon(200000, 15))],
  ['Validasi email rina@mail.com', validasiEmail('rina@mail.com') ? 'Valid' : 'Tidak valid'],
  ['Ringkas teks', ringkasTeks('Modularisasi membagi aplikasi menjadi bagian-bagian kecil.', 30)],
];
?>
<section class="content">
  <h2>Latihan Function</h2>
  <p>Seluruh function berada di <code>helpers/functions.php</code> dan dipanggil dari halaman ini.</p>
  <table>
    <thead>
      <tr>
        <th>Contoh</th>
        <th>Hasil</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($contoh as [$label, $hasil]): ?>
        <tr>
          <td><?= e($label) ?></td>
          <td><?= e((string) $hasil) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>