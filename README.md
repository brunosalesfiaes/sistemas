# 🍽️ Site-Cintia - Sistema de Comandas

Sistema web completo para gerenciar e imprimir comandas de restaurante em impressoras térmicas.

## ✅ Status do Projeto

✅ **Back-end implementado** com PHP  
✅ **Banco de dados criado** com MySQL  
✅ **Conexão ao banco de dados** funcional  
✅ **Sistema de impressão** para impressoras térmicas  
✅ **Interface web responsiva** e intuitiva  
✅ **Busca automática de clientes** pelo telefone

## 🚀 Início Rápido

### Windows (Recomendado)
Simplesmente execute:
```bash
iniciar_servidor.bat
```

Ou via PowerShell:
```powershell
php -S localhost:8000
```

### Linux/Mac
```bash
php -S localhost:8000
```

Depois acesse: **http://localhost:8000/teste.html**

## 📋 Funcionalidades Principais

1. **Busca de Cliente**
   - Digite o telefone do cliente
   - Sistema busca automaticamente no banco de dados
   - Preenche nome e secretaria automaticamente

2. **Formulário de Pedido**
   - Seleção de proteína principal
   - Múltiplos acompanhamentos
   - Múltiplas saladas

3. **Geração de Comanda**
   - Formatação otimizada para impressora térmica (80mm)
   - Número único de comanda
   - Data e hora do pedido
   - Dados do cliente e pedido completo

4. **Impressão Térmica**
   - Suporte para impressoras 80mm
   - Formatação CSS específica para térmica
   - Testes de impressão com PDF

## 📁 Estrutura do Projeto

```
Site-Cintia/
├── Frontend/
│   ├── PageOne.html              # Interface principal
│   └── Atalhos.css               # Estilos CSS
├── Banco de dados/
│   └── setup_banco.sql           # Script de criação do banco
├── action_PAGE.php               # Backend - processa pedidos
├── config.php                    # Configurações
├── test_connection.php           # Testes de conexão
├── teste.html                    # Página de testes do sistema
├── iniciar_servidor.bat          # Script para iniciar (Windows)
├── SETUP_INSTRUCOES.md          # Instruções detalhadas
└── README.md                     # Este arquivo
```

## 🔧 Requisitos

- **PHP** 7.4+
- **MySQL** ou **MariaDB**
- **Navegador moderno** com suporte a impressão

## 📝 Configuração Inicial

### 1. Criar Banco de Dados
```bash
mysql -u root -p < "Banco de dados\setup_banco.sql"
```

### 2. Configurar Credenciais (se necessário)
Edite o arquivo `config.php` ou a variáveis em `action_PAGE.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce');
```

### 3. Iniciar Servidor
```bash
# Windows
iniciar_servidor.bat

# Ou manualmente
php -S localhost:8000
```

### 4. Acessar o Sistema
- **Aplicação**: http://localhost:8000/Frontend/PageOne.html
- **Testes**: http://localhost:8000/teste.html

## 🖨️ Impressoras Térmicas Suportadas

Qualquer impressora térmica 80mm compatível com:
- Windows
- Drivers padrão de impressão
- Suporte a CSS de impressão

## 📱 Clientes de Exemplo

O banco de dados vem com alguns clientes cadastrados:
- João Silva - (11) 98765-4321
- Maria Santos - (11) 97654-3210
- Pedro Costa - (11) 96543-2109
- Ana Paula - (11) 95432-1098
- Carlos Ferreira - (11) 94321-0987

## 🎯 Próximas Melhorias

- [ ] Sistema de autenticação
- [ ] Dashboard de vendas
- [ ] Relatórios de pedidos
- [ ] Integração com múltiplas impressoras
- [ ] Aplicativo mobile
- [ ] API REST

## 💡 Dicas

- Para testar impressoras, use "Imprimir para PDF"
- Verifique as configurações de margem (use 0mm)
- Configure o tamanho de papel como 80mm
- Consulte o arquivo SETUP_INSTRUCOES.md para mais detalhes

## 🐛 Suporte

Em caso de dúvidas ou problemas:
1. Verifique o arquivo SETUP_INSTRUCOES.md
2. Abra http://localhost:8000/teste.html para diagnóstico
3. Verifique o console do navegador (F12)

---

**Desenvolvido para Restaurante Delícias da Cintia**  
Versão 1.0 - 2026
