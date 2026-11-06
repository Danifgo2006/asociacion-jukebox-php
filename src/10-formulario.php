<?php

require_once __DIR__ . '/lib/canciones.php';

// Categorías permitidas
$allowedCategories = [
    'electronica',
    'rock',
    'bandas-sonoras'
];

// Audios permitidos
$allowedAudios = [
    'audio/demo-01.mp3',
    'audio/demo-02.mp3'
];

// Valores iniciales
$title = '';
$artist = '';
$category = '';
$durationInput = '';
$duration = null;
$src = '';

$errors = [];
$preview = false;

// Comprobamos si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recibimos el título
    $rawTitle = $_POST['title'] ?? '';

    if (!is_string($rawTitle)) {
        $errors['title'] = 'El título no es válido.';
    } else {
        $title = trim($rawTitle);

        if ($title === '') {
            $errors['title'] = 'El título es obligatorio.';
        }
    }

    // Recibimos el artista
    $rawArtist = $_POST['artist'] ?? '';

    if (!is_string($rawArtist)) {
        $errors['artist'] = 'El artista no es válido.';
    } else {
        $artist = trim($rawArtist);

        if ($artist === '') {
            $errors['artist'] = 'El artista es obligatorio.';
        }
    }

    // Recibimos la categoría
    $rawCategory = $_POST['category'] ?? '';

    if (
        !is_string($rawCategory) ||
        !in_array($rawCategory, $allowedCategories, true)
    ) {
        $errors['category'] = 'Selecciona una categoría válida.';
    } else {
        $category = $rawCategory;
    }

    // Recibimos la duración
    $rawDuration = $_POST['duration'] ?? '';

    if (!is_string($rawDuration)) {
        $errors['duration'] = 'La duración debe ser un número.';
    } else {
        $durationInput = trim($rawDuration);

        $validatedDuration = filter_var(
            $durationInput,
            FILTER_VALIDATE_INT
        );

        if (
            $validatedDuration === false ||
            $validatedDuration < 1 ||
            $validatedDuration > 1800
        ) {
            $errors['duration'] =
                'La duración debe estar entre 1 y 1800 segundos.';
        } else {
            $duration = $validatedDuration;
        }
    }

    // Recibimos el archivo de audio
    $rawSrc = $_POST['src'] ?? '';

    if (
        !is_string($rawSrc) ||
        !in_array($rawSrc, $allowedAudios, true)
    ) {
        $errors['src'] = 'Selecciona un audio válido.';
    } else {
        $src = $rawSrc;
    }

    // Si no hay errores, preparamos la vista previa
    if (empty($errors)) {
        $preview = true;
    } else {
        http_response_code(400);
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario musical</title>
</head>

<body>

    <h1>Añadir canción</h1>

    <p>Introduce los datos de la canción para comprobarlos.</p>

    <form method="post">

        <div>
            <label for="title">Título:</label>

            <input
                type="text"
                id="title"
                name="title"
                value="<?= htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                required
            >

            <?php if (isset($errors['title'])) { ?>
                <p><?= htmlspecialchars($errors['title'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php } ?>
        </div>

        <br>

        <div>
            <label for="artist">Artista:</label>

            <input
                type="text"
                id="artist"
                name="artist"
                value="<?= htmlspecialchars($artist, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                required
            >

            <?php if (isset($errors['artist'])) { ?>
                <p><?= htmlspecialchars($errors['artist'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php } ?>
        </div>

        <br>

        <div>
            <label for="category">Categoría:</label>

            <select id="category" name="category" required>

                <option value="">Selecciona una categoría</option>

                <option value="electronica"
                    <?= $category === 'electronica' ? 'selected' : '' ?>>
                    Electrónica
                </option>

                <option value="rock"
                    <?= $category === 'rock' ? 'selected' : '' ?>>
                    Rock
                </option>

                <option value="bandas-sonoras"
                    <?= $category === 'bandas-sonoras' ? 'selected' : '' ?>>
                    Bandas sonoras
                </option>

            </select>

            <?php if (isset($errors['category'])) { ?>
                <p><?= htmlspecialchars($errors['category'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php } ?>
        </div>

        <br>

        <div>
            <label for="duration">Duración (segundos):</label>

            <input
                type="number"
                id="duration"
                name="duration"
                min="1"
                max="1800"
                value="<?= htmlspecialchars($durationInput, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                required
            >

            <?php if (isset($errors['duration'])) { ?>
                <p><?= htmlspecialchars($errors['duration'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php } ?>
        </div>

        <br>

        <div>
            <label for="src">Archivo de audio:</label>

            <select id="src" name="src" required>

                <option value="">Selecciona un audio</option>

                <option value="audio/demo-01.mp3"
                    <?= $src === 'audio/demo-01.mp3' ? 'selected' : '' ?>>
                    Demo 01
                </option>

                <option value="audio/demo-02.mp3"
                    <?= $src === 'audio/demo-02.mp3' ? 'selected' : '' ?>>
                    Demo 02
                </option>

            </select>

            <?php if (isset($errors['src'])) { ?>
                <p><?= htmlspecialchars($errors['src'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php } ?>
        </div>

        <br>

        <button type="submit">Comprobar canción</button>

    </form>

    <?php if ($preview) { ?>

        <hr>

        <h2>Vista previa de la canción</h2>

        <p><strong>Título:</strong>
            <?= htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </p>

        <p><strong>Artista:</strong>
            <?= htmlspecialchars($artist, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </p>

        <p><strong>Categoría:</strong>
            <?= htmlspecialchars($category, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </p>

        <p><strong>Duración:</strong>
            <?= htmlspecialchars(duracionLegible($duration), ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p><strong>Audio:</strong>
            <?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>
        </p>

        <h3>Vista previa, canción no guardada.</h3>

    <?php } ?>

</body>
</html>