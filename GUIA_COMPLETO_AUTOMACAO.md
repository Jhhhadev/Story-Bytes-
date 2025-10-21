# 📚 Guia Completo de Automação - Story Bytes
**Versão 1.0 | Outubro 2025**

---

## 📖 Índice

1. [Introdução](#introdução)
2. [Pré-requisitos](#pré-requisitos)
3. [Configuração Inicial](#configuração-inicial)
4. [Scripts Disponíveis](#scripts-disponíveis)
5. [Guia de Uso Passo a Passo](#guia-de-uso-passo-a-passo)
6. [Exemplos Práticos](#exemplos-práticos)
7. [Solução de Problemas](#solução-de-problemas)
8. [Boas Práticas](#boas-práticas)
9. [Referência Rápida](#referência-rápida)

---

## 🎯 Introdução

Este documento fornece um guia completo para utilizar os scripts de automação criados para o projeto **Story Bytes**. Os scripts automatizam o processo de versionamento, commit e deploy das alterações para o repositório GitHub.

### Objetivos dos Scripts:
- ✅ Simplificar o processo de commit e push
- ✅ Reduzir erros manuais
- ✅ Padronizar mensagens de commit
- ✅ Automatizar sincronização com o repositório remoto
- ✅ Fornecer feedback visual detalhado

---

## 🛠️ Pré-requisitos

### Software Necessário:
1. **Git** - Sistema de controle de versão
2. **PowerShell** - Para executar scripts .ps1
3. **XAMPP** - Ambiente de desenvolvimento local
4. **Editor de código** (VS Code recomendado)

### Conhecimentos Básicos:
- Comandos básicos de terminal
- Conceitos de Git (commit, push, pull)
- Estrutura de projetos web

---

## ⚙️ Configuração Inicial

### 1. Habilitando Scripts PowerShell

Abra o PowerShell como **Administrador** e execute:

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

**Explicação:** Este comando permite a execução de scripts PowerShell baixados da internet, mas apenas se estiverem assinados.

### 2. Verificando Configuração do Git

```powershell
# Verificar configuração atual
git config --list | findstr user

# Se não estiver configurado, definir:
git config --global user.name "Seu Nome"
git config --global user.email "seuemail@exemplo.com"
```

### 3. Navegando para o Diretório do Projeto

```powershell
cd c:\xampp\htdocs\Story-Bytes-
```

---

## 📜 Scripts Disponíveis

### 1. **deploy.ps1** - Script Principal de Deploy

**Propósito:** Automatiza todo o processo de commit e push para o GitHub.

**Funcionalidades:**
- ✅ Verifica se está no diretório correto
- ✅ Exibe status atual do repositório
- ✅ Adiciona todos os arquivos modificados
- ✅ Cria commit com mensagem personalizada
- ✅ Identifica branch atual automaticamente
- ✅ Envia alterações para o GitHub
- ✅ Fornece feedback colorido e detalhado

**Sintaxe:**
```powershell
.\deploy.ps1 "Sua mensagem de commit"
```

### 2. **deploy.bat** - Versão Batch

**Propósito:** Mesma funcionalidade do deploy.ps1, mas em formato batch para compatibilidade.

**Sintaxe:**
```cmd
deploy.bat "Sua mensagem de commit"
```

### 3. **sync.ps1** - Script de Sincronização

**Propósito:** Sincroniza seu repositório local com as alterações do GitHub.

**Funcionalidades:**
- ✅ Busca atualizações do repositório remoto
- ✅ Compara commits locais vs remotos
- ✅ Faz pull automático se necessário
- ✅ Gerencia stash automático para alterações não commitadas
- ✅ Restaura alterações após sincronização

**Sintaxe:**
```powershell
.\sync.ps1
```

---

## 📋 Guia de Uso Passo a Passo

### Cenário 1: Fazendo Alterações e Deploy

#### Passo 1: Sincronizar com o Repositório Remoto
```powershell
.\sync.ps1
```
**O que acontece:**
- Verifica se há atualizações no GitHub
- Baixa alterações se necessário
- Preserva suas alterações locais

#### Passo 2: Fazer Suas Alterações
- Modifique os arquivos necessários
- Adicione novos arquivos se precisar
- Teste suas alterações localmente

#### Passo 3: Fazer Deploy
```powershell
.\deploy.ps1 "Implementação do sistema de comentários"
```

**O que acontece internamente:**
1. **Verificação:** Script verifica se está no diretório correto
2. **Status:** Mostra quais arquivos foram modificados
3. **Add:** Adiciona todos os arquivos modificados ao staging
4. **Commit:** Cria um commit com sua mensagem
5. **Push:** Envia para o GitHub

### Cenário 2: Trabalhando em Equipe

#### Antes de Começar a Trabalhar:
```powershell
.\sync.ps1
```

#### Depois de Fazer Alterações:
```powershell
.\deploy.ps1 "Descrição das suas alterações"
```

#### Se Houver Conflitos:
O script irá avisar e você precisará resolver manualmente.

---

## 💡 Exemplos Práticos

### Exemplo 1: Implementando Nova Funcionalidade
```powershell
# Sincronizar primeiro
.\sync.ps1

# Fazer alterações no código...
# Testar a funcionalidade...

# Deploy
.\deploy.ps1 "Implementação do sistema de login completo com validação"
```

### Exemplo 2: Corrigindo Bugs
```powershell
.\deploy.ps1 "Correção de bug na validação de email do formulário de cadastro"
```

### Exemplo 3: Adicionando Conteúdo
```powershell
.\deploy.ps1 "Adição de 10 novas receitas de sobremesas com imagens"
```

### Exemplo 4: Atualizando Estilos
```powershell
.\deploy.ps1 "Melhoria no design responsivo e cores do tema"
```

### Exemplo 5: Atualizações de Banco de Dados
```powershell
.\deploy.ps1 "Atualização do script de criação do banco com novas tabelas"
```

---

## 🚨 Solução de Problemas

### Problema 1: "Execution Policy" Restritiva

**Erro:**
```
cannot be loaded because running scripts is disabled on this system
```

**Solução:**
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Problema 2: Não Está no Diretório Correto

**Erro:**
```
❌ Erro: Execute este script no diretório raiz do projeto Story-Bytes!
```

**Solução:**
```powershell
cd c:\xampp\htdocs\Story-Bytes-
```

### Problema 3: Conflitos de Merge

**Erro:**
```
Automatic merge failed; fix conflicts and then commit the result
```

**Solução:**
1. Identifique os arquivos com conflito:
   ```powershell
   git status
   ```

2. Abra os arquivos e resolva os conflitos manualmente

3. Adicione os arquivos resolvidos:
   ```powershell
   git add .
   ```

4. Finalize o merge:
   ```powershell
   git commit -m "Resolução de conflitos de merge"
   ```

### Problema 4: Erro de Autenticação

**Erro:**
```
Permission denied (publickey)
```

**Solução:**
- Configure suas credenciais do GitHub
- Use Personal Access Token se necessário

### Problema 5: Branch Divergente

**Erro:**
```
Your branch and 'origin/main' have diverged
```

**Solução:**
```powershell
.\sync.ps1  # Isso vai resolver automaticamente
```

---

## 📏 Boas Práticas

### 1. Mensagens de Commit Efetivas

#### ✅ Boas Mensagens:
```powershell
.\deploy.ps1 "Implementação do sistema de autenticação de usuários"
.\deploy.ps1 "Correção de bug na validação de formulário de cadastro"
.\deploy.ps1 "Adição de responsividade para dispositivos móveis"
.\deploy.ps1 "Atualização da documentação da API de receitas"
```

#### ❌ Mensagens Ruins:
```powershell
.\deploy.ps1 "fix"
.\deploy.ps1 "update"
.\deploy.ps1 "mudanças"
.\deploy.ps1 "teste"
```

### 2. Frequência de Commits

- **Faça commits pequenos e frequentes**
- **Cada commit deve representar uma funcionalidade ou correção completa**
- **Evite commits muito grandes com muitas alterações**

### 3. Sincronização Regular

```powershell
# Sempre antes de começar a trabalhar
.\sync.ps1

# Pelo menos uma vez por dia se trabalhando em equipe
```

### 4. Teste Antes do Deploy

- Sempre teste suas alterações localmente
- Verifique se o site está funcionando
- Confirme que não quebrou funcionalidades existentes

---

## 🏃‍♂️ Referência Rápida

### Comandos Mais Usados:

```powershell
# Sincronizar com GitHub
.\sync.ps1

# Deploy simples
.\deploy.ps1 "Sua mensagem aqui"

# Verificar status
git status

# Ver histórico
git log --oneline -n 5

# Verificar branch atual
git branch --show-current
```

### Estrutura de Arquivos:
```
Story-Bytes-/
├── deploy.ps1           # Script principal de deploy
├── deploy.bat           # Versão batch
├── sync.ps1             # Script de sincronização
├── SCRIPTS_README.md    # Documentação dos scripts
├── backend/             # Código do backend
├── css/                 # Estilos
├── pages/               # Páginas PHP
├── partials/            # Componentes reutilizáveis
└── ...
```

### Fluxo de Trabalho Ideal:

1. **Iniciar:** `.\sync.ps1`
2. **Desenvolver:** Fazer alterações
3. **Testar:** Verificar funcionamento
4. **Deploy:** `.\deploy.ps1 "Mensagem"`
5. **Repetir:** Conforme necessário

---

## 🔗 Links Úteis

- **Repositório GitHub:** https://github.com/Jhhhadev/Story-Bytes-
- **Documentação Git:** https://git-scm.com/docs
- **PowerShell:** https://docs.microsoft.com/powershell/

---

## 📞 Suporte

Em caso de dúvidas ou problemas:

1. Consulte a seção **Solução de Problemas**
2. Verifique se seguiu todos os passos da **Configuração Inicial**
3. Consulte os **Exemplos Práticos**

---

**© 2025 Story Bytes Project | Guia de Automação v1.0**