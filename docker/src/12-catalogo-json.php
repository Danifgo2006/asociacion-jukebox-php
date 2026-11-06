<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/canciones.php';
exigirAcceso();

try {
    $songs = leerCanciones();
} catch (Throwable $error) {
    error_log('Error al leer catálogo JSON: ' . get_class($error));
    http_response_code(500);
    exit('No se pudo cargar el catálogo.');
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Catálogo JSON</title><link rel="stylesheet" href="/assets/app.css?v=10"></head>
<body>
<h1>Catálogo musical desde JSON</h1>
<p><a href="home.php">Volver al inicio</a></p>
<?php if ($songs === []) { ?>
    <p>Todavía no hay canciones.</p>
<?php } else { foreach ($songs as $song) { ?>
    <article>
        <h2><?= e($song['title'] ?? '') ?></h2>
        <p>Artista: <?= e($song['artist'] ?? '') ?></p>
        <p>Categoría: <?= e($song['category'] ?? '') ?></p>
        <p>Duración: <?= e(duracionLegible((int) ($song['duration'] ?? 0))) ?></p>
        <hr>
    </article>
<?php }} ?>
</body></html>
