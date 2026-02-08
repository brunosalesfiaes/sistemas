-- ============================================
-- SCRIPT DE EXEMPLO: ADICIONAR CLIENTES
-- ============================================
-- Execute este arquivo para adicionar novos clientes ao banco

-- Método 1: SQL Direto
-- Descomente as linhas abaixo e execute:

/*
INSERT INTO clientes (telefone, nome, secretaria) VALUES 
('(11) 91111-1111', 'Cliente Teste 1', 'Vendas'),
('(11) 92222-2222', 'Cliente Teste 2', 'Financeiro'),
('(11) 93333-3333', 'Cliente Teste 3', 'Administrativo');
*/

-- Método 2: Se estiver usando arquivo CSV
-- LOAD DATA LOCAL INFILE 'clientes.csv'
-- INTO TABLE clientes
-- FIELDS TERMINATED BY ','
-- ENCLOSED BY '"'
-- LINES TERMINATED BY '\n'
-- IGNORE 1 LINES
-- (telefone, nome, secretaria);

-- ============================================
-- EXEMPLOS DE FORMATOS DE TELEFONE ACEITOS:
-- ============================================
-- (11) 98765-4321
-- 11 98765-4321
-- 11998765-4321
-- 11 9 8765-4321

-- ============================================
-- COMO USAR:
-- ============================================
-- 1. Copie as linhas de INSERT que você quer
-- 2. Retire os comentários (remova /*  */)
-- 3. Execute em:
--    - phpMyAdmin
--    - MySQL Command Line: mysql -u root -p ecommerce < arquivo.sql
--    - Seu IDE SQL favorito

-- ============================================
-- VALIDAÇÕES:
-- ============================================
-- - telefone: UNIQUE (não pode repetir)
-- - nome: VARCHAR(100) - máximo 100 caracteres
-- - secretaria: VARCHAR(100) - máximo 100 caracteres (pode ser NULL)

-- ============================================
-- CONSULTAR CLIENTES JÁ CADASTRADOS:
-- ============================================
-- SELECT * FROM clientes;
-- SELECT * FROM clientes WHERE nome LIKE '%Silva%';
-- SELECT * FROM clientes WHERE secretaria = 'Vendas';

-- ============================================
-- ATUALIZAR UM CLIENTE:
-- ============================================
-- UPDATE clientes 
-- SET nome = 'Novo Nome', secretaria = 'Novo Setor'
-- WHERE telefone = '(11) 98765-4321';

-- ============================================
-- DELETAR UM CLIENTE:
-- ============================================
-- DELETE FROM clientes WHERE telefone = '(11) 98765-4321';

-- ============================================
-- EXEMPLO DE ARQUIVO CSV (clientes.csv):
-- ============================================
-- telefone,nome,secretaria
-- (11) 98765-4321,João Silva,Vendas
-- (11) 97654-3210,Maria Santos,Administrativo
-- (11) 96543-2109,Pedro Costa,Financeiro
