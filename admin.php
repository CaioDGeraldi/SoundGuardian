<?php
require 'cfg.php';
if(!eh_admin()){ header('Location: entrar.php'); exit; }
$us = $pdo->query('SELECT * FROM usr ORDER BY criado_em DESC')->fetchAll();
$itns = $pdo->query('SELECT i.*, u.nm as dono FROM itn i JOIN usr u ON u.id_usr = i.id_usr ORDER BY i.criado_em DESC')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'parts/header.php'; ?><main class="container"><h2>Admin</h2><h3>Usuários</h3><table class="table"><tr><th>ID</th><th>Nome</th><th>Email</th><th>Admin</th><th>Criado</th></tr><?php foreach($us as $u): ?><tr><td><?=$u['id_usr']?></td><td><?=htmlspecialchars($u['nm'])?></td><td><?=htmlspecialchars($u['eml'])?></td><td><?=$u['adm']?></td><td><?=$u['criado_em']?></td></tr><?php endforeach; ?></table><h3>Itens</h3><div class="grid"><?php foreach($itns as $it): ?><div class="card small"><strong><?=htmlspecialchars($it['ttl'])?></strong><div class="muted"><?=htmlspecialchars($it['dono'])?></div></div><?php endforeach;?></div></main><?php include 'parts/footer.php'; ?></body></html>