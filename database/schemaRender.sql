-- Criação do banco (em PostgreSQL, o banco já é escolhido ao conectar)
-- CREATE DATABASE sistema_chamados;  ← Você cria isso direto no Render

-- Criação da tabela clientes
CREATE TABLE IF NOT EXISTS clientes (
  id SERIAL PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email_portal VARCHAR(100),
  senha_portal VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  -- PostgreSQL usa índices criados separadamente
  CONSTRAINT idx_nome UNIQUE (nome)
);

-- Criação da tabela chamados
-- Primeiro, criamos o tipo ENUM (ou você pode usar apenas TEXT com validação no app)
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'status_chamado') THEN
    CREATE TYPE status_chamado AS ENUM ('Aberto', 'Pendente Cliente', 'Aguardando Validação', 'Resolvido');
  END IF;
END
$$;

CREATE TABLE IF NOT EXISTS chamados (
  id SERIAL PRIMARY KEY,
  cliente_id INTEGER NOT NULL REFERENCES clientes(id) ON DELETE RESTRICT,
  numero_chamado VARCHAR(50) NOT NULL,
  titulo VARCHAR(100) NOT NULL,
  status status_chamado NOT NULL DEFAULT 'Aberto',
  status_execucao TEXT,
  ultima_verificacao TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices adicionais (em PostgreSQL não se usam no CREATE TABLE diretamente)
CREATE INDEX IF NOT EXISTS idx_status ON chamados(status);
CREATE INDEX IF NOT EXISTS idx_data_criacao ON chamados(created_at);
CREATE INDEX IF NOT EXISTS idx_cliente ON chamados(cliente_id);
CREATE INDEX IF NOT EXISTS idx_titulo ON chamados(titulo);
CREATE INDEX IF NOT EXISTS idx_numero_chamado ON chamados(numero_chamado);
