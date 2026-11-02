<?php $title='Praktikum 2 - Arsitektur Teknis ERP (100 Pengguna)'; include 'header.php';
$ganggu=['1'=>'Database tidak dapat diakses: failover ke replika/standby, cek service & disk, aplikasi mode baca saja, pulihkan dari backup bila rusak.','2'=>'Server aplikasi gangguan: load balancer mengalihkan ke server cadangan, restart service, periksa log.','3'=>'Internet terputus: transaksi LAN tetap berjalan; akses remote via jaringan cadangan/VPN; tunda sinkronisasi eksternal.','4'=>'Akses tidak sah ke menu keuangan: ditolak (403), dicatat di audit log, notifikasi admin, tinjau role pengguna.','5'=>'Backup gagal/data hilang: restore full backup terakhir + binary log (point-in-time), verifikasi, perbaiki job backup & alert.'];
$k=$_GET['k']??'';?>
<h5>1. Diagram arsitektur</h5>
<pre class="mermaid">flowchart LR
U[100 Pengguna: Penjualan, Gudang, Produksi, Pembelian, Keuangan] --> W[Antarmuka Web - Browser]
W --> LB[Load Balancer + HTTPS]
LB --> A1[Server Aplikasi 1]
LB --> A2[Server Aplikasi 2]
A1 --> D[(Database Utama)]
A2 --> D
D --> R[(Replika Standby)]
D --> BK[Sistem Backup: harian + offsite]</pre>
<h5>2. Matriks hak akses (C=Create, R=Read, U=Update, A=Approve, -=Tidak ada)</h5>
<table class="table table-bordered text-center bg-white"><tr class="table-primary"><th>Departemen</th><th>Penjualan</th><th>Gudang</th><th>Produksi</th><th>Pembelian</th><th>Keuangan</th></tr>
<tr><td>Penjualan</td><td>CRU</td><td>R</td><td>R</td><td>-</td><td>R</td></tr>
<tr><td>Gudang</td><td>R</td><td>CRU</td><td>R</td><td>R</td><td>-</td></tr>
<tr><td>Produksi</td><td>R</td><td>R</td><td>CRU</td><td>R</td><td>-</td></tr>
<tr><td>Pembelian</td><td>-</td><td>R</td><td>R</td><td>CRUA</td><td>R</td></tr>
<tr><td>Keuangan</td><td>R</td><td>R</td><td>-</td><td>R</td><td>CRUA</td></tr></table>
<p class="small text-muted">Prinsip: least privilege, login individual, audit log, pemisahan tugas (yang membuat tidak menyetujui).</p>
<div class="row"><div class="col-md-6"><h5>3. Backup dan pemulihan</h5><ul>
<li>Full backup harian (malam), incremental/binlog tiap jam.</li><li>Salinan offsite/cloud terenkripsi; retensi 30 hari.</li>
<li>Uji restore bulanan di lingkungan pengujian.</li><li>Pemulihan: restore full terakhir, terapkan binlog sampai waktu gangguan, validasi data, buka akses.</li><li>Target: RPO 1 jam, RTO 4 jam.</li></ul></div>
<div class="col-md-6"><h5>4. Aliran data transaksi pembelian</h5><pre class="mermaid">sequenceDiagram
Pengguna->>Web: Input PO
Web->>Server App: Request (HTTPS)
Server App->>Server App: Cek hak akses & validasi
Server App->>Database: Simpan PO
Database-->>Server App: OK
Server App-->>Web: Konfirmasi
Database->>Backup: Binlog/backup terjadwal</pre></div></div>
<h5>5. Potensi kegagalan teknis</h5>
<table class="table table-bordered bg-white"><tr class="table-primary"><th>Kegagalan</th><th>Dampak operasional</th></tr>
<tr><td>Database down</td><td>Seluruh transaksi berhenti</td></tr><tr><td>Server aplikasi down</td><td>Pengguna tidak dapat login</td></tr><tr><td>Backup tidak berjalan</td><td>Data hilang saat bencana</td></tr><tr><td>Hak akses salah</td><td>Kebocoran data keuangan</td></tr></table>
<h5>Simulasi gangguan</h5>
<form class="row g-2 mb-3"><div class="col-auto"><select name="k" class="form-select"><option value="">- Pilih kondisi -</option><?php foreach(['Database tidak dapat diakses','Server aplikasi gangguan','Koneksi internet terputus','Pengguna tidak berwenang buka menu keuangan','Data hilang, backup tidak berjalan'] as $i=>$t):?><option value="<?=$i+1?>" <?=$k==$i+1?'selected':''?>><?=$t?></option><?php endforeach;?></select></div><div class="col-auto"><button class="btn btn-primary">Tampilkan penanganan</button></div></form>
<?php if(isset($ganggu[$k])):?><div class="alert alert-danger"><?=$ganggu[$k]?></div><?php endif;?>
<p class="small text-muted">Catatan: simulasi hanya di lingkungan pengujian, tidak mematikan server bersama.</p>
<?php include 'footer.php';?>
