<?php
try {
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar DB
    $pdo->exec("CREATE DATABASE IF NOT EXISTS soundguardian CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE soundguardian;");

    // Tabela usuários
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usr (
            id_usr INT AUTO_INCREMENT PRIMARY KEY,
            nm VARCHAR(80),
            eml VARCHAR(120) UNIQUE,
            pwd VARCHAR(255),
            adm TINYINT DEFAULT 0
        ) ENGINE=InnoDB;
    ");

    // Tabela itens
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS itn (
            id_itn INT AUTO_INCREMENT PRIMARY KEY,
            id_usr INT,
            ttl VARCHAR(120),
            desc_txt TEXT,
            img VARCHAR(200),
            tipo VARCHAR(20),
            favorito TINYINT DEFAULT 0,
            dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_usr) REFERENCES usr(id_usr) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    // Tabelas de listas
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS lst (
            id_lst INT AUTO_INCREMENT PRIMARY KEY,
            id_usr INT,
            nm VARCHAR(120),
            pub TINYINT DEFAULT 0,
            FOREIGN KEY(id_usr) REFERENCES usr(id_usr) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS lst_itn (
            id_lst INT,
            id_itn INT,
            PRIMARY KEY(id_lst, id_itn),
            FOREIGN KEY(id_lst) REFERENCES lst(id_lst) ON DELETE CASCADE,
            FOREIGN KEY(id_itn) REFERENCES itn(id_itn) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    // Favoritos
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS fav (
            id_usr INT,
            id_itn INT,
            PRIMARY KEY(id_usr, id_itn),
            FOREIGN KEY(id_usr) REFERENCES usr(id_usr) ON DELETE CASCADE,
            FOREIGN KEY(id_itn) REFERENCES itn(id_itn) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    // Comentários
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cmntr (
            id_cmntr INT AUTO_INCREMENT PRIMARY KEY,
            id_usr INT,
            id_itn INT,
            txt TEXT,
            dt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(id_usr) REFERENCES usr(id_usr) ON DELETE CASCADE,
            FOREIGN KEY(id_itn) REFERENCES itn(id_itn) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    // Seguidores (social)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS seg (
            id_usr_seguidor INT,
            id_usr_seguido INT,
            PRIMARY KEY(id_usr_seguidor, id_usr_seguido),
            FOREIGN KEY(id_usr_seguidor) REFERENCES usr(id_usr) ON DELETE CASCADE,
            FOREIGN KEY(id_usr_seguido) REFERENCES usr(id_usr) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    // Criar admin
    $adminEmail = "admin@sound.local";
    $adminPwd = password_hash("admin123", PASSWORD_DEFAULT);

    $pdo->exec("
        INSERT IGNORE INTO usr (nm, eml, pwd, adm)
        VALUES ('Administrador', '$adminEmail', '$adminPwd', 1)
    ");

    echo "<h2>✔ Banco de dados instalado com sucesso!</h2>";
    echo "<p>Admin: <b>$adminEmail</b><br>Senha: <b>admin123</b></p>";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
