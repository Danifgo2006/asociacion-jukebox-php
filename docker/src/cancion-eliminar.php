<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/http.php';
exigirAcceso();

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) responderMetodoNoPermitido(['GET', 'POST']);

$id = idPositivo($_SERVER['REQUEST_METHOD'] === 'POST' ? ($_POST['id'] ?? null) : ($_GET['id'] ?? null));
if ($id === null) { http_response_code(400); exit('Identificador no válido.'); }

try { $songs = leerCanciones(); }
catch (Throwable $error) {
    error_log('Error al consultar canción para eliminar: ' . get_class($error));
    http_response_code(500); exit('No se pudo consultar el catálogo.');
}

$current = null;
foreach ($songs as $song) {
    if (isset($song['id']) && $song['id'] === $id) { $current = $song; break; }
}
if ($current === null) { http_response_code(404); exit('Canción no encontrada.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValido()) { http_response_code(403); exit('Token CSRF no válido.'); }

    try {
        $deleted = actualizarCanciones(function (array $songs) use ($id): array {
            foreach ($songs as $pos => $song) {
                if (isset($song['id']) && $song['id'] === $id) {
                    unset($songs[$pos]);
                    return [array_values($songs), true];
                }
            }
            return [$songs, false];
        });

        if (!$deleted) { http_response_code(404); exit('La canción ya no existe.'); }
        header('Location: canciones.php', true, 303); exit;
    } catch (Throwable $error) {
        error_log('Error al eliminar canción: ' . get_class($error));
        http_response_code(500); exit('No se pudo eliminar la canción.');
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Confirmar eliminación</title><link rel="stylesheet" href="/assets/app.css?v=10"></head>
<body>
<h1>Eliminar canción</h1>
<p>¿Seguro que quieres eliminar esta canción?</p>
<h2><?= e($current['title'] ?? '') ?></h2>
<p>Artista: <?= e($current['artist'] ?? '') ?></p>
<form method="post" action="cancion-eliminar.php">
    <?= campoCsrf() ?>
    <input type="hidden" name="id" value="<?= e($id) ?>">
    <button type="submit">Sí, eliminar canción</button>
</form>
<p><a href="canciones.php">Cancelar</a></p>
</body></html>
