<?php

class AuthController
{
    private function csrf_token()
    {
        if (!isset($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['csrf'];
    }

    private function verify_csrf()
    {
        $token = isset($_POST['csrf']) ? $_POST['csrf'] : '';
        if (!$token || !isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
            http_response_code(400);
            echo "Invalid CSRF token.";
            exit;
        }
    }

    public function showRegister()
    {
        $csrf = $this->csrf_token();
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function showLogin()
    {
        $csrf = $this->csrf_token();
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function register()
    {
        $this->verify_csrf();

        $fullname = trim(isset($_POST['fullname']) ? $_POST['fullname'] : '');
        $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : 'Tenant';

        if ($fullname === '' || $email === '' || $password === '') {
            $error = "All fields are required.";
            $csrf = $this->csrf_token();
            require __DIR__ . '/../Views/auth/register.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
            $csrf = $this->csrf_token();
            require __DIR__ . '/../Views/auth/register.php';
            return;
        }

        if ($role !== 'Caretaker' && $role !== 'Tenant') {
            $role = 'Tenant';
        }

        $hash = password_hash($password, PASSWORD_DEFAULT); // recommended built-in hashing 

        $pdo = db();
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password_hash, role, dateoflogin) VALUES (?, ?, ?, ?, NULL)");

        try {
            $stmt->execute([$fullname, $email, $hash, $role]);
        } catch (PDOException $e) {
            // likely duplicate email
            $error = "Email is already registered.";
            $csrf = $this->csrf_token();
            require __DIR__ . '/../Views/auth/register.php';
            return;
        }

        header("Location: /login");
        exit;
    }

    public function login()
    {
        $this->verify_csrf();

        $email = trim(isset($_POST['email']) ? $_POST['email'] : '');
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($email === '' || $password === '') {
            $error = "Email and password are required.";
            $csrf = $this->csrf_token();
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $pdo = db();
        $stmt = $pdo->prepare("SELECT id, fullname, email, password_hash, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) { // recommended verify 
            $error = "Invalid credentials.";
            $csrf = $this->csrf_token();
            require __DIR__ . '/../Views/auth/login.php';
            return;
        }

        // session hardening: regenerate after login 
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];

        // update last login
        $pdo->prepare("UPDATE users SET dateoflogin = NOW() WHERE id = ?")->execute([$user['id']]);

        if ($user['role'] === 'Caretaker') {
            header("Location: /dashboard/caretaker");
            exit;
        }
        header("Location: /dashboard/tenant");
        exit;
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: /login");
        exit;
    }
}
