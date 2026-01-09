<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db/conexao.php";
require_once __DIR__ . "/../db/Auth.php";

$auth = new Auth($db);
$auth->requireLogin();

// Obter informações do usuário logado
$currentUser = $auth->getCurrentUser();

// Definir base_path para recursos estáticos
$base_path = "../";

// Definir página atual para o header
$page_title = "Painel Admin";
?>
