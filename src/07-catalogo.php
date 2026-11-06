<?php

require_once __DIR__ . '/lib/canciones.php';

$songs = require __DIR__ . '/lib/catalogo-inicial.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo musical</title>
</head>

<body>

    <h1>Catálogo musical</h1>

    <?php if (empty($songs)) { ?>

        <p>Todavía no hay canciones</p>

    <?php } else { ?>

        <?php foreach ($songs as $song) { ?>

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
                    <?= htmlspecialchars(
                        duracionLegible($song['duration']),
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    ) ?>
                </p>

                <hr>

            </div>

        <?php } ?>

    <?php } ?>

</body>
</html>