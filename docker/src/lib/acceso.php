<?php
declare(strict_types=1);
require_once __DIR__ . '/sesion.php';

function exigirAcceso(): void
{
    iniciarSesionJukebox();
    if (!isset($_SESSION['usuario_id']) || !is_int($_SESSION['usuario_id'])) {
        header('Location: login.php', true, 303);
        exit;
    }
}
