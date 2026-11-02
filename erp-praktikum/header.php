<?php require_once 'config.php';
$menu=['index.php'=>['Beranda','house'],'p1.php'=>['P1 Evolusi','diagram-3'],'p2.php'=>['P2 Arsitektur','hdd-network'],'p3.php'=>['P3 Proses','signpost-split'],'p4.php'=>['P4 Keuangan','cash-coin'],'p5.php'=>['P5 SCM','box-seam']];
$cur=basename($_SERVER['PHP_SELF']); ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=h($title??'Praktikum ERP')?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<script type="module">import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10.9.1/dist/mermaid.esm.min.mjs';mermaid.initialize({startOnLoad:true,theme:'base',themeVariables:{primaryColor:'#e8eeff',primaryBorderColor:'#1a2f6b',lineColor:'#1a2f6b',fontFamily:'Inter'}});</script>
</head><body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top"><div class="container">
<a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-boxes me-2"></i>Praktikum ERP</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#m"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="m"><ul class="navbar-nav ms-auto">
<?php foreach($menu as $u=>$n):?><li class="nav-item"><a class="nav-link <?=$cur==$u?'active':''?>" href="<?=$u?>"><i class="bi bi-<?=$n[1]?> me-1"></i><?=$n[0]?></a></li><?php endforeach;?>
</ul></div></div></nav>
<header class="page-hero"><div class="container"><h1><?=h($title??'')?></h1><div class="sub mt-1">Politeknik META Industri &middot; Mata Kuliah ERP</div></div></header>
<div class="container main pb-4"><div class="panel">
