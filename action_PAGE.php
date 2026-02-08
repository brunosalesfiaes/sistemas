<?php
header('Content-Type: application/json');

// Configurações do banco de dados
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ecommerce';

try {
    // Conectar ao banco de dados
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
        throw new Exception("Erro de conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    // Verificar qual ação será executada
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    
    if ($action === 'buscar_cliente') {
        buscarCliente($conn);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        processarPedido($conn);
    } else {
        http_response_code(400);
        echo json_encode(['erro' => 'Requisição inválida']);
    }
    
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}

/**
 * Buscar cliente pelo telefone
 */
function buscarCliente($conn) {
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    
    if (empty($telefone)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Telefone não informado']);
        return;
    }
    
    // Remover caracteres especiais do telefone
    $telefoneLimpo = preg_replace('/[^0-9]/', '', $telefone);
    
    $sql = "SELECT nome, secretaria FROM clientes WHERE REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', '') LIKE ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na consulta: ' . $conn->error]);
        return;
    }
    
    $searchTerm = '%' . $telefoneLimpo . '%';
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'sucesso' => true,
            'nome' => $row['nome'],
            'secretaria' => $row['secretaria']
        ]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Cliente não encontrado']);
    }
    
    $stmt->close();
}

/**
 * Processar pedido e gerar comanda
 */
function processarPedido($conn) {
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $secretaria = isset($_POST['secretaria']) ? trim($_POST['secretaria']) : '';
    $proteina = isset($_POST['proteina']) ? trim($_POST['proteina']) : '';
    $acompanhamentos = isset($_POST['acompanhamento']) ? $_POST['acompanhamento'] : [];
    $saladas = isset($_POST['salada']) ? $_POST['salada'] : [];
    
    // Validações
    if (empty($telefone) || empty($nome) || empty($proteina)) {
        http_response_code(400);
        echo json_encode(['erro' => 'Campos obrigatórios não preenchidos']);
        return;
    }
    
    // Criar tabela de comandas se não existir
    criarTabelaComandas($conn);
    
    // Preparar dados para inserção
    $acompanhamentosStr = implode(', ', $acompanhamentos);
    $saladastStr = implode(', ', $saladas);
    $data_hora = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO comandas (telefone, nome, secretaria, proteina, acompanhamentos, saladas, data_hora) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro na preparação da query: ' . $conn->error]);
        return;
    }
    
    $stmt->bind_param('sssssss', $telefone, $nome, $secretaria, $proteina, $acompanhamentosStr, $saladastStr, $data_hora);
    
    if ($stmt->execute()) {
        $id_comanda = $stmt->insert_id;
        
        // Buscar dados completos do cliente (incluindo de clientes.csv se houver)
        // Primeiramente tenta encontrar pelo telefone exato, depois tenta sem formatação
        $telefoneLimpo = preg_replace('/[^0-9]/', '', $telefone);
        $sqlCliente = "SELECT nome, secretaria FROM clientes WHERE REPLACE(REPLACE(REPLACE(telefone, '(', ''), ')', ''), '-', '') = ?";
        $stmtCliente = $conn->prepare($sqlCliente);
        $stmtCliente->bind_param('s', $telefoneLimpo);
        $stmtCliente->execute();
        $resultCliente = $stmtCliente->get_result();
        
        if ($resultCliente->num_rows > 0) {
            $dadosCliente = $resultCliente->fetch_assoc();
            // Usar dados do banco se encontrar
            $nome_final = $dadosCliente['nome'];
            $secretaria_final = $dadosCliente['secretaria'];
        } else {
            // Usar dados do formulário se não encontrar
            $nome_final = $nome;
            $secretaria_final = $secretaria;
        }
        $stmtCliente->close();
        
        // Gerar HTML da comanda para impressão
        $htmlComanda = gerarComanda(
            $id_comanda,
            $telefone,
            $nome_final,
            $secretaria_final,
            $proteina,
            $acompanhamentos,
            $saladas,
            $data_hora
        );
        
        echo $htmlComanda;
        
    } else {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao salvar pedido: ' . $stmt->error]);
    }
    
    $stmt->close();
}

/**
 * Criar tabela de comandas se não existir
 */
function criarTabelaComandas($conn) {
    $sql = "CREATE TABLE IF NOT EXISTS comandas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        telefone VARCHAR(20) NOT NULL,
        nome VARCHAR(100) NOT NULL,
        secretaria VARCHAR(100),
        proteina VARCHAR(50) NOT NULL,
        acompanhamentos TEXT,
        saladas TEXT,
        data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
        impresso BOOLEAN DEFAULT FALSE
    )";
    
    if (!$conn->query($sql)) {
        throw new Exception("Erro ao criar tabela: " . $conn->error);
    }
}

/**
 * Gerar HTML da comanda para impressão térmica
 */
function gerarComanda($id, $telefone, $nome, $secretaria, $proteina, $acompanhamentos, $saladas, $data_hora) {
    // Mapear valores para nomes legíveis
    $proteinas = [
        'FT' => 'Frango com Toscana',
        'FB' => 'Frango com Barbecue',
        'SP' => 'Sem Proteína'
    ];
    
    $nomeProteina = isset($proteinas[$proteina]) ? $proteinas[$proteina] : $proteina;
    
    $acompanhamentosNomes = [
        'arroz' => 'Arroz',
        'arroz_temperado' => 'Arroz Temperado',
        'arroz_integral' => 'Arroz Integral',
        'macarrao' => 'Macarrão',
        'feijao_caldo' => 'Feijão de Caldo',
        'feijao_tropeiro' => 'Feijão Tropeiro',
        'pure_batata' => 'Purê de Batata',
        'farofa' => 'Farofa Crocante'
    ];
    
    $saladaNomes = [
        'sem_salada' => 'Sem Salada',
        'acelga' => 'Acelga',
        'repolho' => 'Repolho',
        'beterraba' => 'Beterraba',
        'legumes' => 'Legumes',
        'maionese' => 'Maionese'
    ];
    
    // Converter arrays para nomes
    $acompanhamentosFormatados = [];
    foreach ($acompanhamentos as $acomp) {
        if (isset($acompanhamentosNomes[$acomp])) {
            $acompanhamentosFormatados[] = $acompanhamentosNomes[$acomp];
        }
    }
    
    $saladascFormatadas = [];
    foreach ($saladas as $salada) {
        if (isset($saladaNomes[$salada])) {
            $saladascFormatadas[] = $saladaNomes[$salada];
        }
    }
    
    $dataFormatada = date('d/m/Y H:i', strtotime($data_hora));
    
    $html = <<<HTML
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda #$id</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 20px;
        }

        /* Seção de Confirmação */
        .confirmacao {
            background: white;
            max-width: 600px;
            margin: 0 auto 30px;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .confirmacao h2 {
            color: #2ECC71;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .info-box {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #FF6B35;
        }

        .info-box label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .info-box p {
            color: #666;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .resumo {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #004E89;
        }

        .resumo h3 {
            color: #004E89;
            margin-bottom: 10px;
        }

        .item-resumo {
            color: #333;
            margin: 5px 0;
            padding: 5px 0;
            border-bottom: 1px dotted #ccc;
        }

        .item-resumo:last-child {
            border-bottom: none;
        }

        .botoes-confirmacao {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-imprimir {
            background: #2ECC71;
            color: white;
        }

        .btn-imprimir:hover {
            background: #27AE60;
        }

        .btn-novo {
            background: #004E89;
            color: white;
        }

        .btn-novo:hover {
            background: #003A66;
        }
        
        .container {
            width: 80mm;
            max-width: 80mm;
            background: white;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #000;
        }
        
        @media print {
            body {
                padding: 0;
                background: white;
            }
            
            .container {
                width: 100%;
                max-width: 100%;
                border: none;
                margin: 0;
                padding: 0;
            }

            .confirmacao, .nao-imprimir {
                display: none;
            }
        }
        
        .cabecalho {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .cabecalho h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .cabecalho p {
            font-size: 10px;
            margin: 2px 0;
        }
        
        .numero-comanda {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            border: 2px solid #000;
            padding: 5px;
        }
        
        .secao {
            margin: 10px 0;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }
        
        .secao-titulo {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        
        .secao-conteudo {
            font-size: 10px;
            line-height: 1.4;
        }
        
        .cliente-info {
            font-size: 10px;
            margin: 5px 0;
        }
        
        .cliente-info strong {
            font-weight: bold;
        }
        
        .lista {
            font-size: 10px;
            line-height: 1.5;
            margin-left: 10px;
        }
        
        .lista-item {
            margin: 3px 0;
        }
        
        .rodape {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            font-size: 9px;
            border-top: 2px dashed #000;
        }
        
        .data-hora {
            font-size: 9px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- SEÇÃO DE CONFIRMAÇÃO -->
    <div class="confirmacao">
        <h2>✓ Pedido Confirmado!</h2>
        
        <div class="info-box">
            <label>📞 Telefone do Cliente</label>
            <p>$telefone</p>
        </div>

        <div class="info-box">
            <label>👤 Nome do Cliente</label>
            <p>$nome</p>
        </div>

        <div class="info-box">
            <label>📍 Local de Entrega / Secretaria</label>
            <p>$secretaria</p>
        </div>

        <div class="resumo">
            <h3>📋 Resumo do Pedido #$id</h3>
            <div class="item-resumo">
                <strong>Proteína:</strong> $nomeProteina
            </div>
HTML;

    // Adicionar acompanhamentos ao resumo
    if (!empty($acompanhamentosFormatados)) {
        $acompanh = implode(', ', $acompanhamentosFormatados);
        $html .= <<<HTML
            <div class="item-resumo">
                <strong>Acompanhamentos:</strong> $acompanh
            </div>
HTML;
    }

    // Adicionar saladas ao resumo
    if (!empty($saladascFormatadas)) {
        $saladas_str = implode(', ', $saladascFormatadas);
        $html .= <<<HTML
            <div class="item-resumo">
                <strong>Saladas:</strong> $saladas_str
            </div>
HTML;
    }

    $html .= <<<HTML
            <div class="item-resumo">
                <strong>Data/Hora:</strong> $dataFormatada
            </div>
        </div>

        <div class="botoes-confirmacao nao-imprimir">
            <button class="btn btn-imprimir" onclick="window.print()">🖨️ Imprimir Comanda</button>
            <button class="btn btn-novo" onclick="location.href='Frontend/PageOne.html'">➕ Novo Pedido</button>
        </div>
    </div>

    <!-- COMANDA PARA IMPRESSORA -->
    <div class="container">
        <div class="cabecalho">
            <h1>DELÍCIAS DA CINTIA</h1>
            <p>Restaurante</p>
        </div>
        
        <div class="numero-comanda">
            COMANDA #$id
        </div>
        
        <div class="secao">
            <div class="secao-titulo">CLIENTE</div>
            <div class="secao-conteudo">
                <div class="cliente-info"><strong>Nome:</strong> $nome</div>
                <div class="cliente-info"><strong>Local:</strong> $secretaria</div>
                <div class="cliente-info"><strong>Tel:</strong> $telefone</div>
            </div>
        </div>
        
        <div class="secao">
            <div class="secao-titulo">PROTEÍNA</div>
            <div class="secao-conteudo">
                <div class="lista">
                    <div class="lista-item">• $nomeProteina</div>
                </div>
            </div>
        </div>
HTML;

    if (!empty($acompanhamentosFormatados)) {
        $acompanh = implode('<br>• ', $acompanhamentosFormatados);
        $html .= <<<HTML
        
        <div class="secao">
            <div class="secao-titulo">ACOMPANHAMENTOS</div>
            <div class="secao-conteudo">
                <div class="lista">
                    <div class="lista-item">• $acompanh</div>
                </div>
            </div>
        </div>
HTML;
    }
    
    if (!empty($saladascFormatadas)) {
        $saladas_str = implode('<br>• ', $saladascFormatadas);
        $html .= <<<HTML
        
        <div class="secao">
            <div class="secao-titulo">SALADAS</div>
            <div class="secao-conteudo">
                <div class="lista">
                    <div class="lista-item">• $saladas_str</div>
                </div>
            </div>
        </div>
HTML;
    }
    
    $html .= <<<HTML
        
        <div class="rodape">
            <div class="data-hora">$dataFormatada</div>
            <p>Obrigado!</p>
        </div>
    </div>
</body>
</html>
HTML;

    return $html;
}
?>
