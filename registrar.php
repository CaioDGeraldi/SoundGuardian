<?php
require 'cfg.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $nm = trim($_POST['nm'] ?? '');
  $eml = filter_var($_POST['eml'] ?? '', FILTER_VALIDATE_EMAIL);
  $pwd = $_POST['pwd'] ?? '';
  $pwd2 = $_POST['pwd2'] ?? '';
  if(!$nm) $errors[]='Nome obrigatório';
  if(!$eml) $errors[]='Email inválido';
  if(strlen($pwd) < 6) $errors[]='Senha mínimo 6 caracteres';
  if($pwd !== $pwd2) $errors[]='Senhas não conferem';
  if(empty($errors)){
    $stmt = $pdo->prepare('SELECT id_usr FROM usr WHERE eml = ? OR nm = ?');
    $stmt->execute([$eml,$nm]);
    if($stmt->fetch()) $errors[]='Email ou nome já em uso';
    else {
      $hash = password_hash($pwd, PASSWORD_DEFAULT);
      $ins = $pdo->prepare('INSERT INTO usr (nm,eml,pwd) VALUES (?,?,?)');
      $ins->execute([$nm,$eml,$hash]);
      header('Location: entrar.php?reg=1'); exit;
    }
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Registrar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="page-form"><div class="card">
  <h2>Registrar</h2>
  <?php if($errors): ?><div class="err"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
  <form method="post">
    <label>Nome<input name="nm" required></label>
    <label>Email<input type="email" name="eml" required></label>
    <label>Senha<input type="password" name="pwd" required></label>
    <label>Confirmar senha<input type="password" name="pwd2" required></label>
    <div class="row"><button class="btn" type="submit">Cadastrar</button> <a class="btn hollow" href="entrar.php">Já tenho conta</a></div>
  </form>
</div></body></html>