<?php
/**
 * Produto.php - Classe para gerenciar operações com produtos
 */

class Produto {
    private $db;
    
    /**
     * Construtor
     */
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Obter todos os produtos
     */
    public function getAll($limit = null, $offset = 0) {
        try {
            $sql = "SELECT * FROM produtos ORDER BY criado_em DESC";
            
            if ($limit) {
                $sql .= " LIMIT ? OFFSET ?";
                return $this->db->select($sql, [$limit, $offset]);
            }
            
            return $this->db->select($sql);
        } catch (Exception $e) {
            logError($e->getMessage());
            return [];
        }
    }
    
    /**
     * Obter produto por ID
     */
    public function getById($id) {
        try {
            return $this->db->selectOne(
                "SELECT * FROM produtos WHERE id = ?",
                [$id]
            );
        } catch (Exception $e) {
            logError($e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar produtos por nome ou descrição
     */
    public function search($query) {
        try {
            $searchTerm = "%{$query}%";
            return $this->db->select(
                "SELECT * FROM produtos WHERE nome LIKE ? OR descricao LIKE ? ORDER BY criado_em DESC",
                [$searchTerm, $searchTerm]
            );
        } catch (Exception $e) {
            logError($e->getMessage());
            return [];
        }
    }
    
    /**
     * Criar novo produto
     */
    public function create($data) {
        try {
            // Validar dados obrigatórios
            if (empty($data['nome']) || empty($data['preco'])) {
                throw new Exception("Nome e preço são obrigatórios");
            }
            
            // Sanitizar dados
            $produto = [
                'nome' => sanitizeInput($data['nome']),
                'descricao' => sanitizeInput($data['descricao'] ?? ''),
                'preco' => floatval($data['preco']),
                'imagem' => sanitizeInput($data['imagem'] ?? 'default.jpg'),
                'criado_em' => date('Y-m-d H:i:s')
            ];
            
            // Validar preço
            if ($produto['preco'] <= 0) {
                throw new Exception("Preço deve ser maior que zero");
            }
            
            return $this->db->insert('produtos', $produto);
        } catch (Exception $e) {
            logError($e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Atualizar produto
     */
    public function update($id, $data) {
        try {
            // Validar dados obrigatórios
            if (empty($data['nome']) || empty($data['preco'])) {
                throw new Exception("Nome e preço são obrigatórios");
            }
            
            // Sanitizar dados
            $produto = [
                'nome' => sanitizeInput($data['nome']),
                'descricao' => sanitizeInput($data['descricao'] ?? ''),
                'preco' => floatval($data['preco'])
            ];
            
            // Validar preço
            if ($produto['preco'] <= 0) {
                throw new Exception("Preço deve ser maior que zero");
            }
            
            // Adicionar imagem se fornecida
            if (!empty($data['imagem'])) {
                $produto['imagem'] = sanitizeInput($data['imagem']);
            }
            
            return $this->db->update(
                'produtos',
                $produto,
                'id = ?',
                [$id]
            );
        } catch (Exception $e) {
            logError($e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Deletar produto
     */
    public function delete($id) {
        try {
            // Obter produto para deletar imagem
            $produto = $this->getById($id);
            
            if (!$produto) {
                throw new Exception("Produto não encontrado");
            }
            
            // Deletar imagem se existir
            if (!empty($produto['imagem'])) {
                $imagePath = UPLOAD_DIR . $produto['imagem'];
                if (file_exists($imagePath) && $produto['imagem'] !== 'default.jpg') {
                    unlink($imagePath);
                }
            }
            
            // Deletar produto
            return $this->db->delete('produtos', 'id = ?', [$id]);
        } catch (Exception $e) {
            logError($e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Contar total de produtos
     */
    public function count() {
        try {
            return $this->db->count('produtos');
        } catch (Exception $e) {
            logError($e->getMessage());
            return 0;
        }
    }
    
    /**
     * Upload de imagem
     */
    public function uploadImage($file) {
        try {
            // Validar arquivo
            if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
                throw new Exception("Nenhum arquivo foi enviado");
            }
            
            // Validar tamanho
            if ($file['size'] > UPLOAD_MAX_SIZE) {
                throw new Exception("Arquivo muito grande. Máximo: 5MB");
            }
            
            // Validar tipo MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, ALLOWED_MIME_TYPES)) {
                throw new Exception("Tipo de arquivo não permitido");
            }
            
            // Validar extensão
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ALLOWED_EXTENSIONS)) {
                throw new Exception("Extensão não permitida");
            }
            
            // Gerar nome único
            $filename = uniqid('img_') . '.' . $extension;
            $filepath = UPLOAD_DIR . $filename;
            
            // Criar diretório se não existir
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            
            // Mover arquivo
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception("Erro ao fazer upload do arquivo");
            }
            
            return $filename;
        } catch (Exception $e) {
            logError($e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Deletar imagem
     */
    public function deleteImage($filename) {
        try {
            if (empty($filename) || $filename === 'default.jpg') {
                return true;
            }
            
            $filepath = UPLOAD_DIR . $filename;
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            return true;
        } catch (Exception $e) {
            logError($e->getMessage());
            return false;
        }
    }
}
?>

