<?php

// Cargamos las funciones y el catálogo
require_once __DIR__ . '/lib/canciones.php';

$songs = require __DIR__ . '/lib/catalogo-inicial.php';

// Categorías permitidas
$allowed = [
    'todas',
    'electronica',
    'rock',
    'bandas-sonoras'
];

// Recibimos la categoría desde la URL
$category = $_GET['category'] ?? 'todas';

// Validamos la categoría
if (
    !is_string($category) ||
    !in_array($category, $allowed, true)
) {
    http_response_code(400);
    $error = 'Categoría no válida';

} elseif ($category === 'todas') {

    $filteredSongs = $songs;

} else {

    // Filtramos las canciones
    $filteredSongs = array_filter(
        $songs,
        function ($song) use ($category) {
            return $song['category'] === $category;
        }
    );
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Filtro musical</title>
</head>

<body>

    <h1>Catálogo musical</h1>

    <?php if (isset($error)) { ?>

        <p>
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </p>

    <?php } elseif (empty($filteredSongs)) { ?>

        <p>No hay canciones en esta categoría.</p>

    <?php } else { ?>

        <?php foreach ($filteredSongs as $song) { ?>

            <div>

                <h2>
                    <?= htmlspecialchars(
                        $song['title'],
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    ) ?>
                </h2>

                <p>
                    Artista:
                    <?= htmlspecialchars(
                        $song['artist'],
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    ) ?>
                </p>

                <p>
                    Duración:
                    <?= duracionLegible($song['duration']) ?>
                </p>

                <hr>

            </div>

        <?php } ?>

    <?php } ?>

</body>
</html>