<?php

require 'cfg.php';

if (!esta_logado()) {
    header('Location: entrar.php');
    exit;
}

$uid = $_SESSION['usr_id'];
$id_itn = intval($_GET['id'] ?? 0);
if (!$id_itn) {
    header('Location: painel.php');
    exit;
}

try {
 
    $chk = $pdo->prepare("SELECT 1 FROM fav WHERE id_usr = ? AND id_itn = ?");
    $chk->execute([$uid, $id_itn]);

    $isFav = $chk->fetch() ? true : false;

    if ($isFav) {
       
        $del = $pdo->prepare("DELETE FROM fav WHERE id_usr = ? AND id_itn = ?");
        $del->execute([$uid, $id_itn]);
    } else {
       
        $ins = $pdo->prepare("INSERT INTO fav (id_usr, id_itn) VALUES (?, ?)");
        $ins->execute([$uid, $id_itn]);
    }


    $q = $pdo->prepare("SELECT id_lst FROM lst WHERE id_usr = ? AND nm_lst = 'Favoritos' LIMIT 1");
    $q->execute([$uid]);
    $favList = $q->fetchColumn();

    if (!$favList) {

        $create = $pdo->prepare("INSERT INTO lst (id_usr, nm_lst, desc_txt, publico) VALUES (?, 'Favoritos', 'Itens favoritados automaticamente', 0)");
        $create->execute([$uid]);
        $favList = $pdo->lastInsertId();
    }

    if ($isFav) {
     
        $rm = $pdo->prepare("DELETE FROM lst_itn WHERE id_lst = ? AND id_itn = ?");
        $rm->execute([$favList, $id_itn]);
    } else {
       
        $add = $pdo->prepare("INSERT IGNORE INTO lst_itn (id_lst, id_itn) VALUES (?, ?)");
        $add->execute([$favList, $id_itn]);
    }

} catch (Exception $e) {
    error_log("fav_toggle.php error: ".$e->getMessage());

}


header("Location: ver_itn.php?id=" . $id_itn);
exit;
