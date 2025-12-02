<?php
require 'cfg.php';

if (!esta_logado()) {
    header("Location: entrar.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);


$stmt = $pdo->prepare("SELECT * FROM lst WHERE id_lst = ? AND id_usr = ?");
$stmt->execute([$id, $_SESSION['usr_id']]);
$lst = $stmt->fetch();

if (!$lst) {
    die("Lista não encontrada.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $desc = trim($_POST['desc']);
    $pub  = isset($_POST['publico']) ? 1 : 0;

    $up = $pdo->prepare("UPDATE lst SET nm_lst = ?, desc_txt = ?, publico = ? WHERE id_lst = ? AND id_usr = ?");
    $up->execute([$nome, $desc, $pub, $id, $_SESSION['usr_id']]);

    header("Location: lista_unica.php?id=" . $id);
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Editar lista</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<?php include 'parts/header.php'; ?>

<main class="container">
    <div class="card">
        <h2>Editar Lista</h2>

        <form method="post">

            <label>Nome da lista
                <input type="text" name="nome" required 
                       value="<?= htmlspecialchars($lst['nm_lst']) ?>">
            </label>

            <label>Descrição
                <textarea name="desc"><?= htmlspecialchars($lst['desc_txt']) ?></textarea>
            </label>

            <label>
                <input type="checkbox" name="publico" <?= $lst['publico'] ? "checked" : "" ?>>
                Lista pública
            </label>

            <div class="row">
                <button class="btn" type="submit">Salvar</button>
                <a href="lista_unica.php?id=<?= $id ?>" class="btn hollow">Cancelar</a>
            </div>

        </form>
    </div>
</main>

<?php include 'parts/footer.php'; ?>
</body>
</html>
