-- banco e tabelas para importar no phpMyAdmin
CREATE DATABASE IF NOT EXISTS croche_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE croche_db;

CREATE TABLE IF NOT EXISTS produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2),
  imagem VARCHAR(255),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  nome VARCHAR(100) NULL,
  tentativas INT DEFAULT 0,
  bloqueado_ate DATETIME NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
  tentativas_login INT DEFAULT 0,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- registros de exemplo (produtos)
INSERT INTO produtos (nome, descricao, preco, imagem) VALUES
('Bolsa Bege', 'Bolsa de crochê bege feita à mão', 299.90, 'bolsa1.jpg'),
('Bolsa Azul', 'Bolsa de crochê azul com alça reforçada', 259.90, 'bolsa2.jpg');

-- NOTA: usuário admin será criado por script PHP (criar_admin.php). 
