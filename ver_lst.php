<?php
require 'cfg.php';
if(!esta_logado()){ header('Location: entrar.php'); exit; }
$uid = $_SESSION['usr_id'];
$stmt = $pdo->prepare('SELECT * FROM lst WHERE id_usr = ? ORDER BY criado_em DESC'); $stmt->execute([$uid]); $lsts = $stmt->fetchAll();
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Minhas listas</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
<body>
    <?php include 'parts/header.php'; ?>
    <main class="container">
        <h2>Minhas listas</h2>
        <a class="btn" href="criar_lst.php">Criar nova lista</a>
        <?php if($lsts): foreach($lsts as $l): ?>
            <div class="card">
                <strong><?=htmlspecialchars($l['nm_lst'])?></strong>
                <div class="muted"><?=htmlspecialchars($l['desc_txt'])?></div>
                <div class="actions">
                    <a class="btn tiny" href="lista_unica.php?id=<?=$l['id_lst']?>">Abrir</a>
                     <a class="btn tiny" href="editar_lst.php?id=<?=$l['id_lst']?>">Editar</a>
                     <?php if ($l['id_usr'] == $_SESSION['usr_id'] && $l['nm_lst'] != 'Favoritos'): ?>
                        <a class="btn hollow" href="excluir_lst.php?id=<?= $l['id_lst'] ?>">Excluir playlist</a>
<?php endif; ?>

                </div>
            </div>
            <?php endforeach; else: ?><p>Você ainda não criou listas.</p><?php endif; ?>
    </main>
    
    <?php include 'parts/footer.php'; ?>

</body>

</html>