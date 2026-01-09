<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db/conexao.php";
require_once __DIR__ . "/db/Auth.php";

$page_title = "Login";
$base_path = "./";

$auth = new Auth($db);

$message = getSessionMessage();

if ($auth->isLoggedIn()) {
    redirectWithMessage("index.php", "Você já está logado.", "info");
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
        // Redirecionar para dashboard do usuário ou página inicial
        redirectWithMessage("index.php", "Login realizado com sucesso!", "success");
    } catch (Exception $e) {
        redirectWithMessage("login.php", $e->getMessage(), "danger");
    }
}

include __DIR__ . "/includes/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg p-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Login</h2>
                    
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
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Entrar
                            </button>
                        </div>
                        <p class="text-center mt-3">Não tem uma conta? <a href="register.php">Cadastre-se</a></p>
                        <p class="text-center mt-2"><a href="#">Esqueceu a senha?</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

