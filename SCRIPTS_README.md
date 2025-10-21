# 🚀 Scripts de Automação - Story Bytes

Este diretório contém scripts para automatizar o processo de versionamento e deploy do projeto.

## 📋 Scripts Disponíveis

### 1. **deploy.ps1** (PowerShell)
Automatiza o processo completo de commit e push para o GitHub.

**Uso:**
```powershell
.\deploy.ps1 "Sua mensagem de commit aqui"
```

**Exemplo:**
```powershell
.\deploy.ps1 "Implementação do sistema de login"
.\deploy.ps1 "Correção de bugs na validação de formulário"
.\deploy.ps1 "Adição de novas receitas de massas"
```

### 2. **deploy.bat** (Batch)
Mesma funcionalidade do deploy.ps1, mas em formato batch para compatibilidade.

**Uso:**
```cmd
deploy.bat "Sua mensagem de commit aqui"
```

### 3. **sync.ps1** (PowerShell)
Sincroniza seu repositório local com as alterações do GitHub.

**Uso:**
```powershell
.\sync.ps1
```

## 🔧 Configuração Inicial

Para usar os scripts PowerShell, você pode precisar habilitar a execução:

```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

## 📝 Fluxo de Trabalho Recomendado

### Para fazer alterações:
1. Faça suas modificações nos arquivos
2. Execute: `.\sync.ps1` (para buscar atualizações)
3. Execute: `.\deploy.ps1 "Descrição das suas alterações"`

### Para buscar atualizações:
```powershell
.\sync.ps1
```

## 🎯 Funcionalidades dos Scripts

### Deploy Script:
- ✅ Verifica se está no diretório correto
- ✅ Mostra status do repositório
- ✅ Adiciona arquivos modificados
- ✅ Faz commit com mensagem personalizada
- ✅ Identifica branch atual
- ✅ Faz push para o GitHub
- ✅ Exibe feedback colorido e informativo

### Sync Script:
- ✅ Busca atualizações do GitHub
- ✅ Compara commits locais vs remotos
- ✅ Faz pull automático se necessário
- ✅ Gerencia stash automático para alterações não commitadas
- ✅ Restaura alterações após sync

## 🛡️ Tratamento de Erros

Ambos os scripts incluem:
- Verificação de diretório correto
- Tratamento de erros de Git
- Mensagens informativas
- Códigos de saída apropriados

## 💡 Dicas

- Use mensagens de commit descritivas
- Execute `sync.ps1` regularmente para manter-se atualizado
- Os scripts são seguros e não sobrescrevem alterações sem aviso

## 🔗 Repositório

GitHub: https://github.com/Jhhhadev/Story-Bytes-