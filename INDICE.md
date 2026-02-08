# 📚 Índice de Documentação

## 🎯 Comece Por Aqui

1. **[GUIA_RAPIDO.md](GUIA_RAPIDO.md)** ⚡
   - Instruções para começar em 5 minutos
   - Solução rápida de problemas
   - Telefones de teste

2. **[README.md](README.md)** 📖
   - Visão geral do projeto
   - Status de funcionalidades
   - Estrutura do projeto

## 📖 Documentação Detalhada

3. **[SETUP_INSTRUCOES.md](SETUP_INSTRUCOES.md)** 🔧
   - Pré-requisitos completos
   - Instalação passo a passo
   - Configuração de impressoras
   - Troubleshooting completo

## 🗂️ Arquivos do Projeto

### Frontend
- **Frontend/PageOne.html** - Interface principal do sistema
- **Frontend/Atalhos.css** - Estilos compartilhados

### Backend
- **action_PAGE.php** - Processa pedidos e gera comandas
- **config.php** - Configurações centralizadas
- **test_connection.php** - Testa conexão com banco
- **api_dashboard.php** - API para dashboard

### Web
- **teste.html** - Página de diagnóstico do sistema
- **dashboard.html** - Histórico e estatísticas

### Database
- **Banco de dados/setup_banco.sql** - Script de criação do banco
- **Banco de dados/ecommerce.sql** - Dados iniciais

### Automação
- **iniciar_servidor.bat** - Script para iniciar servidor (Windows)

## 🚀 Fluxo de Uso

```
1. Iniciar Servidor
   └─ iniciar_servidor.bat (ou php -S localhost:8000)

2. Diagnóstico
   └─ Acessar http://localhost:8000/teste.html

3. Usar Sistema
   ├─ PageOne.html - Criar pedidos
   ├─ dashboard.html - Ver histórico
   └─ teste.html - Diagnóstico

4. Configurar
   └─ config.php - Credenciais (se necessário)
```

## 📋 Checklist de Setup

- [ ] PHP instalado (php --version)
- [ ] MySQL rodando
- [ ] Banco de dados criado (setup_banco.sql)
- [ ] Servidor iniciado
- [ ] Testes passando (teste.html)
- [ ] Impressora configurada

## 🔄 Manutenção

### Adicionar Novos Clientes
1. Edite: `Banco de dados/setup_banco.sql`
2. Adicione linhas em INSERT
3. Execute: `mysql -u root -p ecommerce < setup_banco.sql`

### Backup do Banco
```bash
mysqldump -u root -p ecommerce > backup_$(date +%Y%m%d).sql
```

### Ver Histórico de Pedidos
- Acesse: http://localhost:8000/dashboard.html

## 💬 FAQ Rápido

**P: Como alterar credenciais do banco?**
R: Edite `config.php` ou variáveis em `action_PAGE.php`

**P: Onde adiciono novos clientes?**
R: Em `Banco de dados/setup_banco.sql` ou via MySQL/phpMyAdmin

**P: Como testar impressão sem térmica?**
R: Use "Imprimir para PDF" no navegador

**P: Sistema diz cliente não encontrado**
R: Verifique se o telefone está exatamente igual ao cadastrado

**P: Servidor não inicia**
R: Confirme que PHP está instalado e MySQL rodando

## 📞 Contato & Suporte

Para dúvidas, consulte:
1. GUIA_RAPIDO.md (soluções rápidas)
2. SETUP_INSTRUCOES.md (detalhes)
3. Console do navegador (F12) - para erros
4. teste.html - para diagnóstico

---

## 📁 Estrutura Completa

```
Site-Cintia/
│
├── 📄 Documentação
│   ├── README.md
│   ├── GUIA_RAPIDO.md
│   ├── SETUP_INSTRUCOES.md
│   └── INDICE.md (este arquivo)
│
├── 🌐 Frontend
│   ├── Frontend/PageOne.html
│   └── Frontend/Atalhos.css
│
├── ⚙️ Backend
│   ├── action_PAGE.php
│   ├── config.php
│   ├── test_connection.php
│   └── api_dashboard.php
│
├── 📊 Dashboards
│   ├── teste.html
│   └── dashboard.html
│
├── 🗄️ Banco de Dados
│   └── Banco de dados/
│       ├── setup_banco.sql
│       └── ecommerce.sql
│
└── 🔧 Automação
    └── iniciar_servidor.bat
```

---

**Versão:** 1.0  
**Última Atualização:** 2026  
**Desenvolvido para:** Restaurante Delícias da Cintia
