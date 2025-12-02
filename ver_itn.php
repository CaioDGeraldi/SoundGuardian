<?php
require __DIR__ . "/cfg.php";

if (!esta_logado()) {
    header("Location: entrar.php");
    exit;
}

$uid = $_SESSION['usr_id'];

if (!isset($_GET['id'])) {
    die("Item não especificado.");
}

$id_itn = intval($_GET['id']);


$stmt = $pdo->prepare("SELECT * FROM itn WHERE id_itn = ?");
$stmt->execute([$id_itn]);
$it = $stmt->fetch();

if (!$it) {
    die("Item não encontrado.");
}

$usr = $pdo->prepare("SELECT id_usr, nm FROM usr WHERE id_usr = ?");
$usr->execute([$it['id_usr']]);
$autor = $usr->fetch();


$chk = $pdo->prepare("SELECT 1 FROM fav WHERE id_usr = ? AND id_itn = ?");
$chk->execute([$uid, $id_itn]);
$favoritado = $chk->fetch() ? true : false;


$coment = $pdo->prepare("
    SELECT c.*, u.nm 
    FROM cmntr c
    JOIN usr u ON u.id_usr = c.id_usr
    WHERE c.id_itn = ?
    ORDER BY c.dt DESC
");
$coment->execute([$id_itn]);
$comentarios = $coment->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($it['ttl']) ?></title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<?php include "parts/header.php"; ?>

<main class="container">

<h1><?= htmlspecialchars($it['ttl']) ?></h1>

<p class="muted">
    Por <?= $autor ? htmlspecialchars($autor['nm']) : "Autor desconhecido" ?> —
    <?= htmlspecialchars($it['ano']) ?>
</p>

<?php if (!empty($it['capa'])): ?>
    <img src="<?= htmlspecialchars($it['capa']) ?>" class="cover">
<?php endif; ?>

<p><?= nl2br(htmlspecialchars($it['desc_txt'])) ?></p>

<div class="actions">
    <?php if ($it['id_usr'] == $uid): ?>
        <a class="btn" href="editar_itn.php?id=<?= $it['id_itn'] ?>">Editar</a>
    <?php endif; ?>

    <?php if ($favoritado): ?>
        <a class="btn" href="fav_toggle.php?id=<?= $it['id_itn'] ?>">★ Favoritado (remover)</a>
    <?php else: ?>
        <a class="btn" href="fav_toggle.php?id=<?= $it['id_itn'] ?>">☆ Favoritar</a>
    <?php endif; ?>
</div>

<hr>

<h2>Comentários</h2>

<?php if ($comentarios): ?>
    <?php foreach ($comentarios as $c): ?>
        <div class="comentario">
            <strong><?= htmlspecialchars($c['nm']) ?></strong><br>
            <?= nl2br(htmlspecialchars($c['txt'])) ?>
            <div class="muted"><?= $c['dt'] ?></div>
            <hr>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Seja o primeiro a comentar.</p>
<?php endif; ?>

<form method="post" action="comentar.php">
    <input type="hidden" name="id_itn" value="<?= $it['id_itn'] ?>">
    <textarea name="texto" required placeholder="Adicionar comentário"></textarea>
    <button class="btn">Comentar</button>
</form>

</main>

<?php include "parts/footer.php"; ?>
</body>
</html>
