<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db/conexao.php";
require_once __DIR__ . "/db/Auth.php";

$page_title = "Cadastro";
$base_path = "./";

$auth = new Auth($db);

$message = getSessionMessage();

if ($auth->isLoggedIn()) {
    redirectWithMessage("index.php", "Você já está logado.", "info");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf_token = $_POST["csrf_token"] ?? "";

    if (!verifyCSRFToken($csrf_token)) {
        redirectWithMessage("register.php", "Erro de segurança: Token CSRF inválido.", "danger");
    }

    try {
        $name = sanitizeInput($_POST["name"] ?? "");
        $email = sanitizeInput($_POST["email"] ?? "");
        $password = sanitizeInput($_POST["password"] ?? "");
        $confirm_password = sanitizeInput($_POST["confirm_password"] ?? "");

        if (empty($name)) {
            throw new Exception("Nome é obrigatório.");
        }

        if ($password !== $confirm_password) {
            throw new Exception("As senhas não coincidem.");
        }

        $auth->register($email, $password, $name, 'user'); // Registrar como usuário comum

        redirectWithMessage("login.php", "Cadastro realizado com sucesso! Faça login para continuar.", "success");
    } catch (Exception $e) {
        redirectWithMessage("register.php", "Erro ao cadastrar: " . $e->getMessage(), "danger");
    }
}

include __DIR__ . "/includes/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg p-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Cadastre-se</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message["message"]; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="register.php" method="POST" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Por favor, digite seu nome.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Por favor, digite um email válido.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">A senha deve ter no mínimo <?php echo PASSWORD_MIN_LENGTH; ?> caracteres.</div>
                        </div>
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <div class="invalid-feedback">Por favor, confirme sua senha.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus"></i> Cadastrar
                            </button>
                        </div>
                        <p class="text-center mt-3">Já tem uma conta? <a href="login.php">Faça Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

