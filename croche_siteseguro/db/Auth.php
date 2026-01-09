<?php
/**
 * Auth.php - Classe para gerenciamento de autenticação e autorização
 */

class Auth {
    private $db;
    private $session_name = 'croche_session';
    private $session_lifetime = 3600; // 1 hora

    public function __construct($database) {
        $this->db = $database;
        if (session_status() == PHP_SESSION_NONE) {
            session_name($this->session_name);
            session_set_cookie_params($this->session_lifetime, '/', '', false, true);
            session_start();
        }
    }

    /**
     * Registra um novo usuário.
     */
    public function register($email, $password, $name = null, $role = 'user') {
        if (empty($email) || empty($password)) {
            throw new Exception("Email e senha são obrigatórios.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido.");
        }

        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            throw new Exception("A senha deve ter no mínimo " . PASSWORD_MIN_LENGTH . " caracteres.");
        }

        // Verificar se o email já existe
        $existingUser = $this->db->selectOne("SELECT id FROM usuarios WHERE email = ?", [$email]);
        if ($existingUser) {
            throw new Exception("Este email já está cadastrado.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $data = [
            'email' => $email,
            'senha' => $hashedPassword,
            'nome' => $name,
            'role' => $role
        ];
        return $this->db->insert('usuarios', $data);
    }

    /**
     * Realiza o login do usuário.
     */
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            throw new Exception("Email e senha são obrigatórios.");
        }

        $user = $this->db->selectOne("SELECT * FROM usuarios WHERE email = ?", [$email]);

        if (!$user) {
            throw new Exception("Email ou senha incorretos.");
        }

        // Verificar bloqueio por tentativas
        if ($user['bloqueado_ate'] && strtotime($user['bloqueado_ate']) > time()) {
            throw new Exception("Sua conta está bloqueada temporariamente devido a muitas tentativas de login. Tente novamente mais tarde.");
        }

        if (password_verify($password, $user['senha'])) {
            // Login bem-sucedido
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['LAST_ACTIVITY'] = time();

            // Resetar tentativas de login
            $this->db->update('usuarios', ['tentativas_login' => 0, 'bloqueado_ate' => null], 'id = ?', [$user['id']]);

            return true;
        } else {
            // Login falhou
            $this->recordFailedLoginAttempt($user['id']);
            throw new Exception("Email ou senha incorretos.");
        }
    }

    /**
     * Registra uma tentativa de login falha e bloqueia o usuário se exceder o limite.
     */
    private function recordFailedLoginAttempt($userId) {
        $this->db->query("UPDATE usuarios SET tentativas_login = tentativas_login + 1 WHERE id = ?", [$userId]);
        $user = $this->db->selectOne("SELECT tentativas_login FROM usuarios WHERE id = ?", [$userId]);

        if ($user && $user['tentativas_login'] >= MAX_LOGIN_ATTEMPTS) {
            $lockoutTime = date('Y-m-d H:i:s', time() + LOCKOUT_TIME);
            $this->db->update('usuarios', ['bloqueado_ate' => $lockoutTime], 'id = ?', [$userId]);
        }
    }

    /**
     * Verifica se o usuário está logado.
     */
    public function isLoggedIn() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['LAST_ACTIVITY'])) {
            return false;
        }

        // Verificar inatividade
        if ((time() - $_SESSION['LAST_ACTIVITY']) > $this->session_lifetime) {
            $this->logout();
            return false;
        }

        $_SESSION['LAST_ACTIVITY'] = time(); // Atualizar tempo de atividade
        return true;
    }

    /**
     * Requer que o usuário esteja logado, redireciona para o login se não estiver.
     */
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            redirectWithMessage(SITE_URL . '/admin/login.php', 'Você precisa estar logado para acessar esta página.', 'warning');
        }
    }

    /**
     * Requer que o usuário tenha uma role específica.
     */
    public function requireRole($requiredRole) {
        if (!$this->isLoggedIn() || $_SESSION['user_role'] !== $requiredRole) {
            redirectWithMessage(SITE_URL . '/admin/login.php', 'Você não tem permissão para acessar esta página.', 'danger');
        }
    }

    /**
     * Requer que o usuário seja administrador.
     */
    public function requireAdmin() {
        $this->requireRole('admin');
    }

    /**
     * Retorna informações do usuário logado.
     */
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'] ?? null,
                'email' => $_SESSION['user_email'] ?? null,
                'name' => $_SESSION['user_name'] ?? null,
                'role' => $_SESSION['user_role'] ?? null,
            ];
        }
        return null;
    }

    /**
     * Realiza o logout do usuário.
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();
        session_start(); // Inicia uma nova sessão vazia
    }
}

