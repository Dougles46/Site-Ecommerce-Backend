<?php
/**
 * conexao.php - Arquivo de conexão com o banco de dados
 * Utiliza a classe Database para gerenciar conexões seguras
 */

// Incluir arquivo de configuração
require_once __DIR__ . '/../config.php';

// Incluir classe Database
require_once __DIR__ . '/Database.php';

try {
    // Criar instância da conexão
    $db = new Database(
        DB_HOST,
        DB_NAME,
        DB_USER,
        DB_PASS
    );
    
    // Manter compatibilidade com código antigo que usa $conn
    $conn = $db->getConnection();
    
} catch (Exception $e) {
    logError($e->getMessage());
    die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
}
?>

