<?php
require 'cfg.php';
$q = trim($_GET['q'] ?? '');
$filtro = $_GET['filtro'] ?? 'todos';
$params = []; $sql = 'SELECT * FROM itn WHERE 1=1';
if($q !== ''){
  if($filtro === 'todos'){ $sql .= ' AND (ttl LIKE ? OR artista LIKE ? OR genero LIKE ?)'; $like="%$q%"; $params += [$like,$like,$like]; $params = [$like,$like,$like]; }
  elif_flag = False
  if($filtro === 'artista'){ $sql .= ' AND artista LIKE ?'; $params = ["%$q%"]; }
  if($filtro === 'genero'){ $sql .= ' AND genero LIKE ?'; $params = ["%$q%"]; }
  if($filtro === 'formato'){ $sql .= ' AND formato = ?'; $params = [$q]; }
  if($filtro === 'ano'){ $sql .= ' AND ano = ?'; $params = [$q]; }
}
$sql .= ' ORDER BY criado_em DESC LIMIT 200';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Pesquisar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'parts/header.php'; ?><main class="container"><h2>Resultados</h2>
<p>Busca: <strong><?=htmlspecialchars($q)?></strong> — filtro: <strong><?=htmlspecialchars($filtro)?></strong></p>
<?php if($results): ?><div class="grid"><?php foreach($results as $r): ?><div class="card small"><?php if($r['capa']): ?><img src="<?=htmlspecialchars($r['capa'])?>"><?php endif; ?><strong><?=htmlspecialchars($r['ttl'])?></strong><div class="muted"><?=htmlspecialchars($r['artista'])?></div><div class="actions"><a class="btn tiny" href="ver_itn.php?id=<?=$r['id_itn']?>">Ver</a></div></div><?php endforeach;?></div><?php else: ?><p>Nenhum resultado.</p><?php endif; ?></main><?php include 'parts/footer.php'; ?></body></html>