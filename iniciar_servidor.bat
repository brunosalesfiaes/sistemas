@echo off
REM Script para iniciar o servidor PHP no Windows
REM Delícias da Cintia - Sistema de Comandas

echo.
echo ========================================
echo  Sistema de Comandas - Delícias da Cintia
echo ========================================
echo.

REM Verificar se PHP está instalado
php --version >nul 2>&1
if errorlevel 1 (
    echo [ERRO] PHP não está instalado ou não está no PATH!
    echo.
    echo Faça o download em: https://www.php.net/downloads
    echo.
    pause
    exit /b 1
)

echo [OK] PHP encontrado!
echo.

REM Exibir mensagens de instrução
echo ========================================
echo INSTRUÇÕES:
echo ========================================
echo.
echo 1. Certifique-se que MySQL está rodando
echo 2. Configure suas credenciais em config.php se necessário
echo 3. Crie o banco de dados executando:
echo    mysql -u root -p < "Banco de dados\setup_banco.sql"
echo.
echo ========================================
echo.

REM Iniciar servidor
set PORT=8000
echo Iniciando servidor PHP na porta %PORT%...
echo.
echo URL de acesso: http://localhost:%PORT%/Frontend/PageOne.html
echo URL de testes: http://localhost:%PORT%/teste.html
echo.
echo Pressione Ctrl+C para parar o servidor
echo.

cd /d "%~dp0"
php -S localhost:%PORT%

pause
