<?php
require 'cfg.php';
if (!esta_logado()) { header("Location: entrar.php"); exit; }

$uid = $_SESSION['usr_id'];
$id = intval($_GET['id'] ?? 0);


$stmt = $pdo->prepare("SELECT * FROM lst WHERE id_lst = ? AND id_usr = ?");
$stmt->execute([$id, $uid]);
$lst = $stmt->fetch();

if (!$lst) die("Playlist não encontrada.");
if ($lst['nm_lst'] === "Favoritos") die("A playlist Favoritos não pode ser deletada.");


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 
    $del_rel = $pdo->prepare("DELETE FROM lst_itn WHERE id_lst = ?");
    @ $del_rel->execute([$id]);


    $del = $pdo->prepare("DELETE FROM lst WHERE id_lst = ? AND id_usr = ?");
    $del->execute([$id, $uid]);

    header("Location: perfil.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Excluir Playlist</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'parts/header.php'; ?>

<main class="container">
    <div class="card">
        <h2>Excluir Playlist</h2>

        <p>Tem certeza que deseja excluir a playlist <strong><?= htmlspecialchars($lst['nm_lst']) ?></strong>?</p>

        <form method="post">
            <button class="btn" type="submit">Sim, excluir</button>
            <a class="btn hollow" href="lista_unica.php?id=<?= $id ?>">Cancelar</a>
        </form>
    </div>
</main>

<?php include 'parts/footer.php'; ?>

</body>
</html>
