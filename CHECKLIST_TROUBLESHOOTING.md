# 📋 Checklist e Troubleshooting - Story Bytes

## ✅ Checklist de Configuração Inicial

### Antes de Usar os Scripts:

- [ ] **Git instalado e configurado**
  ```powershell
  git --version
  git config --global user.name
  git config --global user.email
  ```

- [ ] **PowerShell com política de execução configurada**
  ```powershell
  Get-ExecutionPolicy
  # Deve retornar: RemoteSigned ou Unrestricted
  ```

- [ ] **Estar no diretório correto**
  ```powershell
  cd c:\xampp\htdocs\Story-Bytes-
  pwd  # Verificar diretório atual
  ```

- [ ] **Repositório Git inicializado**
  ```powershell
  git status  # Não deve dar erro "not a git repository"
  ```

- [ ] **Remoto configurado**
  ```powershell
  git remote -v  # Deve mostrar origin com GitHub
  ```

---

## 🚨 Troubleshooting Detalhado

### 1. Erro: "Scripts não podem ser executados"

**Sintomas:**
```
.\deploy.ps1 : File cannot be loaded because running scripts is disabled
```

**Solução Passo a Passo:**
```powershell
# 1. Abrir PowerShell como Administrador
# 2. Executar comando:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# 3. Confirmar com 'Y' quando perguntado
# 4. Verificar:
Get-ExecutionPolicy
```

### 2. Erro: "Não é um repositório Git"

**Sintomas:**
```
fatal: not a git repository (or any of the parent directories): .git
```

**Solução:**
```powershell
# Verificar se está no diretório correto
ls
# Deve mostrar: backend/, css/, pages/, etc.

# Se não estiver, navegar:
cd c:\xampp\htdocs\Story-Bytes-

# Se o repositório não foi inicializado:
git init
git remote add origin https://github.com/Jhhhadev/Story-Bytes-.git
```

### 3. Erro: "Permission Denied"

**Sintomas:**
```
Permission denied (publickey)
remote: Repository not found
```

**Solução:**
```powershell
# Verificar remoto:
git remote -v

# Se estiver incorreto, corrigir:
git remote set-url origin https://github.com/Jhhhadev/Story-Bytes-.git

# Para HTTPS com token:
git remote set-url origin https://[SEU_TOKEN]@github.com/Jhhhadev/Story-Bytes-.git
```

### 4. Erro: "Conflitos de Merge"

**Sintomas:**
```
Auto-merging file.php
CONFLICT (content): Merge conflict in file.php
Automatic merge failed
```

**Solução Detalhada:**
```powershell
# 1. Ver arquivos com conflito:
git status

# 2. Abrir cada arquivo e procurar por:
# <<<<<<< HEAD
# Seu código
# =======
# Código do GitHub
# >>>>>>> branch

# 3. Resolver manualmente escolhendo o que manter

# 4. Adicionar arquivos resolvidos:
git add nome_do_arquivo.php

# 5. Finalizar merge:
git commit -m "Resolução de conflitos"

# 6. Enviar:
git push origin main
```

### 5. Erro: "Nothing to Commit"

**Sintomas:**
```
On branch main
nothing to commit, working tree clean
```

**Explicação:** Não há alterações para enviar.

**Verificação:**
```powershell
# Ver status detalhado:
git status

# Ver diferenças:
git diff

# Se houver arquivos não rastreados:
git add .
```

---

## 🔧 Comandos de Emergência

### Desfazer Último Commit (Local):
```powershell
git reset --soft HEAD~1
```

### Desfazer Alterações em Arquivo:
```powershell
git checkout -- nome_do_arquivo.php
```

### Voltar Arquivo a Versão Anterior:
```powershell
git checkout HEAD~1 -- nome_do_arquivo.php
```

### Ver Histórico Detalhado:
```powershell
git log --oneline --graph --all
```

### Forçar Push (CUIDADO!):
```powershell
git push -f origin main
```

---

## 📊 Códigos de Status do Git

| Status | Significado |
|--------|-------------|
| `A` | Arquivo adicionado |
| `M` | Arquivo modificado |
| `D` | Arquivo deletado |
| `R` | Arquivo renomeado |
| `??` | Arquivo não rastreado |
| `UU` | Conflito não resolvido |

---

## 🎯 Fluxos de Trabalho Específicos

### Fluxo para Nova Funcionalidade:
```powershell
# 1. Sincronizar
.\sync.ps1

# 2. Criar/editar arquivos
# ... fazer alterações ...

# 3. Testar localmente
# Abrir no navegador e verificar

# 4. Deploy
.\deploy.ps1 "Implementação de [nome da funcionalidade]"
```

### Fluxo para Correção de Bug:
```powershell
# 1. Identificar o problema
# 2. Sincronizar
.\sync.ps1

# 3. Corrigir o código
# ... fazer correções ...

# 4. Testar se corrigiu
# 5. Deploy
.\deploy.ps1 "Correção de bug: [descrição do problema]"
```

### Fluxo para Atualização de Design:
```powershell
# 1. Sincronizar
.\sync.ps1

# 2. Modificar CSS/HTML
# ... alterações visuais ...

# 3. Testar em diferentes telas
# 4. Deploy
.\deploy.ps1 "Atualização de design: [área modificada]"
```

---

## 🔍 Verificações Importantes

### Antes de Cada Deploy:
```powershell
# 1. Site funciona localmente?
# Abrir: http://localhost/Story-Bytes-

# 2. Não há erros PHP?
# Verificar logs de erro

# 3. CSS está carregando?
# Verificar no navegador

# 4. Formulários funcionam?
# Testar cadastro/login
```

### Após Cada Deploy:
```powershell
# 1. Verificar se push foi bem-sucedido
git status

# 2. Confirmar no GitHub
# Acessar: https://github.com/Jhhhadev/Story-Bytes-

# 3. Ver último commit
git log --oneline -1
```

---

## 📱 Versões dos Scripts

### Verificar Versão dos Scripts:
```powershell
# Ver informações do script:
Get-Content .\deploy.ps1 | Select-String "Autor|Versão"
```

### Atualizar Scripts:
Se houver uma versão nova disponível, substitua os arquivos:
- `deploy.ps1`
- `deploy.bat`
- `sync.ps1`

---

## 🆘 Recuperação de Emergência

### Se Tudo Der Errado:
```powershell
# 1. Fazer backup das alterações importantes
# 2. Clonar repositório novamente:
cd c:\xampp\htdocs
git clone https://github.com/Jhhhadev/Story-Bytes-.git Story-Bytes-new

# 3. Copiar suas alterações para a nova pasta
# 4. Continuar trabalhando na nova pasta
```

### Contactar Suporte:
- Anote a mensagem de erro exata
- Informe qual comando estava executando
- Mencione o sistema operacional
- Descreva o que estava tentando fazer