# Sistema de Comandas - Delícias da Cintia

Sistema web para gerenciar e imprimir comandas de restaurante em impressora térmica.

## 📋 Pré-requisitos

- **PHP** 7.4 ou superior
- **MySQL** ou **MariaDB**
- **Servidor Web** (Apache, Nginx ou embutido do PHP)
- **Navegador moderno** com suporte a impressão

## 🚀 Instalação

### 1. Configurar o Banco de Dados

#### Opção A: Via MySQL CLI
```bash
mysql -u root -p < "Banco de dados/setup_banco.sql"
```

#### Opção B: Via phpMyAdmin
1. Acesse phpMyAdmin
2. Crie um novo banco chamado `ecommerce`
3. Importe o arquivo `setup_banco.sql`

### 2. Configurar Credenciais do Banco

Edite o arquivo `action_PAGE.php` e altere as credenciais:

```php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';        // Sua senha do MySQL
$db_name = 'ecommerce';
```

## 🏃 Executando o Sistema

### Opção 1: Usando o Servidor Embutido do PHP (Recomendado para Desenvolvimento)

```bash
cd "c:\Users\BRUNO\Downloads\Site-Cintia-main\sistemas"
php -S localhost:8000
```

Depois acesse: **http://localhost:8000/Frontend/PageOne.html**

### Opção 2: Usando Apache

1. Coloque a pasta `sistemas` no diretório `htdocs` do Apache
2. Acesse: **http://localhost/sistemas/Frontend/PageOne.html**

### Opção 3: Usando Python (Se não tiver PHP instalado ainda)

Para visualizar apenas o HTML/CSS (sem funcionalidade de banco de dados):

```bash
cd "c:\Users\BRUNO\Downloads\Site-Cintia-main\sistemas\Frontend"
python -m http.server 8000
```

Acesse: **http://localhost:8000/PageOne.html**

## 📝 Como Usar

### 1. **Preenchimento do Formulário**
   - Digite o **telefone do cliente** (ex: (11) 98765-4321)
   - O sistema buscará automaticamente o cliente no banco de dados
   - Os campos "Nome" e "Secretaria" serão preenchidos automaticamente

### 2. **Seleção do Pedido**
   - Escolha a **Proteína Principal** (obrigatório)
   - Selecione os **Acompanhamentos** desejados
   - Escolha as **Saladas** desejadas

### 3. **Finalizar Pedido**
   - Clique em "✓ Finalizar Pedido"
   - A comanda será gerada e exibida pronta para impressão

### 4. **Imprimir Comanda**
   - Clique no botão "🖨️ Imprimir Comanda"
   - Configure a impressora (ideal: térmica 80mm)
   - Clique em "Imprimir"

## 🖨️ Configuração da Impressora Térmica

### Para Impressoras Térmicas 80mm:

1. **Nas Configurações de Impressão:**
   - Tamanho do papel: **80mm**
   - Orientação: **Retrato**
   - Margens: **Nenhuma** (0mm)
   - Escala: **100%**

2. **No Driver da Impressora:**
   - Remova cabeçalhos e rodapés
   - Desative cores (se preferir preto e branco)

### Dica de Teste:
Se não tiver impressora térmica, teste com "Imprimir para PDF" primeiro.

## 📚 Estrutura de Arquivos

```
sistemas/
├── Frontend/
│   ├── PageOne.html          # Interface principal
│   └── Atalhos.css           # Estilos CSS
├── action_PAGE.php           # Backend - processa pedidos
├── Banco de dados/
│   └── setup_banco.sql       # Script para criar banco de dados
└── README.md                 # Este arquivo
```

## 🔧 Funcionalidades

✅ **Busca automática de clientes** pelo telefone  
✅ **Preenchimento automático** de dados do cliente  
✅ **Validação de formulário** antes de enviar  
✅ **Geração de comanda** formatada para impressora térmica  
✅ **Histórico de pedidos** salvos no banco de dados  
✅ **Interface responsiva** e amigável  
✅ **Suporte para múltiplos acompanhamentos e saladas**

## 🐛 Troubleshooting

### Erro: "Conexão recusada" ao iniciar o servidor
- Verifique se PHP está instalado: `php --version`
- Se não tiver, baixe em: https://www.php.net/downloads

### Erro: "Cliente não encontrado"
- Verifique se o banco de dados foi criado corretamente
- Confirme que o MySQL está rodando
- Adicione mais clientes ao banco se necessário

### Impressora não funciona
- Teste primeiro com "Imprimir para PDF"
- Verifique as configurações de papel (80mm)
- Teste com uma página de configuração de impressora

### Formulário não envia dados
- Verifique se o PHP está rodando corretamente
- Abra o console do navegador (F12) e veja os erros
- Verifique o caminho do arquivo `action_PAGE.php`

## 📞 Próximas Melhorias

- [ ] Sistema de autenticação de usuários
- [ ] Histórico completo de pedidos
- [ ] Integração com mais tipos de impressoras
- [ ] Dashboard de vendas
- [ ] Aplicativo mobile
- [ ] API REST para integrações

## 👨‍💻 Suporte

Para dúvidas ou problemas, verifique os logs do PHP ou abra o console do navegador (F12) para ver erros.

---

**Desenvolvido para Restaurante Delícias da Cintia**
