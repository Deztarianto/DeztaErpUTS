<?php $title='Praktikum 1 - Evolusi dan Integrasi ERP'; include 'header.php';
$qty=max(1,(int)($_GET['qty']??100)); $kond=$_GET['kond']??'';
$m=$db->query("SELECT * FROM material")->fetch_all(MYSQLI_ASSOC);
$tant=['1'=>'Stok bahan baku tidak cukup: Gudang/MRP (net requirement) -> Pembelian membuat PR; Produksi ditunda sampai barang masuk.','2'=>'Pelanggan ubah jumlah: Sales Order direvisi -> reservasi stok, jadwal produksi, dan invoice piutang dihitung ulang otomatis.','3'=>'Pembayaran belum diterima: piutang tetap terbuka, Keuangan menandai jatuh tempo; pengiriman berikutnya dapat diblokir (credit limit).','4'=>'Produksi terlambat: tanggal janji kirim pada Sales Order diperbarui, Penjualan memberi tahu pelanggan, kebutuhan bahan dijadwal ulang.'];?>
<h5>1. Hubungan data yang belum terintegrasi</h5>
<table class="table table-bordered bg-white"><tr class="table-primary"><th>Departemen</th><th>Sistem</th><th>Masalah</th><th>Data terputus</th></tr>
<tr><td>Penjualan</td><td>Spreadsheet</td><td>Pesanan terlambat diperbarui</td><td>Pesanan -> Produksi & Stok</td></tr>
<tr><td>Gudang</td><td>Aplikasi stok</td><td>Stok beda dengan catatan pembelian</td><td>Pembelian -> Stok</td></tr>
<tr><td>Produksi</td><td>Formulir manual</td><td>Jadwal sering berubah</td><td>Pesanan & Stok -> Jadwal</td></tr>
<tr><td>Keuangan</td><td>Aplikasi akuntansi</td><td>Transaksi diinput ulang</td><td>Semua transaksi -> Jurnal</td></tr></table>
<div class="row"><div class="col-md-6"><h5>2. Diagram sebelum ERP</h5><pre class="mermaid">flowchart TD
S[Penjualan: Spreadsheet] -. WA/email manual .-> P[Produksi: Formulir]
G[Gudang: Aplikasi stok] -. rekap manual .-> P
G -. beda catatan .-> B[Pembelian]
S -. input ulang .-> K[Keuangan: Aplikasi akuntansi]
G -. input ulang .-> K
P -. input ulang .-> K</pre></div>
<div class="col-md-6"><h5>3. Diagram sesudah ERP</h5><pre class="mermaid">flowchart TD
S[Penjualan] --> DB[(Database ERP terpusat)]
G[Gudang] --> DB
P[Produksi] --> DB
B[Pembelian] --> DB
K[Keuangan] --> DB
DB --> L[Laporan real-time]</pre></div></div>
<h5>4. Data master bersama</h5>
<table class="table table-sm table-striped bg-white"><tr><th>Data master</th><th>Dipakai oleh</th><th>Atribut</th></tr>
<?php foreach($db->query("SELECT * FROM master_data") as $r):?><tr><td><?=h($r['nama'])?></td><td><?=h($r['dipakai'])?></td><td><?=h($r['keterangan'])?></td></tr><?php endforeach;?></table>
<h5>5. Simulasi pesanan pelanggan</h5>
<form class="row g-2 mb-3"><div class="col-auto"><label class="col-form-label">Jumlah pesanan (unit)</label></div><div class="col-auto"><input type="number" name="qty" value="<?=$qty?>" class="form-control"></div>
<div class="col-auto"><select name="kond" class="form-select"><option value="">- Tantangan individual -</option><?php foreach(['Stok bahan tidak cukup','Pelanggan ubah jumlah','Pembayaran belum diterima','Produksi terlambat'] as $i=>$t):?><option value="<?=$i+1?>" <?=$kond==$i+1?'selected':''?>><?=$t?></option><?php endforeach;?></select></div><div class="col-auto"><button class="btn btn-primary">Simulasikan</button></div></form>
<table class="table table-bordered bg-white"><tr class="table-primary"><th>Modul</th><th>Dampak</th></tr>
<?php $kurang=false; $s=''; foreach($m as $x){$need=$qty*$x['per_unit'];$sisa=$x['stok']-$need;if($sisa<0)$kurang=true;$s.="{$x['nama']}: butuh $need, stok {$x['stok']} -> sisa $sisa. ";}?>
<tr><td>Stok</td><td><?=h($s)?><?=$kurang?'<b class="text-danger">Kurang, buat PR.</b>':''?></td></tr>
<tr><td>Produksi</td><td>Work order <?=$qty?> unit, status: <?=$kurang?'Menunggu material':'Siap dijadwalkan'?></td></tr>
<tr><td>Keuangan</td><td>Piutang usaha dicatat saat invoice terbit (Dr Piutang, Cr Penjualan).</td></tr></table>
<?php if(isset($tant[$kond])):?><div class="alert alert-warning"><b>Bagian terdampak:</b> <?=$tant[$kond]?></div><?php endif;?>
<?php include 'footer.php';?>
