<?php
require 'cfg.php';
if(!esta_logado()){ header('Location: entrar.php'); exit; }
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nm = trim($_POST['nm'] ?? ''); $desc = trim($_POST['desc'] ?? ''); $publico = isset($_POST['publico']) ? 1 : 0;
  if(!$nm) $errors[]='Nome da lista necessário';
  if(empty($errors)){
    $ins = $pdo->prepare('INSERT INTO lst (id_usr,nm_lst,desc_txt,publico) VALUES (?,?,?,?)');
    $ins->execute([$_SESSION['usr_id'],$nm,$desc,$publico]);
    header('Location: ver_lst.php'); exit;
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Criar lista</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'parts/header.php'; ?><main class="container"><div class="card"><h2>Criar lista</h2><?php if($errors): ?><div class="err"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?><form method="post"><label>Nome<input name="nm" required></label><label>Descrição<textarea name="desc"></textarea></label><label><input type="checkbox" name="publico"> Tornar pública</label><div class="row"><button class="btn" type="submit">Criar</button> <a class="btn hollow" href="ver_lst.php">Cancelar</a></div></form></div></main><?php include 'parts/footer.php'; ?></body></html>