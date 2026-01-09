<?php
require_once __DIR__ . "/secure.php";
require_once __DIR__ . "/../db/Produto.php";

$page_title = "Dashboard";

$produtoManager = new Produto($db);
$totalProdutos = $produtoManager->count();

include __DIR__ . "/../includes/header.php";
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add.php">
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
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Compartilhar</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Exportar</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <span data-feather="calendar"></span>
                        Esta semana
                    </button>
                </div>
            </div>

            <?php $message = getSessionMessage(); if ($message): ?>
                <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message["message"]; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Total de Produtos</h5>
                            <p class="card-text display-4 fw-bold"> <?php echo $totalProdutos; ?></p>
                            <a href="#" class="text-white stretched-link">Ver detalhes <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Vendas Hoje</h5>
                            <p class="card-text display-4 fw-bold">R$ 0,00</p>
                            <a href="#" class="text-white stretched-link">Ver detalhes <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Usuários Registrados</h5>
                            <p class="card-text display-4 fw-bold">1</p>
                            <a href="#" class="text-white stretched-link">Ver detalhes <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <h2 class="mt-4 mb-3">Últimos Produtos</h2>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $produtos = $produtoManager->getAll(5); // Limitar a 5 produtos para a dashboard
                        if (!empty($produtos)):
                            foreach ($produtos as $p):
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p["id"]); ?></td>
                                    <td><img src="<?php echo $base_path; ?>assets/img/<?php echo htmlspecialchars($p["imagem"]); ?>" alt="<?php echo htmlspecialchars($p["nome"]); ?>" width="50"></td>
                                    <td><?php echo htmlspecialchars($p["nome"]); ?></td>
                                    <td><?php echo formatCurrency($p["preco"]); ?></td>
                                    <td><?php echo htmlspecialchars(substr($p["descricao"], 0, 50)); ?>...</td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $p["id"]; ?>" class="btn btn-sm btn-info text-white me-1"><i class="fas fa-edit"></i></a>
                                        <a href="delete.php?id=<?php echo $p["id"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm(\'Tem certeza que deseja deletar este produto?\');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <tr>
                                <td colspan="6" class="text-center">Nenhum produto cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>

