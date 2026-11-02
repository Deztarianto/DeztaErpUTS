<?php
$db=new mysqli('localhost','root','','erp_praktikum');$db->set_charset('utf8mb4');
function rp($n){return 'Rp'.number_format($n,0,',','.');}
function h($s){return htmlspecialchars((string)$s);}
function saldo($db,$k){$r=$db->query("SELECT COALESCE(SUM(IF(debit='$k',nilai,0))-SUM(IF(kredit='$k',nilai,0)),0) FROM jurnal WHERE '$k' IN(debit,kredit)")->fetch_row();return (int)$r[0];}
