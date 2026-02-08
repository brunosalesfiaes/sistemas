# ⚡ Guia Rápido - Delícias da Cintia

## 🚀 Para Começar Imediatamente

### Windows
1. **Clique duas vezes em:** `iniciar_servidor.bat`
2. **Acesse:** http://localhost:8000/teste.html
3. **Se tudo OK, clique:** "Ir para Aplicação"

### Linux/Mac
```bash
php -S localhost:8000
```
Depois acesse: http://localhost:8000/teste.html

---

## 📝 Primeiro Acesso

1. Vá para **PageOne.html**
2. Digite um dos telefones:
   - (11) 98765-4321 → João Silva
   - (11) 97654-3210 → Maria Santos
   - (11) 96543-2109 → Pedro Costa
   - (11) 95432-1098 → Ana Paula
   - (11) 94321-0987 → Carlos Ferreira
3. Escolha proteína, acompanhamentos e saladas
4. Clique "Finalizar Pedido"
5. Clique "Imprimir Comanda"

---

## 🔧 Configuração do Banco de Dados

Se o teste disser que banco não existe:

**No Prompt de Comando/Terminal:**
```bash
mysql -u root -p < "Banco de dados\setup_banco.sql"
```

**Ou no phpMyAdmin:**
1. Crie banco `ecommerce`
2. Importe arquivo `setup_banco.sql`

---

## 📋 Arquivos Importantes

| Arquivo | Função |
|---------|--------|
| PageOne.html | Interface principal |
| action_PAGE.php | Processa pedidos |
| config.php | Configurações |
| teste.html | Diagnóstico |
| dashboard.html | Histórico de pedidos |
| iniciar_servidor.bat | Inicia servidor (Windows) |

---

## 🖨️ Imprimir em Térmica

1. **Tamanho de papel:** 80mm
2. **Margens:** 0mm
3. **Clique:** "Imprimir Comanda"
4. **Selecione:** Sua impressora térmica
5. **Clique:** "Imprimir"

---

## 🐛 Problemas Comuns

| Problema | Solução |
|----------|---------|
| "PHP não encontrado" | Instale PHP: https://www.php.net |
| "Banco não conecta" | Execute setup_banco.sql ou inicie MySQL |
| "Cliente não encontrado" | Verifique se digitou o telefone corretamente |
| "Impressora não imprime" | Teste com "Imprimir para PDF" primeiro |

---

## 📞 Próximas Ações Recomendadas

1. ✅ Testar com clientes de exemplo
2. ✅ Adicionar seus clientes ao banco
3. ✅ Testar impressão em térmica
4. ✅ Configurar em rede (se necessário)
5. ✅ Fazer backup do banco regularmente

---

## 💡 Adicionar Seus Clientes

Abra `setup_banco.sql` e adicione:
```sql
INSERT INTO clientes (telefone, nome, secretaria) VALUES 
('(11) 91234-5678', 'Seu Cliente', 'Seu Setor');
```

Depois execute:
```bash
mysql -u root -p ecommerce < Banco\ de\ dados/setup_banco.sql
```

---

**Desenvolvido para Restaurante Delícias da Cintia**
