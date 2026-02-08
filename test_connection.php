<?php
header('Content-Type: application/json');

$test = isset($_GET['test']) ? $_GET['test'] : 'php';

if ($test === 'php') {
    // Teste de PHP
    echo json_encode([
        'success' => true,
        'php_version' => phpversion(),
        'message' => 'PHP está funcionando'
    ]);
} elseif ($test === 'db') {
    // Teste de banco de dados
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'ecommerce';
    
    $conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn) {
        echo json_encode([
            'success' => true,
            'database' => $db_name,
            'message' => 'Conexão ao banco de dados estabelecida'
        ]);
        mysqli_close($conn);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Erro ao conectar: ' . mysqli_connect_error(),
            'message' => 'Verifique as credenciais e se o MySQL está rodando'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Teste inválido'
    ]);
}
?>
