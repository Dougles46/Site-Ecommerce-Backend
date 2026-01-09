# Análise e Oportunidades de Melhoria para o Site Crochê e Arte

## 1. Frontend (HTML/CSS/Bootstrap)

### Problemas Atuais:

*   **Design Básico:** O arquivo `style.css` é minimalista, e o uso do Bootstrap 5 no `index.php` é fundamental, sem exploração de componentes mais avançados ou personalização profunda. O site tem uma aparência funcional, mas carece de um design moderno e atraente, conforme solicitado pelo usuário.
*   **Interatividade Limitada:** Há pouca interatividade além da navegação básica. O usuário solicitou "bastante Bootstrap interativo", indicando a necessidade de elementos como carrosséis, modais, abas, etc.
*   **Consistência Visual:** A consistência visual pode ser aprimorada com a definição de uma paleta de cores mais rica, tipografia e espaçamento uniformes.
*   **Responsividade:** Embora o Bootstrap seja responsivo por natureza, é crucial verificar e otimizar a exibição em diferentes tamanhos de tela, especialmente ao adicionar novos componentes.

### Oportunidades de Melhoria:

*   **Adoção de Componentes Bootstrap Avançados:** Implementar carrosséis para produtos em destaque, modais para detalhes de produtos, barras de navegação mais ricas (com dropdowns, por exemplo), cards de produtos mais elaborados, formulários de contato estilizados, etc.
*   **Personalização do Bootstrap:** Utilizar variáveis CSS ou SASS (se o projeto for escalado) para personalizar as cores, fontes e outros estilos do Bootstrap, alinhando-os à identidade visual desejada (e.g., cores quentes e terrosas, fontes que remetam ao artesanato).
*   **Animações e Transições:** Adicionar microinterações e transições suaves para melhorar a experiência do usuário.
*   **Otimização de Imagens:** Garantir que as imagens dos produtos sejam otimizadas para a web (tamanho e formato) para melhorar o tempo de carregamento.

## 2. Backend (PHP)

### Problemas Atuais:

*   **Segurança:**
    *   **SQL Injection:** As consultas SQL (`SELECT * FROM produtos`) não utilizam *prepared statements*, o que as torna vulneráveis a ataques de injeção SQL. Embora os dados exibidos sejam apenas de leitura, em um cenário de administração (que já existe com `admin/add.php`, `admin/edit.php`), essa vulnerabilidade é crítica. [1]
    *   **Cross-Site Scripting (XSS):** Embora `htmlspecialchars()` seja usado na exibição de dados (`echo htmlspecialchars($p['nome'])`), é fundamental garantir que todos os pontos de entrada de dados (formulários de adição/edição) sejam devidamente sanitizados e validados para prevenir XSS. [2]
    *   **Senhas em Texto Plano:** A estrutura do banco de dados (`usuarios` com `email` e `senha varchar(255)`) sugere que as senhas podem ser armazenadas em texto plano ou com hash fraco. Senhas devem ser sempre armazenadas com funções de hash seguras (como `password_hash()` no PHP) e verificadas com `password_verify()`. [3]
*   **Organização do Código:** O código PHP está misturado com o HTML em arquivos como `index.php` e `produtos.php`. Isso dificulta a manutenção, legibilidade e escalabilidade do projeto. Não há uma clara separação de responsabilidades (MVC - Model-View-Controller). [4]
*   **Tratamento de Erros:** O tratamento de erros é básico (`die(

Erro de conexão: ")) e não há um registro robusto de logs de erros, o que dificulta a depuração e monitoramento do sistema em produção.
*   **Reuso de Código:** O cabeçalho (`index_header.php`) é incluído, mas outras partes do código, como a lógica de exibição de produtos, são repetidas em `index.php` e `produtos.php`. Isso viola o princípio DRY (Don't Repeat Yourself). [5]

### Oportunidades de Melhoria:

*   **Segurança:**
    *   Implementar *prepared statements* para todas as consultas SQL que envolvem entrada de usuário.
    *   Garantir validação e sanitização rigorosa de todas as entradas de usuário.
    *   Armazenar senhas de forma segura usando `password_hash()` e `password_verify()`.
*   **Refatoração:**
    *   Separar a lógica de negócios (PHP) da apresentação (HTML) usando um padrão de arquitetura (como MVC simplificado) ou, no mínimo, funções e classes PHP dedicadas.
    *   Criar funções reutilizáveis para tarefas comuns, como a exibição de cards de produtos.
    *   Melhorar o tratamento de erros com blocos `try-catch` e um sistema de log para registrar exceções e avisos.
*   **Configuração do Banco de Dados:** A senha do banco de dados está em texto plano no arquivo `db/conexao.php`. Embora seja um ambiente de desenvolvimento local, em produção, credenciais de banco de dados devem ser gerenciadas de forma mais segura (e.g., variáveis de ambiente).

## 3. Banco de Dados (MySQL via phpMyAdmin)

### Problemas Atuais:

*   **Estrutura Básica:** As tabelas `produtos` e `usuarios` são funcionais, mas bastante básicas. Não há chaves estrangeiras, índices adicionais ou outras otimizações que poderiam ser úteis para um sistema mais complexo.
*   **Tipos de Dados:** Os tipos de dados (`varchar`, `text`, `decimal`) são adequados para as colunas existentes, mas a ausência de uma coluna `criado_em` ou `atualizado_em` para a tabela `usuarios` pode limitar a auditoria ou funcionalidades futuras.

### Oportunidades de Melhoria:

*   **Otimização de Banco de Dados:** Adicionar índices às colunas frequentemente pesquisadas (e.g., `email` em `usuarios`, `nome` em `produtos`).
*   **Normalização:** Se o site crescer, considerar normalização adicional para evitar redundância e melhorar a integridade dos dados (e.g., tabelas para categorias de produtos).
*   **Campos de Auditoria:** Adicionar colunas `criado_em` e `atualizado_em` com `TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` para ambas as tabelas.

## Referências

[1] OWASP. (n.d.). *SQL Injection*. Retrieved from [https://owasp.org/www-community/attacks/SQL_Injection](https://owasp.org/www-community/attacks/SQL_Injection)
[2] OWASP. (n.d.). *Cross-Site Scripting (XSS)*. Retrieved from [https://owasp.org/www-community/attacks/xss/](https://owasp.org/www-community/attacks/xss/)
[3] PHP Manual. (n.d.). *password_hash*. Retrieved from [https://www.php.net/manual/en/function.password-hash.php](https://www.php.net/manual/en/function.password-hash.php)
[4] Wikipedia. (n.d.). *Model–view–controller*. Retrieved from [https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
[5] Wikipedia. (n.d.). *Don't repeat yourself*. Retrieved from [https://en.wikipedia.org/wiki/Don%27t_repeat_yourself](https://en.wikipedia.org/wiki/Don%27t_repeat_yourself)

