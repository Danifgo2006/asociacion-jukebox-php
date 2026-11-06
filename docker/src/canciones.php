<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/canciones.php';
exigirAcceso();

try {
    $songs = leerCanciones();
} catch (Throwable $error) {
    error_log('Error al leer catálogo: ' . get_class($error));
    http_response_code(500); exit('No se pudo cargar el catálogo.');
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Catálogo musical</title><link rel="stylesheet" href="/assets/app.css?v=10"></head>
<body>
<h1>Catálogo musical</h1>
<p><a href="home.php">Inicio</a> | <a href="cancion-nueva.php">Añadir canción</a></p>
<?php if ($songs === []) { ?>
    <p>No hay canciones todavía.</p>
<?php } else { foreach ($songs as $song) { ?>
    <article>
        <h2><?= e($song['title'] ?? '') ?></h2>
        <p>Artista: <?= e($song['artist'] ?? '') ?></p>
        <p>Categoría: <?= e($song['category'] ?? '') ?></p>
        <p>Duración: <?= e(duracionLegible((int) ($song['duration'] ?? 0))) ?></p>
        <p>ID: <?= e($song['id'] ?? '') ?></p>
        <a href="cancion-editar.php?id=<?= e($song['id'] ?? '') ?>">Editar</a> |
        <a href="cancion-eliminar.php?id=<?= e($song['id'] ?? '') ?>">Eliminar</a>
        <hr>
    </article>
<?php }} ?>
</body></html>
