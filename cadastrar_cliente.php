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
    
    $acao = isset($_GET['acao']) ? $_GET['acao'] : (isset($_POST['acao']) ? $_POST['acao'] : '');
    
    switch ($acao) {
        case 'novo':
            cadastrarNovoCliente($conn);
            break;
        
        case 'importar_arquivo':
            importarArquivoCSV($conn);
            break;
        
        case 'importar_contatos':
            importarContatosCSV($conn);
            break;
        
        case 'listar':
            listarClientes($conn);
            break;
        
        case 'deletar':
            deletarCliente($conn);
            break;
        
        default:
            echo json_encode(['sucesso' => false, 'erro' => 'Ação inválida']);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}

/**
 * Cadastrar novo cliente
 */
function cadastrarNovoCliente($conn) {
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $secretaria = isset($_POST['secretaria']) ? trim($_POST['secretaria']) : '';
    
    if (empty($telefone) || empty($nome)) {
        echo json_encode(['sucesso' => false, 'erro' => 'Telefone e nome são obrigatórios']);
        return;
    }
    
    // Limpar telefone para padronizar
    $telefoneLimpo = preg_replace('/[^0-9]/', '', $telefone);
    
    // Verificar se já existe
    $sql = "SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', '') = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $telefoneLimpo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'erro' => 'Este telefone já está cadastrado']);
        $stmt->close();
        return;
    }
    $stmt->close();
    
    // Inserir novo cliente
    $sql = "INSERT INTO clientes (telefone, nome, secretaria) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro na consulta: ' . $conn->error]);
        return;
    }
    
    $stmt->bind_param('sss', $telefone, $nome, $secretaria);
    
    if ($stmt->execute()) {
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Cliente cadastrado com sucesso',
            'id' => $stmt->insert_id
        ]);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao cadastrar: ' . $stmt->error]);
    }
    
    $stmt->close();
}

/**
 * Importar arquivo CSV enviado
 */
function importarArquivoCSV($conn) {
    if (!isset($_FILES['arquivo'])) {
        echo json_encode(['sucesso' => false, 'erro' => 'Nenhum arquivo enviado']);
        return;
    }
    
    $arquivo = $_FILES['arquivo']['tmp_name'];
    $adicionados = 0;
    $duplicados = 0;
    
    if (!file_exists($arquivo)) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao fazer upload do arquivo']);
        return;
    }
    
    $fp = fopen($arquivo, 'r');
    if (!$fp) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao ler o arquivo']);
        return;
    }
    
    while (($linha = fgetcsv($fp, 0, ',')) !== false) {
        if (count($linha) < 2) continue; // Pular linhas incompletas
        
        $telefone = trim($linha[0]);
        $nome = isset($linha[1]) ? trim($linha[1]) : '';
        $secretaria = isset($linha[2]) ? trim($linha[2]) : '';
        
        if (empty($telefone) || empty($nome)) continue;
        
        // Formatar telefone
        $telefoneFormatado = formatarTelefone($telefone);
        
        // Verificar se já existe
        $telefoneLimpo = preg_replace('/[^0-9]/', '', $telefone);
        $sql = "SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', '') = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $telefoneLimpo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $duplicados++;
            $stmt->close();
            continue;
        }
        $stmt->close();
        
        // Inserir cliente
        $sql = "INSERT INTO clientes (telefone, nome, secretaria) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $telefoneFormatado, $nome, $secretaria);
        
        if ($stmt->execute()) {
            $adicionados++;
        }
        
        $stmt->close();
    }
    
    fclose($fp);
    
    echo json_encode([
        'sucesso' => true,
        'adicionados' => $adicionados,
        'duplicados' => $duplicados,
        'mensagem' => "$adicionados clientes adicionados" . ($duplicados > 0 ? ", $duplicados duplicados ignorados" : '')
    ]);
}

/**
 * Importar arquivo contatos.csv do servidor
 */
function importarContatosCSV($conn) {
    $caminhos = [
        'Banco de dados/Banco de dados/contatos.csv',
        '../Banco de dados/Banco de dados/contatos.csv',
        './Banco de dados/Banco de dados/contatos.csv'
    ];
    
    $arquivo = null;
    foreach ($caminhos as $caminho) {
        if (file_exists($caminho)) {
            $arquivo = $caminho;
            break;
        }
    }
    
    if (!$arquivo) {
        echo json_encode(['sucesso' => false, 'erro' => 'Arquivo contatos.csv não encontrado']);
        return;
    }
    
    $adicionados = 0;
    $duplicados = 0;
    
    $fp = fopen($arquivo, 'r');
    if (!$fp) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao ler o arquivo contatos.csv']);
        return;
    }
    
    while (($linha = fgetcsv($fp, 0, ',')) !== false) {
        if (count($linha) < 2) continue;
        
        $telefone = trim($linha[0]);
        $nome = isset($linha[1]) ? trim($linha[1]) : '';
        $secretaria = isset($linha[2]) ? trim($linha[2]) : '';
        
        if (empty($telefone) || empty($nome)) continue;
        
        // Formatar telefone com DDD brasileiro
        $telefoneLimpo = preg_replace('/[^0-9]/', '', $telefone);
        if (strlen($telefoneLimpo) == 10) {
            // Formato: 11 9xxxx-xxxx
            $telefoneFormatado = '(' . substr($telefoneLimpo, 0, 2) . ') ' . 
                                substr($telefoneLimpo, 2, 4) . '-' . 
                                substr($telefoneLimpo, 6);
        } else {
            $telefoneFormatado = $telefone;
        }
        
        // Verificar se já existe
        $sql = "SELECT id FROM clientes WHERE REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', '') = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $telefoneLimpo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $duplicados++;
            $stmt->close();
            continue;
        }
        $stmt->close();
        
        // Inserir cliente
        $sql = "INSERT INTO clientes (telefone, nome, secretaria) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $telefoneFormatado, $nome, $secretaria);
        
        if ($stmt->execute()) {
            $adicionados++;
        }
        
        $stmt->close();
    }
    
    fclose($fp);
    
    echo json_encode([
        'sucesso' => true,
        'adicionados' => $adicionados,
        'duplicados' => $duplicados,
        'mensagem' => "$adicionados clientes adicionados" . ($duplicados > 0 ? ", $duplicados duplicados ignorados" : '')
    ]);
}

/**
 * Listar clientes
 */
function listarClientes($conn) {
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    
    if (!empty($buscar)) {
        $buscar = '%' . $buscar . '%';
        $sql = "SELECT id, telefone, nome, secretaria, data_cadastro FROM clientes 
                WHERE nome LIKE ? OR telefone LIKE ? 
                ORDER BY nome ASC 
                LIMIT 100";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $buscar, $buscar);
    } else {
        $sql = "SELECT id, telefone, nome, secretaria, data_cadastro FROM clientes 
                ORDER BY data_cadastro DESC 
                LIMIT 100";
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
    
    // Contar total
    $sqlTotal = "SELECT COUNT(*) as total FROM clientes";
    $resultTotal = $conn->query($sqlTotal);
    $total = $resultTotal->fetch_assoc()['total'];
    
    $stmt->close();
    
    echo json_encode([
        'sucesso' => true,
        'clientes' => $clientes,
        'total' => $total
    ]);
}

/**
 * Deletar cliente
 */
function deletarCliente($conn) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id <= 0) {
        echo json_encode(['sucesso' => false, 'erro' => 'ID inválido']);
        return;
    }
    
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Cliente deletado']);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao deletar: ' . $stmt->error]);
    }
    
    $stmt->close();
}

/**
 * Formatar telefone para padrão brasileiro
 */
function formatarTelefone($telefone) {
    $limpo = preg_replace('/[^0-9]/', '', $telefone);
    
    if (strlen($limpo) == 10) {
        // DDD + número (sem 9)
        return '(' . substr($limpo, 0, 2) . ') ' . 
               substr($limpo, 2, 4) . '-' . 
               substr($limpo, 6);
    } elseif (strlen($limpo) == 11) {
        // DDD + 9 + número
        return '(' . substr($limpo, 0, 2) . ') ' . 
               substr($limpo, 2, 5) . '-' . 
               substr($limpo, 7);
    }
    
    return $telefone; // Retorna como estava se não conseguir formatar
}
?>
