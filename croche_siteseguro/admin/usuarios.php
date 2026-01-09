<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db/conexao.php";
require_once __DIR__ . "/../db/Auth.php";

$page_title = "Gerenciar Usuários";
$base_path = "../";

$auth = new Auth($db);
$auth->requireLogin();
$auth->requireAdmin();

$message = getSessionMessage();

// Buscar todos os usuários
$usuarios = $db->select("SELECT id, email, nome, role, criado_em FROM usuarios ORDER BY criado_em DESC");

// Processar exclusão de usuário
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "delete") {
    $csrf_token = $_POST["csrf_token"] ?? "";
    $user_id = (int)($_POST["user_id"] ?? 0);

    if (!verifyCSRFToken($csrf_token)) {
        redirectWithMessage("usuarios.php", "Erro de segurança: Token CSRF inválido.", "danger");
    }

    if ($user_id === 0) {
        redirectWithMessage("usuarios.php", "ID de usuário inválido.", "danger");
    }

    // Impedir que o admin delete a si mesmo
    $currentUser = $auth->getCurrentUser();
    if ($user_id === $currentUser["id"]) {
        redirectWithMessage("usuarios.php", "Você não pode deletar sua própria conta.", "danger");
    }

    try {
        $db->delete("usuarios", "id = ?", [$user_id]);
        redirectWithMessage("usuarios.php", "Usuário deletado com sucesso!", "success");
    } catch (Exception $e) {
        redirectWithMessage("usuarios.php", "Erro ao deletar usuário: " . $e->getMessage(), "danger");
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
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="add.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-plus-circle"></i> Adicionar Produto
                </a>
                <a href="usuarios.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-users"></i> Gerenciar Usuários
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div class="col-md-9">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users"></i> Gerenciar Usuários
                    </h3>
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message["message"]; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($usuarios)): ?>
                        <p class="text-muted">Nenhum usuário encontrado.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Email</th>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Data de Cadastro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $index => $usuario): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($usuario["email"]); ?></td>
                                            <td><?php echo htmlspecialchars($usuario["nome"] ?? "N/A"); ?></td>
                                            <td>
                                                <?php if ($usuario["role"] === "admin"): ?>
                                                    <span class="badge bg-danger">Administrador</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info">Usuário</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date("d/m/Y H:i", strtotime($usuario["criado_em"])); ?></td>
                                            <td>
                                                <a href="editar_usuario.php?id=<?php echo $usuario["id"]; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($usuario["id"] !== $auth->getCurrentUser()["id"]): ?>
                                                    <form action="usuarios.php" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este usuário?');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="user_id" value="<?php echo $usuario["id"]; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Deletar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Você</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

