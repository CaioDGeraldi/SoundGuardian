<?php
require 'cfg.php';
if (!esta_logado()) {
    header("Location: entrar.php");
    exit;
}

$uid = $_SESSION['usr_id'];

// Buscar todos os itens favoritados pelo usuário
$sql = "
    SELECT it.*
    FROM fav f
    JOIN itn it ON it.id_itn = f.id_itn
    WHERE f.id_usr = ?
    ORDER BY it.ttl ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$uid]);
$itens = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Favoritos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'parts/header.php'; ?>

<main class="container">
    <h2>Seus Favoritos</h2>

    <?php if ($itens): ?>
        <?php foreach ($itens as $i): ?>
            <div class="card">
                <strong><?= htmlspecialchars($i['ttl']) ?></strong>
                <div class="muted"><?= htmlspecialchars($i['artista']) ?> — <?= htmlspecialchars($i['formato']) ?></div>

                <?php if ($i['capa']): ?>
                    <img src="<?= htmlspecialchars($i['capa']) ?>" class="cover">
                <?php endif; ?>

                <div class="actions">
                    <a class="btn tiny" href="ver_itn.php?id=<?= $i['id_itn'] ?>">Abrir</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Você ainda não favoritou nenhum item.</p>
    <?php endif; ?>
</main>

<?php include 'parts/footer.php'; ?>

</body>
</html>
