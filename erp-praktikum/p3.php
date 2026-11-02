<?php $title='Praktikum 3 - Proses Bisnis & Alur Persetujuan'; include 'header.php';
$batas=10; // aturan: kenaikan nilai > 10% dari nilai disetujui -> persetujuan ulang
if(isset($_POST['ajukan'])&&trim($_POST['pengaju'])!==''){$s=$db->prepare("INSERT INTO permintaan(pengaju,bahan,nilai) VALUES(?,?,?)");$n=(int)$_POST['nilai'];$s->bind_param('ssi',$_POST['pengaju'],$_POST['bahan'],$n);$s->execute();}
if(isset($_POST['setuju'])){$id=(int)$_POST['setuju'];$db->query("UPDATE permintaan SET nilai_setuju=nilai,status='disetujui' WHERE id=$id");}
if(isset($_POST['revisi'])){$id=(int)$_POST['id'];$n=(int)$_POST['nilai_baru'];$db->query("UPDATE permintaan SET nilai=$n,status=IF(nilai_setuju>0 AND $n>nilai_setuju*(1+$batas/100),'perlu persetujuan ulang',status) WHERE id=$id");}
if(isset($_POST['hapus']))$db->query("DELETE FROM permintaan");?>
<div class="row"><div class="col-md-6"><h5>A. As-is (manual)</h5><pre class="mermaid">flowchart TD
A[Dosen minta bahan via chat/kertas] --> B[Kalab verifikasi]
B --> C[Gudang cek stok manual]:::r
C --> D[Pembelian pilih pemasok]:::r
D --> E[Pimpinan setuju - tanda tangan]:::r
E --> F[Gudang terima barang]
F --> G[Keuangan bayar - cari dokumen]:::r
classDef r fill:#f8d7da,stroke:#dc3545</pre><small class="text-danger">Merah = berisiko salah/terlambat (berkas hilang, stok tidak akurat, tanda tangan menunggu, dokumen tidak cocok).</small></div>
<div class="col-md-6"><h5>B. To-be (ERP)</h5><pre class="mermaid">flowchart TD
A[Dosen input PR + identitas] --> B[Kalab verifikasi di sistem]
B --> C{Cek stok otomatis}
C -- cukup --> X[Pakai stok / selesai]
C -- kurang --> D[Pembelian: bandingkan pemasok, buat PO]
D --> E{Persetujuan sesuai nilai}
E -- setuju --> F[Gudang GR terhadap PO]
F --> G[3-way match PO-GR-Invoice]
G --> H[Keuangan bayar]
E -- tolak --> D</pre></div></div>
<h5>C. Aturan bisnis</h5><ol><li>Permintaan wajib memiliki identitas pengaju.</li><li>Permintaan diperiksa terhadap stok sebelum PO dibuat.</li><li>PO harus disetujui pihak berwenang.</li><li>Pembayaran wajib didukung PO, penerimaan barang, dan faktur yang cocok.</li><li>Jika nilai PO naik lebih dari <?=$batas?>% dari nilai yang disetujui, persetujuan wajib diulang.</li></ol>
<h5>Tabel aktor</h5><table class="table table-sm table-bordered bg-white"><tr><th>Aktor</th><th>Tanggung jawab</th></tr><tr><td>Dosen</td><td>Mengajukan permintaan</td></tr><tr><td>Kepala Lab</td><td>Verifikasi kebutuhan</td></tr><tr><td>Gudang</td><td>Cek stok, terima barang</td></tr><tr><td>Pembelian</td><td>Pilih pemasok, buat PO</td></tr><tr><td>Pimpinan</td><td>Menyetujui PO</td></tr><tr><td>Keuangan</td><td>Memproses pembayaran</td></tr></table>
<h5>D. Simulasi (database)</h5>
<form method="post" class="row g-2 mb-3"><div class="col-md-3"><input name="pengaju" class="form-control" placeholder="Identitas pengaju" required></div><div class="col-md-4"><input name="bahan" class="form-control" placeholder="Bahan praktik" required></div><div class="col-md-3"><input name="nilai" type="number" class="form-control" placeholder="Nilai (Rp)" required></div><div class="col-md-2"><button name="ajukan" class="btn btn-primary w-100">Ajukan</button></div></form>
<table class="table table-bordered bg-white align-middle"><tr class="table-primary"><th>#</th><th>Pengaju</th><th>Bahan</th><th>Nilai</th><th>Disetujui</th><th>Status</th><th>Aksi</th></tr>
<?php foreach($db->query("SELECT * FROM permintaan ORDER BY id") as $r):?><tr><td><?=$r['id']?></td><td><?=h($r['pengaju'])?></td><td><?=h($r['bahan'])?></td><td><?=rp($r['nilai'])?></td><td><?=rp($r['nilai_setuju'])?></td><td><span class="badge bg-<?=$r['status']=='disetujui'?'success':'warning'?>"><?=h($r['status'])?></span></td>
<td><form method="post" class="d-flex gap-1"><input type="hidden" name="id" value="<?=$r['id']?>"><button name="setuju" value="<?=$r['id']?>" class="btn btn-sm btn-success">Setujui</button><input name="nilai_baru" type="number" class="form-control form-control-sm" placeholder="Nilai baru"><button name="revisi" class="btn btn-sm btn-outline-danger">Penawaran baru</button></form></td></tr><?php endforeach;?></table>
<form method="post"><button name="hapus" class="btn btn-sm btn-secondary">Reset data</button></form>
<p class="mt-3"><b>Kesimpulan:</b> rancangan baru lebih baik karena stok dicek otomatis, persetujuan tercatat, dan perubahan nilai memicu persetujuan ulang sehingga tidak ada pembelian di atas otorisasi.</p>
<?php include 'footer.php';?>
