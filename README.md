Projeto do Módulo 3 do Curso de Desenvolvimento de Sistemas da ETEC

# Site de Receitas — StoryBites

Este é um projeto de site de receitas desenvolvido em PHP, HTML e CSS, com estrutura modular e fácil manutenção.

🚀 Como rodar o projeto

1. **Pré-requisitos:**  
   - PHP instalado  
   - XAMPP ou similar  
   - Git (opcional)

2. **Instalação:**  
   - Clone ou copie os arquivos para a pasta `htdocs` do XAMPP.
   - Inicie o Apache pelo painel do XAMPP.

3. **Acesse no navegador:**  
   ```
   http://localhost/Site-de-receitas/
   ```

🗂️ Organização das páginas

- `index.php`: Página inicial com apresentação e categorias.
- `pages/*.php`: Páginas de cada categoria de receita.
- `partials/_head.php`: Cabeçalho HTML dinâmico (meta tags, CSS).
- `partials/_header.php`: Menu de navegação.
- `partials/_footer.php`: Rodapé do site.
- `config.php`: Configurações globais e variáveis padrão.

🎨 Estilização

- Os arquivos CSS estão na pasta `css/`.
- Cada página pode definir seus próprios estilos via variável `$PAGE_STYLES`.

📝 Como adicionar uma nova categoria

1. Crie um novo arquivo em `pages/` (ex: `pages/salgados.php`).
2. Defina as variáveis da página no topo do arquivo.
3. Inclua os partials e o conteúdo da receita.
4. Adicione/edite o CSS se necessário.

💡 Funcionalidades

- Estrutura modular com partials reutilizáveis.
- Menu dinâmico com página ativa destacada.
- Cards de receitas com imagens, ingredientes e modo de preparo.
- Fácil expansão para novas categorias.

