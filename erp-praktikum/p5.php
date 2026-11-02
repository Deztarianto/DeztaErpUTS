<?php $title='Praktikum 5 - SCM & Pembelian Berbasis MRP'; include 'header.php';
$qty=max(1,(int)($_GET['qty']??300));$dl=max(1,(int)($_GET['dl']??6));$inc=isset($_GET['inc'])||!$_GET;$ket='';
if(isset($_POST['pr'])){foreach($_POST['net'] as $k=>$n)if($n>0){$s=$db->prepare("INSERT INTO pr(kode,qty) VALUES(?,?)");$n=(int)$n;$s->bind_param('si',$k,$n);$s->execute();}}
if(isset($_POST['terima'])){$id=(int)$_POST['terima'];$q=(int)$_POST['jml'];$r=$db->query("SELECT * FROM pr WHERE id=$id")->fetch_assoc();if($r){$q=min($q,$r['qty']-$r['diterima']);if($q>0){$db->query("UPDATE pr SET diterima=diterima+$q,status=IF(diterima>=qty,'selesai','sebagian') WHERE id=$id");$db->query("UPDATE material SET stok=stok+$q WHERE kode='{$r['kode']}'");}}}
if(isset($_POST['reset'])){$db->query("DELETE FROM pr");$db->query("UPDATE material SET stok=IF(kode='A',400,250)");}
$m=$db->query("SELECT * FROM material")->fetch_all(MYSQLI_ASSOC);?>
<form class="row g-2 mb-3"><div class="col-auto"><label>Produksi (unit)</label><input name="qty" type="number" value="<?=$qty?>" class="form-control"></div><div class="col-auto"><label>Tenggat (hari kerja)</label><input name="dl" type="number" value="<?=$dl?>" class="form-control"></div>
<div class="col-auto pt-4"><label><input type="checkbox" name="inc" <?=$inc?'checked':''?>> Pesanan belum diterima dihitung</label></div><div class="col-auto pt-4"><button class="btn btn-primary">Hitung</button></div></form>
<h5>Data master & BOM</h5><p>Produk jadi: 1 unit = 3 unit Bahan A + 2 unit Bahan B. Gudang utama, satuan unit, aturan pesan: lot-for-lot sebesar kebutuhan bersih.</p>
<h5>Perhitungan kebutuhan</h5>
<form method="post"><table class="table table-bordered bg-white"><tr class="table-primary"><th>Bahan</th><th>BOM</th><th>Bruto</th><th>Stok</th><th>Open PO</th><th>Bersih</th><th>Lead time</th><th>Status</th></tr>
<?php foreach($m as $x){$g=$qty*$x['per_unit'];$po=$inc?$x['po_open']:0;$n=max(0,$g-$x['stok']-$po);$late=$n>0&&$x['lead_time']>$dl;?>
<tr><td><?=$x['nama']?></td><td><?=$x['per_unit']?></td><td><?=$g?></td><td><?=$x['stok']?></td><td><?=$po?></td><td><b><?=$n?></b><input type="hidden" name="net[<?=$x['kode']?>]" value="<?=$n?>"></td><td><?=$x['lead_time']?> hari</td><td><?=$n==0?'<span class="badge bg-success">Cukup</span>':($late?'<span class="badge bg-danger">Terlambat</span>':'<span class="badge bg-warning">Perlu PR</span>')?></td></tr><?php }?></table>
<p class="small text-muted">Asumsi: Open PO Bahan A (100 unit) tiba dalam 4 hari kerja, sebelum tenggat <?=$dl?> hari, sehingga boleh dipakai. Matikan centang untuk skenario konservatif (A bersih 500).</p>
<button name="pr" class="btn btn-success">Buat Permintaan Pembelian</button> <button name="reset" class="btn btn-secondary">Reset</button></form>
<h5 class="mt-4">Permintaan pembelian & penerimaan barang</h5>
<table class="table table-sm table-bordered bg-white align-middle"><tr class="table-primary"><th>PR</th><th>Bahan</th><th>Qty</th><th>Diterima</th><th>Status</th><th>Terima</th></tr>
<?php foreach($db->query("SELECT * FROM pr") as $r):?><tr><td><?=$r['id']?></td><td><?=$r['kode']?></td><td><?=$r['qty']?></td><td><?=$r['diterima']?></td><td><?=$r['status']?></td><td><form method="post" class="d-flex gap-1"><input name="jml" type="number" class="form-control form-control-sm" value="<?=(int)($r['qty']/2)?>"><button name="terima" value="<?=$r['id']?>" class="btn btn-sm btn-primary">GR</button></form></td></tr><?php endforeach;?></table>
<h5>Laporan stok saat ini</h5><table class="table table-sm bg-white"><?php foreach($m as $x):?><tr><td><?=$x['nama']?></td><td><?=$x['stok']?> unit</td></tr><?php endforeach;?></table>
<h5>Kondisi baru: pemasok B terlambat (8 hari &gt; 6 hari)</h5>
<table class="table table-bordered bg-white"><tr class="table-primary"><th>Opsi</th><th>Waktu</th><th>Biaya</th><th>Risiko</th></tr>
<tr class="table-success"><td><b>Pemasok alternatif (atau stok lokasi lain) untuk 350 unit B</b></td><td>Memenuhi jika lead time &le; 6 hari</td><td>Lebih mahal / ongkir</td><td>Rendah, perlu cek kualitas</td></tr>
<tr><td>Jadwal ulang produksi (selesai hari ke-8)</td><td>Terlambat 2 hari</td><td>Rendah</td><td>Janji kirim terlambat</td></tr>
<tr><td>Produksi bertahap sesuai stok B</td><td>Sebagian tepat waktu</td><td>Sedang</td><td>Sebagian pesanan tertunda</td></tr></table>
<p><b>Rekomendasi:</b> cari pemasok alternatif/stok lokasi lain terlebih dahulu; bila tidak ada, produksi bertahap dan informasikan pelanggan.</p>
<?php include 'footer.php';?>
