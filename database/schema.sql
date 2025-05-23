CREATE DATABASE IF NOT EXISTS sistema_chamados;

USE sistema_chamados;

CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email_portal VARCHAR(100),
  senha_portal VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_nome (nome)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS chamados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  numero_chamado VARCHAR(50) NOT NULL,
  titulo VARCHAR(100) NOT NULL,
  status ENUM ('Aberto', 'Pendente Cliente', 'Aguardando Validação', 'Resolvido') NOT NULL DEFAULT 'Aberto',
  status_execucao TEXT,
  ultima_verificacao TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES clientes (id) ON DELETE RESTRICT,
  INDEX idx_status (status),
  INDEX idx_data_criacao (created_at),
  INDEX idx_cliente (cliente_id),
  INDEX idx_titulo (titulo),
  INDEX idx_numero_chamado (numero_chamado)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;