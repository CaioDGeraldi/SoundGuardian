<?php

session_start();
$DB_HOST = '127.0.0.1';
$DB_NAME = 'soundguardian';
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHAR = 'utf8mb4';
$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHAR}";
$options = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC];
try{ $pdo = new PDO($dsn,$DB_USER,$DB_PASS,$options); } catch(PDOException $e){ die('Erro DB: '.$e->getMessage()); }
function esta_logado(){ return !empty($_SESSION['usr_id']); }
function usuario_nome(){ return $_SESSION['usr_nome'] ?? null; }
function eh_admin(){ return !empty($_SESSION['usr_admin']); }
?>