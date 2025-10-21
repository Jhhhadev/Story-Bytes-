@echo off
REM Script Batch para deploy das alterações no GitHub
REM Autor: GitHub Copilot
REM Uso: deploy.bat "Sua mensagem de commit aqui"

if "%~1"=="" (
    echo ❌ Erro: É necessário fornecer uma mensagem de commit!
    echo 💡 Uso: deploy.bat "Sua mensagem de commit aqui"
    pause
    exit /b 1
)

echo 🚀 Iniciando processo de deploy...
echo 📁 Diretório: %CD%

REM Verificar se estamos no diretório correto
if not exist "backend\conexao.php" (
    echo ❌ Erro: Execute este script no diretório raiz do projeto Story-Bytes!
    pause
    exit /b 1
)

echo.
echo 📋 Passo 1: Verificando status do repositório...
git status

echo.
echo ➕ Passo 2: Adicionando arquivos modificados...
git add .

echo.
echo 💾 Passo 3: Fazendo commit das alterações...
git commit -m "%~1"

if errorlevel 1 (
    echo ❌ Erro no commit. Verifique os arquivos e tente novamente.
    pause
    exit /b 1
)

echo.
echo 🌿 Passo 4: Verificando branch atual...
for /f "tokens=*" %%i in ('git branch --show-current') do set BRANCH=%%i
echo Branch atual: %BRANCH%

echo.
echo 🚀 Passo 5: Enviando para o GitHub...
git push origin %BRANCH%

if errorlevel 1 (
    echo ❌ Erro no push. Verifique sua conexão e permissões.
    echo 💡 Dica: Talvez seja necessário fazer 'git pull' primeiro.
    pause
    exit /b 1
) else (
    echo.
    echo ✅ Deploy realizado com sucesso!
    echo 🔗 Verifique em: https://github.com/Jhhhadev/Story-Bytes-
)

echo.
echo 🎉 Script finalizado!
pause