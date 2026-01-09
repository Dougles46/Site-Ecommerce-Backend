# Guia Completo de Segurança e Implementação do Sistema

## 📋 Índice
1. [Arquitetura de Segurança](#arquitetura-de-segurança)
2. [Proteção contra Ataques Comuns](#proteção-contra-ataques-comuns)
3. [Fluxo de Login e Autenticação](#fluxo-de-login-e-autenticação)
4. [Painel Administrativo](#painel-administrativo)
5. [Boas Práticas Implementadas](#boas-práticas-implementadas)
6. [Credenciais de Teste](#credenciais-de-teste)

---

## Arquitetura de Segurança

### 1. Separação de Responsabilidades

O sistema foi projetado com **dois fluxos de login completamente separados**:

#### **Login de Usuário Comum** (`/login.php`)
- Interface simples para clientes
- Acesso apenas a funcionalidades de cliente (perfil, histórico de compras)
- Redirecionamento automático se tentar acessar painel admin

#### **Login de Administrador** (`/admin/login.php`)
- Interface separada com design profissional
- Proteção adicional contra acesso não autorizado
- Verificação de role (papel) do usuário
- Acesso ao painel administrativo completo

### 2. Banco de Dados

#### Tabela `usuarios`
```sql
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(100) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  nome VARCHAR(100),
  tentativas INT DEFAULT 0,
  bloqueado_ate DATETIME,
  role ENUM('user', 'admin') DEFAULT 'user',
  tentativas_login INT DEFAULT 0,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Campos de Segurança:**
- `role`: Diferencia usuários comuns de administradores
- `tentativas_login`: Rastreia tentativas de login falhadas
- `bloqueado_ate`: Bloqueia conta após múltiplas tentativas

---

## Proteção contra Ataques Comuns

### 1. **SQL Injection**
**Proteção:** Prepared Statements com Bind Parameters

```php
// ❌ INSEGURO (vulnerável a SQL Injection)
$query = "SELECT * FROM usuarios WHERE email = '" . $_POST['email'] . "'";

// ✅ SEGURO (com Prepared Statements)
$stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

**Implementação:** A classe `Database.php` utiliza `mysqli_prepare()` e `bind_param()` para todas as queries.

### 2. **Cross-Site Scripting (XSS)**
**Proteção:** Sanitização e Escape de Output

```php
// ❌ INSEGURO
echo "Bem-vindo, " . $_SESSION['user_name'];

// ✅ SEGURO
echo "Bem-vindo, " . htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8');
```

**Implementação:**
- Função `sanitizeInput()` para limpar entrada do usuário
- `htmlspecialchars()` para escapar output
- Validação de email com `filter_var()`

### 3. **Cross-Site Request Forgery (CSRF)**
**Proteção:** Tokens CSRF únicos

```php
// Gerar token CSRF
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verificar token CSRF
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}
```

**Implementação:**
- Token gerado em cada formulário
- Verificado antes de processar dados POST
- Uso de `hash_equals()` para comparação segura

### 4. **Brute Force Attack**
**Proteção:** Limite de tentativas de login

```php
// Após 5 tentativas falhas, bloqueia por 15 minutos
if ($user['tentativas_login'] >= 5) {
    if (strtotime($user['bloqueado_ate']) > time()) {
        throw new Exception("Conta bloqueada temporariamente.");
    }
}
```

**Implementação:**
- Rastreamento de tentativas falhas
- Bloqueio automático após 5 tentativas
- Desbloqueio automático após 15 minutos

### 5. **Senhas Fracas**
**Proteção:** Hash seguro com PASSWORD_DEFAULT

```php
// ❌ INSEGURO
$hashed = md5($password);

// ✅ SEGURO (bcrypt com salt automático)
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Verificar senha
if (password_verify($password, $hashed)) {
    // Senha correta
}
```

**Implementação:**
- `password_hash()` com `PASSWORD_DEFAULT` (bcrypt)
- Requisito mínimo de 8 caracteres
- `password_verify()` para comparação segura

### 6. **Session Hijacking**
**Proteção:** Regeneração de Session ID

```php
// Após login bem-sucedido
session_regenerate_id(true);
```

**Implementação:**
- Session ID regenerado após login
- Cookies com flags `HttpOnly` e `Secure`
- Timeout de sessão configurável

### 7. **Acesso Não Autorizado**
**Proteção:** Verificação de Role e Redirecionamento

```php
// Verificar se é admin
if ($currentUser['role'] !== 'admin') {
    redirectWithMessage("../index.php", "Acesso negado", "danger");
}
```

**Implementação:**
- Verificação de `role` em cada página protegida
- Redirecionamento automático para usuários não autorizados
- Logs de tentativas de acesso não autorizado

---

## Fluxo de Login e Autenticação

### Usuário Comum

```
1. Acessa /login.php
   ↓
2. Preenche email e senha
   ↓
3. Sistema valida credenciais
   ↓
4. Verifica se role = 'user'
   ↓
5. Regenera session ID
   ↓
6. Armazena dados na sessão
   ↓
7. Redireciona para /index.php
   ↓
8. Navbar mostra "Olá, [Nome]" e opção "Sair"
```

### Administrador

```
1. Acessa /admin/login.php
   ↓
2. Preenche email e senha
   ↓
3. Sistema valida credenciais
   ↓
4. Verifica se role = 'admin'
   ↓
5. Se não for admin, faz logout e redireciona
   ↓
6. Regenera session ID
   ↓
7. Redireciona para /admin/dashboard.php
   ↓
8. Navbar mostra "Admin" e opção "Sair (Admin)"
```

---

## Painel Administrativo

### Funcionalidades

#### 1. **Dashboard**
- Estatísticas de produtos
- Vendas do dia/semana
- Usuários registrados
- Últimos produtos cadastrados

#### 2. **Gerenciamento de Produtos (CRUD)**
- **Create:** Adicionar novo produto
- **Read:** Visualizar lista de produtos
- **Update:** Editar produto existente
- **Delete:** Remover produto

#### 3. **Gerenciamento de Usuários**
- Visualizar todos os usuários
- Editar informações do usuário
- Deletar usuários
- Visualizar histórico de atividades

#### 4. **Relatórios**
- Vendas por período
- Logs de atividades
- Tentativas de acesso não autorizado

---

## Boas Práticas Implementadas

### 1. **Validação de Entrada**
```php
// Sanitizar entrada
$email = sanitizeInput($_POST['email']);

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Email inválido");
}
```

### 2. **Escape de Output**
```php
// Sempre escapar dados exibidos
echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
```

### 3. **Logging de Atividades**
```php
// Registrar tentativas de login
logError("Tentativa de login com email: $email");
```

### 4. **Tratamento de Erros**
```php
try {
    // Código que pode gerar exceção
} catch (Exception $e) {
    logError($e->getMessage());
    redirectWithMessage("login.php", "Erro ao fazer login", "danger");
}
```

### 5. **Configurações Seguras**
```php
// config.php
define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_BLOCK_TIME', 900); // 15 minutos
define('SESSION_TIMEOUT', 3600); // 1 hora
```

### 6. **Proteção de Arquivo**
```php
// Impedir acesso direto a arquivos sensíveis
if (!defined('SECURE_ACCESS')) {
    die('Acesso negado');
}
```

---

## Credenciais de Teste

### Usuário Comum
- **Email:** joao@example.com
- **Senha:** Senha123!

### Administrador
- **Email:** admin@croche.art
- **Senha:** Admin@123456

---

## Checklist de Segurança

- [x] Senhas hasheadas com bcrypt
- [x] Prepared statements para SQL Injection
- [x] Sanitização de entrada (XSS)
- [x] Tokens CSRF em formulários
- [x] Limite de tentativas de login
- [x] Bloqueio de conta após múltiplas tentativas
- [x] Separação de roles (user/admin)
- [x] Regeneração de session ID após login
- [x] Escape de output
- [x] Validação de email
- [x] Logs de atividades
- [x] Tratamento de erros seguro
- [x] Redirecionamento automático para não autorizados
- [x] HTTPS recomendado em produção
- [x] Cookies HttpOnly e Secure

---

## Recomendações para Produção

1. **HTTPS Obrigatório**
   ```php
   // Forçar HTTPS
   if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
       header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
       exit;
   }
   ```

2. **Rate Limiting**
   - Implementar rate limiting para APIs
   - Limitar requisições por IP

3. **Web Application Firewall (WAF)**
   - Usar Cloudflare ou similar
   - Proteger contra DDoS

4. **Backup Regular**
   - Backup diário do banco de dados
   - Armazenamento seguro em nuvem

5. **Monitoramento**
   - Monitorar logs de erro
   - Alertas para atividades suspeitas
   - Análise de segurança regular

6. **Atualizações**
   - Manter PHP atualizado
   - Atualizar dependências regularmente
   - Patches de segurança imediatos

---

## Conclusão

O sistema foi implementado com **múltiplas camadas de segurança** para proteger contra os ataques mais comuns. A arquitetura separada de login para usuários e administradores garante que apenas usuários autorizados acessem funcionalidades sensíveis.

Para dúvidas ou melhorias, consulte a documentação do código ou entre em contato com o desenvolvedor.

