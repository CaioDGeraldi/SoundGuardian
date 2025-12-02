<?php
require 'cfg.php';
if(!esta_logado()){ header('Location: entrar.php'); exit; }
$seg = intval($_GET['seg'] ?? 0); $me = $_SESSION['usr_id'];
if($seg === $me) { header('Location: perfil.php?id='.$seg); exit; }
$stmt = $pdo->prepare('SELECT id_seg FROM seg WHERE seguidor = ? AND seguido = ?');
$stmt->execute([$me,$seg]);
if($stmt->fetch()){ $del = $pdo->prepare('DELETE FROM seg WHERE seguidor = ? AND seguido = ?'); $del->execute([$me,$seg]); }
else { $ins = $pdo->prepare('INSERT IGNORE INTO seg (seguidor,seguido) VALUES (?,?)'); $ins->execute([$me,$seg]); }
header('Location: perfil.php?id='.$seg); exit;
?>