<?php
/**
 * Database.php - Classe para gerenciar conexões com o banco de dados
 * Implementa prepared statements para segurança contra SQL injection
 */

class Database {
    private $connection;
    private $host;
    private $user;
    private $password;
    private $database;
    private $charset;
    
    /**
     * Construtor
     */
    public function __construct($host, $database, $user, $password, $charset = 'utf8mb4') {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;
        
        $this->connect();
    }
    
    /**
     * Conectar ao banco de dados
     */
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->host,
                $this->user,
                $this->password,
                $this->database
            );
            
            if ($this->connection->connect_error) {
                throw new Exception("Erro de conexão: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset($this->charset);
        } catch (Exception $e) {
            logError($e->getMessage());
            die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
        }
    }
    
    /**
     * Executar query com prepared statement
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Erro ao preparar query: " . $this->connection->error);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
                
                $stmt->bind_param($types, ...$params);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Erro ao executar query: " . $stmt->error);
            }
            
            return $stmt;
        } catch (Exception $e) {
            logError($e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Selecionar registros
     */
    public function select($sql, $params = []) {
        try {
            $stmt = $this->query($sql, $params);
            $result = $stmt->get_result();
            $data = [];
            
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            
            $stmt->close();
            return $data;
        } catch (Exception $e) {
            logError($e->getMessage());
            return [];
        }
    }
    
    /**
     * Selecionar um registro
     */
    public function selectOne($sql, $params = []) {
        try {
            $stmt = $this->query($sql, $params);
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            return $data;
        } catch (Exception $e) {
            logError($e->getMessage());
            return null;
        }
    }
    
    /**
     * Inserir registro
     */
    public function insert($table, $data) {
        try {
            $columns = array_keys($data);
            $values = array_values($data);
            $placeholders = array_fill(0, count($columns), '?');
            
            $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            
            $stmt = $this->query($sql, $values);
            $id = $this->connection->insert_id;
            $stmt->close();
            
            return $id;
        } catch (Exception $e) {
            logError($e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar registro
     */
    public function update($table, $data, $where, $whereParams = []) {
        try {
            $set = [];
            $values = [];
            
            foreach ($data as $column => $value) {
                $set[] = "{$column} = ?";
                $values[] = $value;
            }
            
            $values = array_merge($values, $whereParams);
            
            $sql = "UPDATE {$table} SET " . implode(', ', $set) . " WHERE {$where}";
            
            $stmt = $this->query($sql, $values);
            $affected = $stmt->affected_rows;
            $stmt->close();
            
            return $affected;
        } catch (Exception $e) {
            logError($e->getMessage());
            return false;
        }
    }
    
    /**
     * Deletar registro
     */
    public function delete($table, $where, $params = []) {
        try {
            $sql = "DELETE FROM {$table} WHERE {$where}";
            
            $stmt = $this->query($sql, $params);
            $affected = $stmt->affected_rows;
            $stmt->close();
            
            return $affected;
        } catch (Exception $e) {
            logError($e->getMessage());
            return false;
        }
    }
    
    /**
     * Contar registros
     */
    public function count($table, $where = '', $params = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$table}";
            
            if (!empty($where)) {
                $sql .= " WHERE {$where}";
            }
            
            $result = $this->selectOne($sql, $params);
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            logError($e->getMessage());
            return 0;
        }
    }
    
    /**
     * Fechar conexão
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Obter conexão
     */
    public function getConnection() {
        return $this->connection;
    }
}
?>

