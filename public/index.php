<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

function redirect($to)
{
    header("Location: {$to}");
    exit;
}

function view($file, $data = [])
{
    extract($data);
    require __DIR__ . '/../app/Views/' . $file . '.php';
}

$auth = new AuthController();

if ($path === '/' || $path === '/index.php') {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] === 'Caretaker') redirect('/dashboard/caretaker');
        redirect('/dashboard/tenant');
    }
    redirect('/login');
}

if ($path === '/register' && $method === 'GET') {
    $auth->showRegister();
    exit;
}
if ($path === '/register' && $method === 'POST') {
    $auth->register();
    exit;
}

if ($path === '/login' && $method === 'GET') {
    $auth->showLogin();
    exit;
}
if ($path === '/login' && $method === 'POST') {
    $auth->login();
    exit;
}

if ($path === '/logout' && $method === 'POST') {
    $auth->logout();
    exit;
}

if ($path === '/dashboard/caretaker') {
    if (!isset($_SESSION['user_id'])) redirect('/login');
    if ($_SESSION['role'] !== 'Caretaker') redirect('/dashboard/tenant');
    view('dashboard/caretaker', ['name' => $_SESSION['fullname']]);
    exit;
}

if ($path === '/dashboard/tenant') {
    if (!isset($_SESSION['user_id'])) redirect('/login');
    echo "<h1>Tenant dashboard (placeholder)</h1><p>Hi, " . htmlspecialchars($_SESSION['fullname']) . "</p>";
    echo '<form method="POST" action="/logout"><button type="submit">Logout</button></form>';
    exit;
}

http_response_code(404);
echo "404 Not Found";
