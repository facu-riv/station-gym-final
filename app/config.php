<?php
declare(strict_types=1);
session_start();

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$basePath = rtrim(str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), '/\\');
if ($basePath === '') { $basePath = '/'; }
define('BASE_URL', rtrim($scheme . '://' . $_SERVER['HTTP_HOST'] . $basePath, '/') . '/');

spl_autoload_register(function ($class) {
    $roots = [__DIR__.'/controllers', __DIR__.'/models', __DIR__.'/lib'];
    foreach ($roots as $root) {
        $file = $root . '/' . $class . '.php';
        if (is_file($file)) { require_once $file; return; }
    }
});

// DB gymdb
define('DB_DSN', getenv('DB_DSN') ?: 'mysql:host=127.0.0.1;dbname=gymdb;charset=utf8mb4');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// helpers
function redirect(string $path): void { header('Location: ' . BASE_URL . ltrim($path, '/')); exit; }
function is_logged_in(): bool { return !empty($_SESSION['user']); }
function require_login(): void { if (!is_logged_in()) redirect('login'); }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_check(string $token): bool { return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token); }
