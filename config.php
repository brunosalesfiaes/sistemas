<?php
/**
 * Arquivo de Configuração do Sistema de Comandas
 * Configure suas credenciais de banco de dados aqui
 */

// ===== CONFIGURAÇÕES DO BANCO DE DADOS =====
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');              // Deixe vazio se não tiver senha
define('DB_NAME', 'ecommerce');
define('DB_PORT', 3306);

// ===== CONFIGURAÇÕES DA APLICAÇÃO =====
define('RESTAURANT_NAME', 'Delícias da Cintia');
define('PAPER_WIDTH', '80mm');      // Largura do papel térmico

// ===== CONFIGURAÇÕES DE DEBUG =====
define('DEBUG_MODE', true);         // Mude para false em produção

/**
 * Função auxiliar para conectar ao banco de dados
 */
function conectarBanco() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    if ($conn->connect_error) {
        if (DEBUG_MODE) {
            die("Erro de conexão: " . $conn->connect_error);
        } else {
            die("Erro ao conectar ao banco de dados. Por favor, tente mais tarde.");
        }
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

/**
 * Função para retornar JSON com erro
 */
function erro($mensagem, $codigo = 500) {
    http_response_code($codigo);
    echo json_encode(['erro' => $mensagem]);
    exit;
}

/**
 * Função para retornar JSON com sucesso
 */
function sucesso($dados) {
    header('Content-Type: application/json');
    echo json_encode($dados);
    exit;
}
?>
