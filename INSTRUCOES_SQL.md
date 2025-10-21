# 📋 Como Executar o Script SQL para Inserir Receitas

## 🎯 Opções para executar o script:

### **Opção 1: Via Terminal MySQL (Recomendado)**
```bash
# 1. Abrir PowerShell e ir para o diretório do MySQL
cd C:\xampp\mysql\bin

# 2. Executar o script SQL
.\mysql.exe -u root -h localhost site_receitas < "C:\xampp\htdocs\Story-Bytes-\inserir_receitas_simples.sql"
```

### **Opção 2: Via MySQL Workbench**
1. Abrir MySQL Workbench
2. Conectar ao servidor local (root)
3. Abrir o arquivo `inserir_receitas_simples.sql`
4. Executar o script (Ctrl + Shift + Enter)

### **Opção 3: Via phpMyAdmin**
1. Acessar http://localhost/phpmyadmin
2. Selecionar banco de dados `site_receitas`
3. Ir na aba "SQL"
4. Copiar e colar o conteúdo do arquivo `inserir_receitas_simples.sql`
5. Clicar em "Executar"

### **Opção 4: Via Terminal MySQL Interativo**
```bash
# 1. Entrar no MySQL
cd C:\xampp\mysql\bin
.\mysql.exe -u root -h localhost

# 2. Dentro do MySQL, executar:
USE site_receitas;
SOURCE C:/xampp/htdocs/Story-Bytes-/inserir_receitas_simples.sql;
```

## 📊 O que o script faz:

✅ **Cria 6 categorias** (se não existirem):
- Bebidas
- Doces  
- Massas
- Carnes
- Sopas
- Lanches

✅ **Insere 12 receitas completas**:
- 2 receitas por categoria
- Todas extraídas das páginas do site
- Status "aprovada" (visível no site)
- Com ingredientes, modo de preparo, rendimento, tempo

✅ **Mostra resultado final**:
- Total de receitas no banco
- Quantidade por categoria

## 🔍 Para verificar se funcionou:

### **Via Terminal:**
```bash
.\mysql.exe -u root -h localhost -e "USE site_receitas; SELECT COUNT(*) as total FROM receita;"
```

### **Via Browser:**
```
http://localhost/Story-Bytes-/verificar_receitas_banco.php
```

## ⚠️ Observações importantes:

- **Banco de dados:** `site_receitas`
- **Usuário:** As receitas serão atribuídas ao usuário ID 1
- **Duplicatas:** O script não verifica duplicatas, então execute apenas uma vez
- **Backup:** Faça backup do banco antes de executar (opcional)

## 🚀 Após a execução:

1. Verifique o resultado na página de debug
2. Acesse o perfil para ver as receitas
3. As receitas aparecerão nas páginas do site
4. Podem ser gerenciadas pelo sistema de administração

---

**📁 Arquivos disponíveis:**
- `inserir_receitas_simples.sql` - Script compacto (recomendado)
- `inserir_receitas_mysql.sql` - Script completo com subconsultas
- Este arquivo de instruções