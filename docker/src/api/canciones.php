<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/sesion.php';
require_once __DIR__ . '/../lib/almacen-json.php';

iniciarSesionJukebox();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

if (!isset($_SESSION['usuario_id']) || !is_int($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Autenticación requerida.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $songs = leerCanciones();
    http_response_code(200);
    echo json_encode($songs, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $error) {
    error_log('Error en API de canciones: ' . get_class($error));
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo cargar el catálogo.'], JSON_UNESCAPED_UNICODE);
}
