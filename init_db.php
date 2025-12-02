<?php
// init_db.php - cria database e tabelas necessárias
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'colecao';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die('Conexão falhou: ' . $mysqli->connect_error);
}

// Create database if not exists
if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die('Falha ao criar database: ' . $mysqli->error);
}
$mysqli->select_db($dbname);

// colecionaveis
$sql = "CREATE TABLE IF NOT EXISTS colecionaveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Vinil','CD','BoxSet','Livreto') NOT NULL DEFAULT 'Vinil',
    nome VARCHAR(255) NOT NULL,
    artista VARCHAR(255) NOT NULL,
    genero VARCHAR(100) DEFAULT NULL,
    gravadora VARCHAR(255) DEFAULT NULL,
    edicao VARCHAR(100) DEFAULT NULL,
    ano YEAR DEFAULT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    autografado TINYINT(1) DEFAULT 0,
    certificado VARCHAR(255) DEFAULT NULL,
    importado TINYINT(1) DEFAULT 0,
    pais VARCHAR(100) DEFAULT NULL,
    primeira TINYINT(1) DEFAULT 0,
    lacrado TINYINT(1) DEFAULT 0,
    encarte TINYINT(1) DEFAULT 0,
    poster TINYINT(1) DEFAULT 0,
    obi TINYINT(1) DEFAULT 0,
    hyper TINYINT(1) DEFAULT 0,
    anotacoes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if (!$mysqli->query($sql)) { die('Falha colecionaveis: ' . $mysqli->error); }

// lists: user lists grouping items (no auth => simple lists)
$sql = "CREATE TABLE IF NOT EXISTS listas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sql);

// lista_items: relaciona listas com colecionaveis
$sql = "CREATE TABLE IF NOT EXISTS lista_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lista_id INT NOT NULL,
    item_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lista_id) REFERENCES listas(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES colecionaveis(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sql);

// wishlist: tabela simples para itens de desejo
$sql = "CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    nota VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES colecionaveis(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$mysqli->query($sql);

echo 'Banco e tabelas criados/atualizados com sucesso.';
$mysqli->close();
?>