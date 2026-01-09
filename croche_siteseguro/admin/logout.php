<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../db/conexao.php";
require_once __DIR__ . "/../db/Auth.php";

$auth = new Auth($db);
$auth->logout();

redirectWithMessage(SITE_URL . "/admin/login.php", "Logout realizado com sucesso!", "info");
?>
