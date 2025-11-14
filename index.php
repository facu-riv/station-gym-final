<?php
declare(strict_types=1);
require_once __DIR__ . '/app/config.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir  = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$path = '/' . ltrim(substr($requestUri, strlen($scriptDir)), '/');
if ($path === '//') $path = '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$auth = new AuthController();
$admin = new AdminController();
$public = new PublicController();

// Public
if ($path === '/' || $path === '/home') { $public->home(); exit; }
if ($path === '/categorias') { $public->categorias(); exit; }
if (preg_match('#^/categoria/(\d+)$#', $path, $m)) { $public->actividadesPorCategoria((int)$m[1]); exit; }
if (preg_match('#^/actividad/(\d+)$#', $path, $m)) { $public->actividadDetalle((int)$m[1]); exit; }

// Auth
if ($path === '/login') {
    if ($method === 'POST') {
        $action = $_POST['action'] ?? '';
        if ($action === 'signin') { $auth->signin(); }
        elseif ($action === 'signup') { $auth->signup(); }
        else { $auth->showLogin(); }
    } else { $auth->showLogin(); }
    exit;
}
if ($path === '/logout') { $auth->logout(); exit; }

// Admin (ABM)
if ($path === '/admin') { require_login(); $admin->index(); exit; }

// Categorías CRUD
if ($path === '/categoria/create' && $method === 'POST') { require_login(); $admin->createCategoria(); exit; }
if ($path === '/categoria/delete' && $method === 'POST') { require_login(); $admin->deleteCategoria(); exit; }
if ($path === '/categoria/edit' && $method === 'POST') { require_login(); $admin->editCategoria(); exit; }

// Actividades CRUD
if ($path === '/actividad/create' && $method === 'POST') { require_login(); $admin->createActividad(); exit; }
if ($path === '/actividad/delete' && $method === 'POST') { require_login(); $admin->deleteActividad(); exit; }
if ($path === '/actividad/edit' && $method === 'POST') { require_login(); $admin->editActividad(); exit; }

http_response_code(404);
echo "<h1>404</h1><p>Ruta no encontrada: " . htmlspecialchars($path) . "</p>";
