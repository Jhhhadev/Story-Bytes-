# Script para sincronizar com o repositório remoto
# Autor: GitHub Copilot
# Uso: .\sync.ps1

Write-Host "🔄 Sincronizando com o repositório remoto..." -ForegroundColor Green

# Verificar se estamos no diretório correto
if (!(Test-Path ".\backend\conexao.php")) {
    Write-Host "❌ Erro: Execute este script no diretório raiz do projeto Story-Bytes!" -ForegroundColor Red
    exit 1
}

# Passo 1: Verificar status atual
Write-Host "`n📋 Status atual:" -ForegroundColor Cyan
git status

# Passo 2: Buscar atualizações do remoto
Write-Host "`n📥 Buscando atualizações do GitHub..." -ForegroundColor Cyan
git fetch origin

# Passo 3: Verificar se há commits remotos
$localCommit = git rev-parse HEAD
$remoteCommit = git rev-parse origin/main

if ($localCommit -eq $remoteCommit) {
    Write-Host "✅ Seu repositório está atualizado!" -ForegroundColor Green
} else {
    Write-Host "⚠️  Há atualizações disponíveis no GitHub." -ForegroundColor Yellow
    Write-Host "🔄 Fazendo pull das alterações..." -ForegroundColor Cyan
    
    # Verificar se há alterações locais não commitadas
    $gitStatus = git status --porcelain
    if (![string]::IsNullOrEmpty($gitStatus)) {
        Write-Host "⚠️  Você tem alterações não commitadas. Fazendo stash..." -ForegroundColor Yellow
        git stash push -m "Auto-stash antes do pull - $(Get-Date)"
        $stashed = $true
    }
    
    git pull origin main
    
    if ($stashed) {
        Write-Host "🔄 Restaurando suas alterações..." -ForegroundColor Cyan
        git stash pop
    }
}

Write-Host "`n🎉 Sincronização concluída!" -ForegroundColor Magenta