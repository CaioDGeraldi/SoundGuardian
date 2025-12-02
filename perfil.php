<?php
require 'cfg.php';
if (!esta_logado()) { header("Location: entrar.php"); exit; }

$uid = $_SESSION['usr_id'];

$pl = $pdo->prepare("SELECT * FROM lst WHERE id_usr = ? ORDER BY criado_em DESC");
$pl->execute([$uid]);
$playlists = $pl->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Perfil</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'parts/header.php'; ?>

<main class="container">
<h2>Administrador</h2>

<h3>Suas playlists</h3>

<?php if ($playlists): ?>
    <?php foreach ($playlists as $l): ?>
        <div class="card">
            <strong><?= htmlspecialchars($l['nm_lst']) ?></strong>
            <p><?= htmlspecialchars($l['desc_txt']) ?></p>
            <a class="btn tiny" href="lista_unica.php?id=<?= $l['id_lst'] ?>">Abrir</a>
            <a class="btn tiny" href="editar_lst.php?id=<?= $l['id_lst'] ?>">Editar</a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhuma playlist criada ainda.</p>
<?php endif; ?>

</main>

<?php include 'parts/footer.php'; ?>

</body>
</html>
