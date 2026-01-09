<?php
require_once __DIR__ . "/secure.php";
require_once __DIR__ . "/../db/Produto.php";

$produtoManager = new Produto($db);

if (isset($_GET["id"])) {
    $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $produtoManager->delete($id);
            redirectWithMessage("dashboard.php", "Produto excluído com sucesso!", "success");
        } catch (Exception $e) {
            redirectWithMessage("dashboard.php", "Erro ao excluir produto: " . $e->getMessage(), "danger");
        }
    }
} else {
    redirectWithMessage("dashboard.php", "ID do produto não fornecido.", "danger");
}
?>
