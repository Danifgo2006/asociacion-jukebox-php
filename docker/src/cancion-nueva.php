<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/validacion-cancion.php';
require_once __DIR__ . '/lib/formulario-cancion.php';
exigirAcceso();

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) {
    header('Allow: GET, POST'); http_response_code(405); exit('Método no permitido.');
}

$valores = ['title'=>'', 'artist'=>'', 'category'=>'', 'duration'=>'', 'src'=>''];
$errores = [];
$errorGeneral = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValido()) {
        http_response_code(403); $errorGeneral = 'Token CSRF no válido.';
    } else {
        $v = validarDatosCancion($_POST);
        $valores = $v['valores'];
        $errores = $v['errores'];

        if ($errores === []) {
            try {
                actualizarCanciones(function (array $songs) use ($v): array {
                    $maxId = 0;
                    foreach ($songs as $song) {
                        if (isset($song['id']) && is_int($song['id'])) $maxId = max($maxId, $song['id']);
                    }
                    $newSong = [
                        'id' => $maxId + 1,
                        'title' => $v['datos']['title'],
                        'artist' => $v['datos']['artist'],
                        'category' => $v['datos']['category'],
                        'duration' => $v['datos']['duration'],
                        'src' => $v['datos']['src']
                    ];
                    $songs[] = $newSong;
                    return [$songs, $newSong['id']];
                });
                header('Location: canciones.php', true, 303); exit;
            } catch (Throwable $error) {
                error_log('Error al crear canción: ' . get_class($error));
                $errorGeneral = 'No se pudo guardar la canción.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Añadir canción</title><link rel="stylesheet" href="/assets/app.css?v=10"></head>
<body>
<h1>Añadir canción</h1><p><a href="canciones.php">Volver</a></p>
<?php if ($errorGeneral !== null) { ?><p role="alert"><?= e($errorGeneral) ?></p><?php } ?>
<?php mostrarFormularioCancion($valores, $errores, 'Guardar canción', 'cancion-nueva.php'); ?>
</body></html>
