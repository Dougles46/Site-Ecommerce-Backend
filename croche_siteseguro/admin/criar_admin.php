<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db/conexao.php";
require_once __DIR__ . "/../db/Auth.php";

$page_title = "Criar Admin";
$base_path = "../";

$auth = new Auth($db);

$message = getSessionMessage();

// Verificar se já existe um admin
$adminExists = $db->selectOne("SELECT id FROM usuarios WHERE role = 'admin' LIMIT 1");

if ($adminExists) {
    redirectWithMessage("../index.php", "Um usuário administrador já foi criado. Não é possível criar outro.", "danger");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf_token = $_POST["csrf_token"] ?? "";

    if (!verifyCSRFToken($csrf_token)) {
        redirectWithMessage("criar_admin.php", "Erro de segurança: Token CSRF inválido.", "danger");
    }

    try {
        $email = sanitizeInput($_POST["email"] ?? "");
        $password = sanitizeInput($_POST["password"] ?? "");
        $name = sanitizeInput($_POST["name"] ?? "Admin");

        $userId = $auth->register($email, $password, $name, 'admin');
        
        if ($userId) {
            redirectWithMessage("login.php", "Usuário administrador criado com sucesso! Faça login para acessar o painel.", "success");
        } else {
            throw new Exception("Erro desconhecido ao criar usuário.");
        }
    } catch (Exception $e) {
        redirectWithMessage("criar_admin.php", "Erro: " . $e->getMessage(), "danger");
    }
}

include __DIR__ . "/../includes/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg p-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Criar Usuário Administrador</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message["message"]; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="criar_admin.php" method="POST" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome (opcional)</label>
                            <input type="text" class="form-control" id="name" name="name" value="Admin">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Por favor, digite um email válido.</div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">A senha deve ter no mínimo <?php echo PASSWORD_MIN_LENGTH; ?> caracteres.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus"></i> Criar Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

