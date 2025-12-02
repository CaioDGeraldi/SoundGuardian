<?php
require 'cfg.php';
if(!esta_logado()){ header('Location: entrar.php'); exit; }
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM itn WHERE id_itn = ? LIMIT 1'); $stmt->execute([$id]); $it = $stmt->fetch();
if(!$it) die('Item não encontrado');
if($it['id_usr'] != $_SESSION['usr_id'] && !eh_admin()) die('Sem permissão');
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $ttl = trim($_POST['ttl'] ?? ''); $artista = trim($_POST['artista'] ?? ''); $formato = $_POST['formato'] ?? 'Outro';
  $genero = trim($_POST['genero'] ?? ''); $ano = $_POST['ano'] ?? null; $desc = trim($_POST['desc'] ?? '');
  if(!$ttl) $errors[]='Título obrigatório';
  $capaPath = $it['capa'];
  if(!empty($_FILES['capa']['name'])){
    $up = $_FILES['capa']; $ext = strtolower(pathinfo($up['name'], PATHINFO_EXTENSION)); $allow=['jpg','jpeg','png','webp'];
    if($up['error']===0 and $up['size'] < 2*1024*1024 and in_array($ext,$allow)){
      $name=uniqid('capa_').'.'.$ext; $dest='uploads/'.$name;
      if(move_uploaded_file($up['tmp_name'],$dest)) $capaPath = $dest; else $errors[]='Erro mover arquivo';
    } else $errors[]='Erro no upload (jpg/png/webp até 2MB)';
  }
  if(empty($errors)){
    $upd = $pdo->prepare('UPDATE itn SET ttl=?,artista=?,formato=?,genero=?,ano=?,desc_txt=?,capa=? WHERE id_itn=?');
    $upd->execute([$ttl,$artista,$formato,$genero,$ano,$desc,$capaPath,$id]);
    header('Location: ver_itn.php?id='.$id); exit;
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Editar Item</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body><?php include 'parts/header.php'; ?>
<main class="container"><div class="card">
  <h2>Editar Item</h2>
  <?php if($errors): ?><div class="err"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Título<input name="ttl" value="<?=htmlspecialchars($it['ttl'])?>" required></label>
    <label>Artista<input name="artista" value="<?=htmlspecialchars($it['artista'])?>"></label>
    <label>Formato<select name="formato"><?php foreach(['Vinil','CD','BOXSET','Livreto','Outro'] as $f): ?><option <?=($it['formato']==$f?'selected':'')?>><?=$f?></option><?php endforeach;?></select></label>
    <label>Gênero<input name="genero" value="<?=htmlspecialchars($it['genero'])?>"></label>
    <label>Ano<input type="number" name="ano" min="1900" max="2100" value="<?=htmlspecialchars($it['ano'])?>"></label>
    <label>Descrição<textarea name="desc"><?=htmlspecialchars($it['desc_txt'])?></textarea></label>
    <label>Capa<input type="file" name="capa"></label>
    <div class="row"><button class="btn" type="submit">Salvar</button> <a class="btn hollow" href="ver_itn.php?id=<?=$it['id_itn']?>">Cancelar</a></div>
  </form>
</div></main><?php include 'parts/footer.php'; ?></body></html>