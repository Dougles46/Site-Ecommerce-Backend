<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db/conexao.php";
require_once __DIR__ . "/../db/Auth.php";

$page_title = "Editar Usuário";
$base_path = "../";

$auth = new Auth($db);
$auth->requireLogin();
$auth->requireAdmin();

$message = getSessionMessage();

// Obter ID do usuário a editar
$user_id = (int)($_GET["id"] ?? 0);
if ($user_id === 0) {
    redirectWithMessage("usuarios.php", "ID de usuário inválido.", "danger");
}

// Buscar usuário
$usuario = $db->selectOne("SELECT * FROM usuarios WHERE id = ?", [$user_id]);
if (!$usuario) {
    redirectWithMessage("usuarios.php", "Usuário não encontrado.", "danger");
}

// Processar atualização
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf_token = $_POST["csrf_token"] ?? "";

    if (!verifyCSRFToken($csrf_token)) {
        redirectWithMessage("editar_usuario.php?id=$user_id", "Erro de segurança: Token CSRF inválido.", "danger");
    }

    try {
        $nome = sanitizeInput($_POST["nome"] ?? "");
        $email = sanitizeInput($_POST["email"] ?? "");
        $role = sanitizeInput($_POST["role"] ?? "user");

        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido.");
        }

        // Verificar se o email já existe para outro usuário
        $existingUser = $db->selectOne("SELECT id FROM usuarios WHERE email = ? AND id != ?", [$email, $user_id]);
        if ($existingUser) {
            throw new Exception("Este email já está em uso por outro usuário.");
        }

        // Validar role
        if (!in_array($role, ["user", "admin"])) {
            throw new Exception("Tipo de usuário inválido.");
        }

        // Atualizar usuário
        $db->update("usuarios", [
            "nome" => $nome,
            "email" => $email,
            "role" => $role
        ], "id = ?", [$user_id]);

        redirectWithMessage("usuarios.php", "Usuário atualizado com sucesso!", "success");
    } catch (Exception $e) {
        redirectWithMessage("editar_usuario.php?id=$user_id", "Erro: " . $e->getMessage(), "danger");
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
</head>
<body>

<?php include __DIR__ . "/../includes/header.php"; ?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Editar Usuário
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message["message"]; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="editar_usuario.php?id=<?php echo $user_id; ?>" method="POST" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario["nome"] ?? ""); ?>" required>
                            <div class="invalid-feedback">Por favor, digite o nome.</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario["email"]); ?>" required>
                            <div class="invalid-feedback">Por favor, digite um email válido.</div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Tipo de Usuário</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user" <?php echo $usuario["role"] === "user" ? "selected" : ""; ?>>Usuário Comum</option>
                                <option value="admin" <?php echo $usuario["role"] === "admin" ? "selected" : ""; ?>>Administrador</option>
                            </select>
                            <div class="invalid-feedback">Por favor, selecione um tipo de usuário.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="usuarios.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

