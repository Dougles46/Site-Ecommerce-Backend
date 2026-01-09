<?php
/**
 * config.php - Configurações globais do site
 */

// Exibir erros (apenas para desenvolvimento, DESATIVAR em produção)
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Iniciar sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// URL base do site
define("SITE_URL", "https://8000-i37fpy4wc167y1rzs618k-c715e77b.manus.computer");

// Configurações de Banco de Dados
define("DB_HOST", "localhost");
define("DB_NAME", "croche_db");
define("DB_USER", "root");
define("DB_PASS", ""); // Senha vazia para o root no ambiente de sandbox

// Configurações de Segurança
define("PASSWORD_MIN_LENGTH", 8);
define("MAX_LOGIN_ATTEMPTS", 5); // Número máximo de tentativas de login antes do bloqueio
define("LOCKOUT_TIME", 600); // Tempo de bloqueio em segundos (10 minutos)
define("CSRF_TOKEN_SECRET", "uma_chave_secreta_bem_longa_e_aleatoria_aqui"); // Mude isso para uma string aleatória e forte

// Configurações de Upload de Imagens
define("UPLOAD_DIR", __DIR__ . "/assets/img/");
define("UPLOAD_MAX_SIZE", 5 * 1024 * 1024); // 5MB
define("ALLOWED_MIME_TYPES", ["image/jpeg", "image/png", "image/gif", "image/webp"]);
define("ALLOWED_EXTENSIONS", ["jpg", "jpeg", "png", "gif", "webp"]);

// Funções de Ajuda

/**
 * Sanitiza uma string para prevenir XSS.
 */
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Redireciona para uma URL com uma mensagem de sessão.
 */
function redirectWithMessage($url, $message, $type = "info") {
    $_SESSION["message"] = ["message" => $message, "type" => $type];
    header("Location: " . $url);
    exit;
}

/**
 * Obtém e limpa a mensagem de sessão.
 */
function getSessionMessage() {
    if (isset($_SESSION["message"])) {
        $message = $_SESSION["message"];
        unset($_SESSION["message"]);
        return $message;
    }
    return null;
}

/**
 * Gera um token CSRF.
 */
function generateCSRFToken() {
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

/**
 * Verifica um token CSRF.
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION["csrf_token"]) || !hash_equals($_SESSION["csrf_token"], $token)) {
        return false;
    }
    return true;
}

/**
 * Formata um valor monetário.
 */
function formatCurrency($value) {
    return "R$ " . number_format($value, 2, ",", ".");
}

/**
 * Função de log de erros.
 */
function logError($message) {
    error_log(date("[Y-m-d H:i:s]") . " " . $message . "\n", 3, __DIR__ . "/logs/app_errors.log");
}

// Criar diretório de logs se não existir
if (!is_dir(__DIR__ . "/logs")) {
    mkdir(__DIR__ . "/logs", 0755, true);
}

// Incluir a conexão com o banco de dados
require_once __DIR__ . "/db/conexao.php";

?>

