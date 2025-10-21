# Script PowerShell para deploy das alterações no GitHub
# Autor: GitHub Copilot
# Uso: .\deploy.ps1 "Sua mensagem de commit aqui"

param(
    [Parameter(Mandatory=$true)]
    [string]$CommitMessage
)

Write-Host "🚀 Iniciando processo de deploy..." -ForegroundColor Green
Write-Host "📁 Diretório: $(Get-Location)" -ForegroundColor Yellow

# Verificar se estamos no diretório correto
if (!(Test-Path ".\backend\conexao.php")) {
    Write-Host "❌ Erro: Execute este script no diretório raiz do projeto Story-Bytes!" -ForegroundColor Red
    exit 1
}

# Passo 1: Verificar status do Git
Write-Host "`n📋 Passo 1: Verificando status do repositório..." -ForegroundColor Cyan
git status

# Passo 2: Adicionar arquivos modificados
Write-Host "`n➕ Passo 2: Adicionando arquivos modificados..." -ForegroundColor Cyan
git add .

# Verificar se há algo para commit
$gitStatus = git status --porcelain
if ([string]::IsNullOrEmpty($gitStatus)) {
    Write-Host "ℹ️  Nenhuma alteração detectada para commit." -ForegroundColor Yellow
    exit 0
}

# Passo 3: Fazer commit
Write-Host "`n💾 Passo 3: Fazendo commit das alterações..." -ForegroundColor Cyan
git commit -m "$CommitMessage"

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erro no commit. Verifique os arquivos e tente novamente." -ForegroundColor Red
    exit 1
}

# Passo 4: Verificar branch atual
Write-Host "`n🌿 Passo 4: Verificando branch atual..." -ForegroundColor Cyan
$currentBranch = git branch --show-current
Write-Host "Branch atual: $currentBranch" -ForegroundColor Yellow

# Passo 5: Fazer push para o GitHub
Write-Host "`n🚀 Passo 5: Enviando para o GitHub..." -ForegroundColor Cyan
git push origin $currentBranch

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n✅ Deploy realizado com sucesso!" -ForegroundColor Green
    Write-Host "🔗 Verifique em: https://github.com/Jhhhadev/Story-Bytes-" -ForegroundColor Blue
} else {
    Write-Host "`n❌ Erro no push. Verifique sua conexão e permissões." -ForegroundColor Red
    Write-Host "💡 Dica: Talvez seja necessário fazer 'git pull' primeiro." -ForegroundColor Yellow
}

Write-Host "`n🎉 Script finalizado!" -ForegroundColor Magenta