-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Tabela de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telefone VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    secretaria VARCHAR(100),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de comandas
CREATE TABLE IF NOT EXISTS comandas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telefone VARCHAR(20) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    secretaria VARCHAR(100),
    proteina VARCHAR(50) NOT NULL,
    acompanhamentos TEXT,
    saladas TEXT,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    impresso BOOLEAN DEFAULT FALSE
);

-- Inserir alguns clientes de exemplo
INSERT INTO clientes (telefone, nome, secretaria) VALUES 
('(11) 98765-4321', 'João Silva', 'Vendas'),
('(11) 97654-3210', 'Maria Santos', 'Administrativo'),
('(11) 96543-2109', 'Pedro Costa', 'Financeiro'),
('(11) 95432-1098', 'Ana Paula', 'RH'),
('(11) 94321-0987', 'Carlos Ferreira', 'Vendas');
