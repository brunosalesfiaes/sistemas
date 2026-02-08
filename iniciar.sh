#!/bin/bash
# ============================================
# Script de Inicialização do Sistema
# Para Linux/Mac
# ============================================

echo "╔════════════════════════════════════════╗"
echo "║   Delícias da Cintia - Sistema Pronto   ║"
echo "╚════════════════════════════════════════╝"
echo ""

# Verificar PHP
echo "🔍 Verificando PHP..."
if command -v php &> /dev/null; then
    echo "✅ PHP encontrado: $(php --version | head -n 1)"
else
    echo "❌ PHP não encontrado!"
    echo "   Instale PHP em: https://www.php.net"
    exit 1
fi

echo ""
echo "🔍 Verificando MySQL..."
if command -v mysql &> /dev/null; then
    echo "✅ MySQL encontrado"
else
    echo "⚠️  MySQL não encontrado (mas pode estar rodando)"
fi

echo ""
echo "════════════════════════════════════════"
echo "📋 PRÓXIMAS AÇÕES:"
echo "════════════════════════════════════════"
echo ""
echo "1. Configure as credenciais do banco em config.php"
echo ""
echo "2. Crie o banco de dados:"
echo "   mysql -u root -p < 'Banco de dados/setup_banco.sql'"
echo ""
echo "3. Inicie o servidor:"
echo "   php -S localhost:8000"
echo ""
echo "4. Acesse no navegador:"
echo "   http://localhost:8000/index.html"
echo ""
echo "════════════════════════════════════════"
echo "📚 DOCUMENTAÇÃO:"
echo "════════════════════════════════════════"
echo ""
echo "⭐ COMECE_AQUI.md - Resumo rápido"
echo "📖 GUIA_RAPIDO.md - Início em 5 minutos"
echo "🔧 SETUP_INSTRUCOES.md - Guia completo"
echo "📑 INDICE.md - Índice de documentação"
echo ""
echo "════════════════════════════════════════"
echo "✨ Sistema pronto para usar!"
echo "════════════════════════════════════════"
