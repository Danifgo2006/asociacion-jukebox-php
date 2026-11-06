<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/sesion.php';
iniciarSesionJukebox();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST'); http_response_code(405); exit('El cierre de sesión solo admite POST.');
}
if (!csrfValido()) {
    http_response_code(403); exit('Token CSRF no válido.');
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => false
    ]);
}
session_destroy();
header('Location: login.php', true, 303);
exit;
