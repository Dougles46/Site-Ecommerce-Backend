<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db/conexao.php";
require_once __DIR__ . "/../db/Auth.php";

$auth = new Auth($db);
$isLoggedIn = $auth->isLoggedIn();
$currentUser = $auth->getCurrentUser();
$isAdmin = ($isLoggedIn && $currentUser["role"] === "admin");

// Define $page_title se não estiver definido
if (!isset($page_title)) {
    $page_title = "Crochê & Arte";
}

// Define $base_path se não estiver definido
if (!isset($base_path)) {
    $base_path = "./";
}

// Mensagens de sessão
$message = getSessionMessage();

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

<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="<?php echo $base_path; ?>index.php">
        <i class="fas fa-heart text-danger"></i> Crochê & Arte
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $base_path; ?>index.php"><i class="fas fa-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $base_path; ?>produtos.php"><i class="fas fa-box-open"></i> Produtos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $base_path; ?>sobre.php"><i class="fas fa-info-circle"></i> Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $base_path; ?>contato.php"><i class="fas fa-envelope"></i> Contato</a>
          </li>
          <?php if ($isAdmin): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $base_path; ?>admin/dashboard.php"><i class="fas fa-user-shield"></i> Admin</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $base_path; ?>admin/logout.php"><i class="fas fa-sign-out-alt"></i> Sair (Admin)</a>
            </li>
          <?php elseif ($isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $base_path; ?>perfil.php"><i class="fas fa-user"></i> Olá, <?php echo htmlspecialchars($currentUser["name"] ?? $currentUser["email"]); ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $base_path; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $base_path; ?>login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $base_path; ?>register.php"><i class="fas fa-user-plus"></i> Cadastre-se</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>

<main>
<?php if ($message): ?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo $message["type"]; ?> alert-dismissible fade show" role="alert">
            <?php echo $message["message"]; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

