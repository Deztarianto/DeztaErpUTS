<?php $title='Soal Praktikum ERP'; include 'header.php';
$p=[['p1.php','1','diagram-3','Evolusi & Integrasi ERP','Analisis data terpisah, diagram before/after, data master, simulasi pesanan.'],
['p2.php','2','hdd-network','Arsitektur Teknis ERP','100 pengguna, hak akses, backup, aliran data, simulasi gangguan.'],
['p3.php','3','signpost-split','Proses Bisnis & Persetujuan','As-is/to-be, aturan bisnis, workflow persetujuan.'],
['p4.php','4','cash-coin','Modul Keuangan','Jurnal, buku besar, neraca saldo, uji duplikasi & koreksi.'],
['p5.php','5','box-seam','SCM & Pembelian MRP','Kebutuhan bruto/bersih, PR, penerimaan barang, pemasok terlambat.']];?>
<div class="alert alert-primary"><i class="bi bi-info-circle me-1"></i>Penilaian berdasarkan <b>ketepatan proses</b>, hasil konfigurasi, kemampuan menguji kesalahan, dan alasan pengambilan keputusan.</div>
<div class="row g-4 mt-1"><?php foreach($p as $x):?><div class="col-md-6 col-lg-4"><div class="card feature h-100"><div class="card-body p-4">
<div class="d-flex align-items-center gap-3 mb-3"><div class="ico"><i class="bi bi-<?=$x[2]?>"></i></div><span class="no">Praktikum <?=$x[1]?></span></div>
<h5><?=$x[3]?></h5><p class="text-muted"><?=$x[4]?></p><a href="<?=$x[0]?>" class="btn btn-primary">Buka <i class="bi bi-arrow-right"></i></a></div></div></div><?php endforeach;?></div>
<h5>Rubrik Praktikum (50%)</h5>
<table class="table table-hover"><tr class="table-primary"><th>Aspek penilaian</th><th>Bobot</th></tr><tr><td>Ketepatan konfigurasi dan rancangan</td><td>25%</td></tr><tr><td>Ketepatan hasil perhitungan atau transaksi</td><td>25%</td></tr><tr><td>Keberhasilan pengujian skenario</td><td>20%</td></tr><tr><td>Kemampuan menganalisis kesalahan</td><td>15%</td></tr><tr><td>Kemampuan menjelaskan dan mempertanggungjawabkan hasil</td><td>15%</td></tr></table>
<?php include 'footer.php';?>
