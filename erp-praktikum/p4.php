<?php $title='Praktikum 4 - Modul Keuangan'; include 'header.php';
if(isset($_POST['dup']))$db->query("INSERT INTO jurnal(tanggal,ket,debit,kredit,nilai) SELECT CURDATE(),CONCAT(ket,' (DUPLIKAT)'),debit,kredit,nilai FROM jurnal WHERE id=3");
if(isset($_POST['koreksi'])){$id=(int)$_POST['koreksi'];$db->query("INSERT INTO jurnal(tanggal,ket,debit,kredit,nilai,ref_id) SELECT CURDATE(),CONCAT('Koreksi pembalik jurnal #',id),kredit,debit,nilai,id FROM jurnal WHERE id=$id AND ref_id IS NULL AND NOT EXISTS(SELECT 1 FROM jurnal j2 WHERE j2.ref_id=$id)");}
if(isset($_POST['reset']))$db->query("DELETE FROM jurnal WHERE id>6");
$nm=[];foreach($db->query("SELECT * FROM akun") as $a)$nm[$a['kode']]=$a;?>
<h5>Konfigurasi akun</h5><div class="mb-3"><?php foreach($nm as $a):?><span class="badge text-bg-secondary me-1"><?=$a['kode']?> <?=h($a['nama'])?></span><?php endforeach;?></div>
<form method="post" class="mb-3 d-flex gap-2"><button name="dup" class="btn btn-warning">Uji: catat ulang pembayaran utang (#3)</button><button name="reset" class="btn btn-secondary">Reset</button></form>
<h5>Jurnal umum</h5><table class="table table-sm table-bordered bg-white"><tr class="table-primary"><th>#</th><th>Tanggal</th><th>Keterangan</th><th>Debit</th><th>Kredit</th><th class="text-end">Nilai</th><th></th></tr>
<?php foreach($db->query("SELECT j.*,(SELECT COUNT(*) FROM jurnal x WHERE x.ref_id=j.id) rev FROM jurnal j ORDER BY id") as $j):?><tr><td><?=$j['id']?></td><td><?=$j['tanggal']?></td><td><?=h($j['ket'])?></td><td><?=$j['debit']?> <?=h($nm[$j['debit']]['nama'])?></td><td><?=$j['kredit']?> <?=h($nm[$j['kredit']]['nama'])?></td><td class="text-end"><?=rp($j['nilai'])?></td>
<td><?php if(!$j['ref_id']&&!$j['rev']&&$j['id']>6):?><form method="post"><button name="koreksi" value="<?=$j['id']?>" class="btn btn-sm btn-danger">Koreksi</button></form><?php elseif($j['rev']):?><span class="badge bg-secondary">dibalik</span><?php endif;?></td></tr><?php endforeach;?></table>
<h5>Neraca saldo (buku besar)</h5><table class="table table-sm table-bordered bg-white"><tr class="table-primary"><th>Akun</th><th class="text-end">Debit</th><th class="text-end">Kredit</th></tr>
<?php $td=$tk=0;foreach($nm as $k=>$a){$s=saldo($db,$k);$d=$s>0?$s:0;$c=$s<0?-$s:0;$td+=$d;$tk+=$c;?><tr><td><?=$k?> <?=h($a['nama'])?></td><td class="text-end"><?=$d?rp($d):'-'?></td><td class="text-end"><?=$c?rp($c):'-'?></td></tr><?php }?>
<tr class="fw-bold"><td>Total</td><td class="text-end"><?=rp($td)?></td><td class="text-end"><?=rp($tk)?></td></tr></table>
<p>Seimbang: <b class="<?=$td==$tk?'text-success':'text-danger'?>"><?=$td==$tk?'Ya':'Tidak'?></b>. Nilai benar: Kas Rp30.500.000, Piutang Rp7.000.000, Utang Rp5.000.000, Penjualan Rp12.000.000, Beban Rp1.500.000, Persediaan Rp8.000.000.</p>
<div class="alert alert-info">Duplikat membuat Kas berkurang dua kali (Rp3.000.000 ekstra) dan Utang turun terlalu besar. Koreksi memakai jurnal pembalik (ref #id) sehingga jejak audit tetap ada, bukan menghapus data.</div>
<?php include 'footer.php';?>
