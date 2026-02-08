# 👥 Guia: Gerenciar Clientes

## 🎯 Como Usar a Nova Funcionalidade

Agora você pode **gerenciar clientes de 3 formas**!

### Acesso Rápido

```
http://localhost:8000/gerenciar_clientes.html
```

---

## 📋 Opção 1: Cadastrar Novo Cliente (Formulário)

### Via Web (Mais fácil)

1. **Abra:** `http://localhost:8000/gerenciar_clientes.html`
2. **Clique em:** "➕ Novo Cliente"
3. **Preencha:**
   - **Telefone:** `(11) 91234-5678` (obrigatório)
   - **Nome:** `João da Silva` (obrigatório)
   - **Secretaria:** `Vendas` (opcional)
4. **Clique em:** "✓ Cadastrar Cliente"
5. ✅ **Pronto!** Cliente cadastrado

---

## 📤 Opção 2: Importar CSV

### Método A: Usar Arquivo Existente (contatos.csv)

1. **Abra:** `http://localhost:8000/gerenciar_clientes.html`
2. **Clique em:** "📤 Importar CSV"
3. **Clique em:** "📂 Importar contatos.csv"
4. ✅ **Automático!** Importa todos de uma vez

### Método B: Upload de Arquivo CSV

1. **Abra:** `http://localhost:8000/gerenciar_clientes.html`
2. **Clique em:** "📤 Importar CSV"
3. **Arraste seu arquivo CSV** na área cinza
   - Ou clique para selecionar um arquivo
4. ✅ **Pronto!** Seus clientes foram importados

### Formato do Arquivo CSV

O arquivo deve ter 3 colunas:

```csv
telefone,nome,secretaria
71981348255,sabrina,inema
71981540191,sergio silva,sesab
71981670604,ezequiel,tribunal de justiça
```

**Formatos de telefone aceitos:**
- `71981348255` (números)
- `(71) 98134-8255` (com formatação)
- `71 98134-8255` (com espaço)

---

## 📋 Opção 3: Listar e Gerenciar Clientes

### Ver Todos os Clientes

1. **Abra:** `http://localhost:8000/gerenciar_clientes.html`
2. **Clique em:** "📋 Listar Clientes"
3. **Tabela aparece** com todos os clientes

### Buscar Cliente

1. **Na aba "Listar Clientes"**
2. **Digite no campo** "Buscar por nome ou telefone..."
3. **A tabela filtra em tempo real**

### Deletar Cliente

1. **Na tabela de clientes**
2. **Clique em:** "🗑️ Deletar"
3. **Confirme a ação**
4. ✅ **Cliente removido**

---

## 🔄 Importar Contatos.csv Automaticamente

O arquivo `contatos.csv` que você colocou está pronto para ser importado!

### Via interface:
```
Clique em "📤 Importar CSV"
Depois em "📂 Importar contatos.csv"
```

### Via linha de comando (alternativo):

Se preferir fazer pelo terminal:

```bash
mysql -u root -p ecommerce << 'EOF'
SELECT COUNT(*) FROM clientes;
EOF
```

---

## 📊 Resultado Final

Depois de importar, você terá:

✅ Todos os clientes do `contatos.csv` cadastrados  
✅ Possibilidade de adicionar novos clientes  
✅ Lista completa para consultar  
✅ Sistema pronto para usar  

---

## 💡 Dicas Importantes

### Telefones
- Pode ser **com ou sem formatação**
- Sistema padroniza automaticamente
- **Não permite duplicados** (mesmo número não pode repetir)

### Nomes
- **Máximo 100 caracteres**
- Pode ter acentos e caracteres especiais

### Secretaria/Setor
- **Opcional** (pode deixar em branco)
- **Máximo 100 caracteres**

### Busca
- Busca funciona por **nome ou telefone**
- **Em tempo real** enquanto você digita
- **Case-insensitive** (maiúsculas/minúsculas)

---

## 🐛 Problemas Comuns

| Problema | Solução |
|----------|---------|
| "Telefone já cadastrado" | O número já existe. Use outro. |
| "Arquivo não encontrado" | Arquivo CSV está corrompido |
| "Campos obrigatórios" | Preencha telefone e nome |
| "Erro de conexão" | MySQL não está rodando |

---

## 📁 Arquivos Envolvidos

- `gerenciar_clientes.html` - Interface
- `cadastrar_cliente.php` - Backend (processa tudo)
- `Banco de dados/Banco de dados/contatos.csv` - Seu arquivo

---

## ✨ Próximas Ações

1. ✅ Acesse `gerenciar_clientes.html`
2. ✅ Clique em "Importar contatos.csv"
3. ✅ Aguarde a importação
4. ✅ Veja a lista de clientes importados
5. ✅ Adicione mais clientes conforme necessário

---

## 🎯 Agora Você Pode

✅ **Cadastrar clientes** via formulário  
✅ **Importar CSV** do seu arquivo  
✅ **Listar todos** os clientes  
✅ **Buscar** por nome ou telefone  
✅ **Deletar** clientes antigos  
✅ **Usar** imediatamente no sistema de comandas  

---

**Tudo funcionando!** 🎉
