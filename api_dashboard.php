<?php
header('Content-Type: application/json');

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ecommerce';

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
        throw new Exception("Erro de conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    // Buscar todas as comandas
    $sql = "SELECT id, nome, telefone, proteina, data_hora, impresso FROM comandas ORDER BY data_hora DESC LIMIT 50";
    $result = $conn->query($sql);
    
    $comandas = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $comandas[] = $row;
        }
    }
    
    // Contar estatísticas
    $totalSQL = "SELECT COUNT(*) as total FROM comandas";
    $totalResult = $conn->query($totalSQL);
    $total = $totalResult->fetch_assoc()['total'];
    
    $impressasSQL = "SELECT COUNT(*) as impressas FROM comandas WHERE impresso = 1";
    $impressasResult = $conn->query($impressasSQL);
    $impressas = $impressasResult->fetch_assoc()['impressas'];
    
    $pendentes = $total - $impressas;
    
    $clientesSQL = "SELECT COUNT(DISTINCT telefone) as unicos FROM comandas";
    $clientesResult = $conn->query($clientesSQL);
    $clientes_unicos = $clientesResult->fetch_assoc()['unicos'];
    
    $conn->close();
    
    echo json_encode([
        'sucesso' => true,
        'comandas' => $comandas,
        'total' => $total,
        'impressas' => $impressas,
        'pendentes' => $pendentes,
        'clientes_unicos' => $clientes_unicos
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'erro' => $e->getMessage()
    ]);
}
?>
