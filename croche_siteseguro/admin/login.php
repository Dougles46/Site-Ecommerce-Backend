<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db/conexao.php";
require_once __DIR__ . "/../db/Auth.php";

$page_title = "Login Admin";
$base_path = "../";

$auth = new Auth($db);

$message = getSessionMessage();

// Se já está logado como admin, redireciona para o dashboard
if ($auth->isLoggedIn()) {
    $currentUser = $auth->getCurrentUser();
    if ($currentUser["role"] === "admin") {
        redirectWithMessage("dashboard.php", "Você já está logado como administrador.", "info");
    } else {
        redirectWithMessage("../index.php", "Você está logado como usuário comum. Faça logout para acessar o painel admin.", "warning");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = sanitizeInput($_POST["email"] ?? "");
    $password = sanitizeInput($_POST["password"] ?? "");
    $csrf_token = $_POST["csrf_token"] ?? "";

    if (!verifyCSRFToken($csrf_token)) {
        redirectWithMessage("login.php", "Erro de segurança: Token CSRF inválido.", "danger");
    }

    try {
        $auth->login($email, $password);
        
        // Verificar se o usuário é admin
        $currentUser = $auth->getCurrentUser();
        if ($currentUser["role"] !== "admin") {
            $auth->logout();
            redirectWithMessage("login.php", "Acesso negado: Apenas administradores podem acessar este painel.", "danger");
        }
        
        redirectWithMessage("dashboard.php", "Login realizado com sucesso!", "success");
    } catch (Exception $e) {
        redirectWithMessage("login.php", $e->getMessage(), "danger");
    }
}

?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($page_title); ?> - Crochê & Arte</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/style.css">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      background: white;
      border-radius: 10px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      padding: 40px;
      max-width: 400px;
      width: 100%;
    }
    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .login-header h2 {
      color: #333;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .login-header p {
      color: #666;
      font-size: 14px;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 10px 20px;
      font-weight: 600;
      transition: transform 0.2s;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
  </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h2><i class="fas fa-lock"></i> Painel Admin</h2>
        <p>Acesso Restrito - Apenas Administradores</p>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
            <?php echo $message["message"]; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">Por favor, digite um email válido.</div>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Senha</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">Por favor, digite sua senha.</div>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-login btn-lg text-white">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </div>
    </form>

    <hr class="my-4">
    <p class="text-center text-muted small">
        <a href="../index.php" class="text-decoration-none">Voltar para a página inicial</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

