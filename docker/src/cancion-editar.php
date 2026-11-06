<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/http.php';
require_once __DIR__ . '/lib/validacion-cancion.php';
require_once __DIR__ . '/lib/formulario-cancion.php';
exigirAcceso();

if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'], true)) responderMetodoNoPermitido(['GET', 'POST']);

$id = idPositivo($_GET['id'] ?? null);
if ($id === null) { http_response_code(400); exit('Identificador no válido.'); }

try { $songs = leerCanciones(); }
catch (Throwable $error) {
    error_log('Error al consultar canción: ' . get_class($error));
    http_response_code(500); exit('No se pudo consultar el catálogo.');
}

$current = null;
foreach ($songs as $song) {
    if (isset($song['id']) && $song['id'] === $id) { $current = $song; break; }
}
if ($current === null) { http_response_code(404); exit('Canción no encontrada.'); }

$valores = [
    'title' => (string) ($current['title'] ?? ''),
    'artist' => (string) ($current['artist'] ?? ''),
    'category' => (string) ($current['category'] ?? ''),
    'duration' => (string) ($current['duration'] ?? ''),
    'src' => (string) ($current['src'] ?? '')
];
$errores = [];
$errorGeneral = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValido()) {
        http_response_code(403); $errorGeneral = 'Token CSRF no válido.';
    } elseif (idPositivo($_POST['id'] ?? null) !== $id) {
        http_response_code(400); $errorGeneral = 'El identificador enviado no coincide.';
    } else {
        $v = validarDatosCancion($_POST);
        $valores = $v['valores'];
        $errores = $v['errores'];

        if ($errores === []) {
            try {
                $found = actualizarCanciones(function (array $songs) use ($id, $v): array {
                    foreach ($songs as $pos => $song) {
                        if (isset($song['id']) && $song['id'] === $id) {
                            $songs[$pos] = [
                                'id' => $id,
                                'title' => $v['datos']['title'],
                                'artist' => $v['datos']['artist'],
                                'category' => $v['datos']['category'],
                                'duration' => $v['datos']['duration'],
                                'src' => $v['datos']['src']
                            ];
                            return [$songs, true];
                        }
                    }
                    return [$songs, false];
                });

                if (!$found) {
                    http_response_code(404); $errorGeneral = 'La canción ya no existe.';
                } else {
                    header('Location: canciones.php', true, 303); exit;
                }
            } catch (Throwable $error) {
                error_log('Error al editar canción: ' . get_class($error));
                $errorGeneral = 'No se pudo guardar la modificación.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Editar canción</title><link rel="stylesheet" href="/assets/app.css?v=10"></head>
<body>
<h1>Editar canción #<?= e($id) ?></h1><p><a href="canciones.php">Volver</a></p>
<?php if ($errorGeneral !== null) { ?><p role="alert"><?= e($errorGeneral) ?></p><?php } ?>
<?php mostrarFormularioCancion($valores, $errores, 'Guardar cambios', 'cancion-editar.php?id=' . $id, $id); ?>
</body></html>
