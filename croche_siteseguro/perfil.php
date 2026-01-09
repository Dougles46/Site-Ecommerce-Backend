<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/db/conexao.php";
require_once __DIR__ . "/db/Auth.php";

$auth = new Auth($db);
$auth->requireLogin();

$page_title = "Meu Perfil";
$base_path = "./";

$currentUser = $auth->getCurrentUser();
$message = getSessionMessage();

// Lógica para atualizar perfil
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf_token = $_POST["csrf_token"] ?? "";

    if (!verifyCSRFToken($csrf_token)) {
        redirectWithMessage("perfil.php", "Erro de segurança: Token CSRF inválido.", "danger");
    }

    try {
        $name = sanitizeInput($_POST["name"] ?? "");
        $email = sanitizeInput($_POST["email"] ?? "");

        if (empty($name)) {
            throw new Exception("Nome é obrigatório.");
        }

        // Verificar se o email já existe para outro usuário
        $existingUser = $db->selectOne("SELECT id FROM usuarios WHERE email = ? AND id != ?", [$email, $currentUser["id"]]);
        if ($existingUser) {
            throw new Exception("Este email já está em uso por outro usuário.");
        }

        $dataToUpdate = [
            "nome" => $name,
            "email" => $email
        ];

        // Se a senha for fornecida, atualizá-la
        if (!empty($_POST["password"])) {
            $password = sanitizeInput($_POST["password"] ?? "");
            $confirm_password = sanitizeInput($_POST["confirm_password"] ?? "");

            if ($password !== $confirm_password) {
                throw new Exception("As senhas não coincidem.");
            }
            if (strlen($password) < PASSWORD_MIN_LENGTH) {
                throw new Exception("A senha deve ter no mínimo " . PASSWORD_MIN_LENGTH . " caracteres.");
            }
            $dataToUpdate["senha"] = password_hash($password, PASSWORD_DEFAULT);
        }

        $db->update("usuarios", $dataToUpdate, "id = ?", [$currentUser["id"]]);

        // Atualizar sessão com novos dados
        $_SESSION["user_name"] = $name;
        $_SESSION["user_email"] = $email;
        
        redirectWithMessage("perfil.php", "Perfil atualizado com sucesso!", "success");
    } catch (Exception $e) {
        redirectWithMessage("perfil.php", "Erro ao atualizar perfil: " . $e->getMessage(), "danger");
    }
}

// Recarregar dados do usuário após possível atualização
$currentUser = $auth->getCurrentUser();

include __DIR__ . "/includes/header.php";
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg p-4">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Meu Perfil</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message["message"]; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="perfil.php" method="POST" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($currentUser["name"] ?? ""); ?>" required>
                            <div class="invalid-feedback">Por favor, digite seu nome.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($currentUser["email"] ?? ""); ?>" required>
                            <div class="invalid-feedback">Por favor, digite um email válido.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="invalid-feedback">A senha deve ter no mínimo <?php echo PASSWORD_MIN_LENGTH; ?> caracteres.</div>
                        </div>
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            <div class="invalid-feedback">Por favor, confirme sua senha.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>

