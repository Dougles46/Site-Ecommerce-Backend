<?php
require_once __DIR__ . "/secure.php";
require_once __DIR__ . "/../db/Produto.php";

$page_title = "Editar Produto";

$produtoManager = new Produto($db);
$produto = null;

if (isset($_GET["id"])) {
    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
    if ($id) {
        $produto = $produtoManager->getById($id);
    }
}

if (!$produto) {
    redirectWithMessage("dashboard.php", "Produto não encontrado.", "danger");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $csrf_token = $_POST["csrf_token"] ?? "";

    if (!verifyCSRFToken($csrf_token)) {
        redirectWithMessage("edit.php?id=" . $produto["id"], "Erro de segurança: Token CSRF inválido.", "danger");
    }

    try {
        $nome = sanitizeInput($_POST["nome"] ?? "");
        $descricao = sanitizeInput($_POST["descricao"] ?? "");
        $preco = filter_var($_POST["preco"] ?? 0, FILTER_VALIDATE_FLOAT);
        
        if ($preco === false) {
            throw new Exception("Preço inválido.");
        }

        $imagemAtual = $produto["imagem"];
        $novaImagem = $imagemAtual;

        if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
            // Fazer upload da nova imagem
            $uploadedImageName = $produtoManager->uploadImage($_FILES["imagem"]);
            
            // Se o upload foi bem-sucedido, deletar a imagem antiga (se não for a default)
            if ($uploadedImageName) {
                if ($imagemAtual !== "default.jpg") {
                    $produtoManager->deleteImage($imagemAtual);
                }
                $novaImagem = $uploadedImageName;
            }
        }

        $produtoManager->update($produto["id"], [
            "nome" => $nome,
            "descricao" => $descricao,
            "preco" => $preco,
            "imagem" => $novaImagem
        ]);

        redirectWithMessage("dashboard.php", "Produto atualizado com sucesso!", "success");
    } catch (Exception $e) {
        redirectWithMessage("edit.php?id=" . $produto["id"], "Erro ao atualizar produto: " . $e->getMessage(), "danger");
    }
}

include __DIR__ . "/../includes/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="add.php">
                            <i class="fas fa-plus-circle"></i> Adicionar Produto
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-users"></i> Gerenciar Usuários
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i> Configurações
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Relatórios</span>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-line"></i> Vendas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-file-alt"></i> Logs
                        </a>
                    </li>
                </ul>

                <hr class="my-3">

                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Editar Produto: <?php echo htmlspecialchars($produto["nome"]); ?></h1>
            </div>

            <?php $message = getSessionMessage(); if ($message): ?>
                <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message["message"]; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="edit.php?id=<?php echo $produto["id"]; ?>" method="POST" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($produto["nome"]); ?>" required>
                            <div class="invalid-feedback">Por favor, insira o nome do produto.</div>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($produto["descricao"]); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço</label>
                            <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?php echo htmlspecialchars($produto["preco"]); ?>" required>
                            <div class="invalid-feedback">Por favor, insira um preço válido.</div>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem Atual</label>
                            <?php if ($produto["imagem"]): ?>
                                <img src="<?php echo $base_path; ?>assets/img/<?php echo htmlspecialchars($produto["imagem"]); ?>" alt="<?php echo htmlspecialchars($produto["nome"]); ?>" class="img-thumbnail mb-2" style="max-width: 150px;">
                            <?php else: ?>
                                <p>Nenhuma imagem</p>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                            <small class="form-text text-muted">Envie uma nova imagem para substituir a atual.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

