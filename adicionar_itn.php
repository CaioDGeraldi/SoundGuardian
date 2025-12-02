<?php
require 'cfg.php';
if(!esta_logado()){ header('Location: entrar.php'); exit; }
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $ttl = trim($_POST['ttl'] ?? '');
  $artista = trim($_POST['artista'] ?? '');
  $formato = $_POST['formato'] ?? 'Outro';
  $genero = trim($_POST['genero'] ?? '');
  $ano = $_POST['ano'] ?? null;
  $desc = trim($_POST['desc'] ?? '');
  if(!$ttl) $errors[]='Título obrigatório';
  $capaPath = null;
  if(!empty($_FILES['capa']['name'])){
    $up = $_FILES['capa'];
    $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION));
    $allow = ['jpg','jpeg','png','webp'];
    if($up['error']===0 and $up['size'] < 2*1024*1024 and in_array($ext,$allow)){
      $name = uniqid('capa_').'.'.$ext;
      $dest = 'uploads/'.$name;
      if(move_uploaded_file($up['tmp_name'],$dest)) $capaPath = $dest; else $errors[]='Erro mover arquivo';
    } else $errors[]='Erro no upload (jpg/png/webp até 2MB)';
  }
  if(empty($errors)){
    $ins = $pdo->prepare('INSERT INTO itn (id_usr,ttl,artista,formato,genero,ano,desc_txt,capa) VALUES (?,?,?,?,?,?,?,?)');
    $ins->execute([$_SESSION['usr_id'],$ttl,$artista,$formato,$genero,$ano,$desc,$capaPath]);
    header('Location: painel.php'); exit;
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Adicionar Item</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'parts/header.php'; ?>
<main class="container"><div class="card">
  <h2>Adicionar Item</h2>
  <?php if($errors): ?><div class="err"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Título<input name="ttl" required></label>
    <label>Artista<input name="artista"></label>
    <label>Formato<select name="formato"><option>Vinil</option><option>CD</option><option>BOXSET</option><option>Livreto</option><option>Outro</option></select></label>
    <label>Gênero<input name="genero"></label>
    <label>Ano<input type="number" name="ano" min="1900" max="2100"></label>
    <label>Descrição<textarea name="desc"></textarea></label>
    <label>Capa<input type="file" name="capa" accept="image/*"></label>
    <div class="row"><button class="btn" type="submit">Salvar</button> <a class="btn hollow" href="painel.php">Cancelar</a></div>
  </form>
</div></main><?php include 'parts/footer.php'; ?></body></html>