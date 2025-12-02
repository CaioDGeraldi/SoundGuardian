<?php
require 'cfg.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT l.*, u.nm FROM lst l JOIN usr u ON u.id_usr = l.id_usr WHERE l.id_lst = ? LIMIT 1'); $stmt->execute([$id]); $l = $stmt->fetch();
if(!$l) die('Lista não encontrada');
$stmt2 = $pdo->prepare('SELECT i.* FROM lst_itn li JOIN itn i ON i.id_itn = li.id_itn WHERE li.id_lst = ? ORDER BY li.posicao ASC'); $stmt2->execute([$id]); $items = $stmt2->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title><?=htmlspecialchars($l['nm_lst'])?></title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'parts/header.php'; ?><main class="container"><h2><?=htmlspecialchars($l['nm_lst'])?></h2><div class="muted">Por <?=htmlspecialchars($l['nm'])?> — <?=($l['publico']?'Pública':'Privada')?></div><?php if($items): ?><div class="grid"><?php foreach($items as $it): ?><div class="card small"><?php if($it['capa']): ?><img src="<?=htmlspecialchars($it['capa'])?>"><?php endif; ?><strong><?=htmlspecialchars($it['ttl'])?></strong><div class="muted"><?=htmlspecialchars($it['artista'])?></div></div><?php endforeach;?></div><?php else: ?><p>Lista vazia.</p><?php endif; ?></main><?php include 'parts/footer.php'; ?></body></html>