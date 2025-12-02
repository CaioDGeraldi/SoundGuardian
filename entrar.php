<?php
require 'cfg.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $user = trim($_POST['user'] ?? '');
  $pwd = $_POST['pwd'] ?? '';
  if(!$user || !$pwd) $errors[]='Preencha usuário/email e senha';
  else {
    $stmt = $pdo->prepare('SELECT id_usr,nm,pwd,adm FROM usr WHERE eml = ? OR nm = ? LIMIT 1');
    $stmt->execute([$user,$user]); $u = $stmt->fetch();
    if($u && password_verify($pwd, $u['pwd'])){
      session_regenerate_id(true);
      $_SESSION['usr_id'] = $u['id_usr'];
      $_SESSION['usr_nome'] = $u['nm'];
      $_SESSION['usr_admin'] = $u['adm'];
      header('Location: painel.php'); exit;
    } else $errors[]='Usuário ou senha inválidos';
  }
}
$reg = isset($_GET['reg']);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Entrar</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="page-form"><div class="card">
  <h2>Entrar</h2>
  <?php if($reg) echo '<div class="ok">Cadastro realizado. Faça login.</div>'; ?>
  <?php if($errors): ?><div class="err"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
  <form method="post">
    <label>Usuário ou Email<input name="user" required></label>
    <label>Senha<input type="password" name="pwd" required></label>
    <div class="row"><button class="btn" type="submit">Entrar</button> <a class="btn hollow" href="registrar.php">Registrar</a></div>
  </form>
</div></body></html>