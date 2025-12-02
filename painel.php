<?php
require 'cfg.php';

if (!esta_logado()) {
    header('Location: entrar.php');
    exit;
}

$uid = $_SESSION['usr_id'];


$stmt = $pdo->prepare("
    SELECT *
    FROM itn
    WHERE id_usr = ?
    ORDER BY id_itn DESC
    LIMIT 8
");
$stmt->execute([$uid]);
$recent = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt2 = $pdo->prepare("
    SELECT i.*
    FROM itn i
    JOIN fav f ON f.id_itn = i.id_itn
    WHERE f.id_usr = ?
    ORDER BY i.id_itn DESC
    LIMIT 6
");
$stmt2->execute([$uid]);
$favs = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Painel</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<?php include 'parts/header.php'; ?>

<main class="container">

    <h2>Olá, <?= htmlspecialchars(usuario_nome()) ?></h2>

    <h3>Adicionados recentemente</h3>

    <?php if ($recent): ?>
        <div class="grid">
        <?php foreach ($recent as $it): ?>
            <div class="card small">

                <?php if (!empty($it['img'])): ?>
                    <img src="<?= htmlspecialchars($it['img']) ?>" alt="capa">
                <?php endif; ?>

                <strong><?= htmlspecialchars($it['ttl']) ?></strong>

                <?php if (!empty($it['tipo'])): ?>
                    <div class="muted"><?= htmlspecialchars($it['tipo']) ?></div>
                <?php endif; ?>

                <div class="actions">
                    <a class="btn tiny" href="ver_itn.php?id=<?= $it['id_itn'] ?>">Ver</a>
                    <a class="btn tiny" href="editar_itn.php?id=<?= $it['id_itn'] ?>">Editar</a>
                </div>

            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Nenhum item encontrado.</p>
    <?php endif; ?>

    <hr>

    <h3>Favoritos</h3>

    <?php if ($favs): ?>
        <ul>
            <?php foreach ($favs as $f): ?>
                <li><?= htmlspecialchars($f['ttl']) ?>
                    <?php if (!empty($f['tipo'])): ?>
                        — <span class="muted"><?= htmlspecialchars($f['tipo']) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Sem favoritos.</p>
    <?php endif; ?>

</main>

<?php include 'parts/footer.php'; ?>
</body>
</html>
