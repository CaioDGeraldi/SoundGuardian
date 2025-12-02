<?php

require 'cfg.php';

if (!esta_logado()) {
    header('Location: entrar.php');
    exit;
}

$uid = $_SESSION['usr_id'];
$id_itn = intval($_POST['id_itn'] ?? 0);
$texto = trim($_POST['texto'] ?? '');

if (!$id_itn || $texto === '') {

    header("Location: ver_itn.php?id=" . $id_itn);
    exit;
}

try {
    $ins = $pdo->prepare("INSERT INTO cmntr (id_usr, id_itn, txt) VALUES (?, ?, ?)");
    $ins->execute([$uid, $id_itn, $texto]);
} catch (Exception $e) {

    error_log("comentar.php error: " . $e->getMessage());
}


header("Location: ver_itn.php?id=" . $id_itn);
exit;
