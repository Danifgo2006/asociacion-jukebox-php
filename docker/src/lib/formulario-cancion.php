<?php
declare(strict_types=1);

function mostrarFormularioCancion(array $v, array $errores, string $boton, string $accion, ?int $id = null): void
{
    $categorias = ['electronica' => 'Electrónica', 'rock' => 'Rock', 'bandas-sonoras' => 'Bandas sonoras'];
    
$audios = [
    'audio/beat-it.mp3' => 'Beat It',
    'audio/come-as-you-are.mp3' => 'Come As You Are',
    'audio/devuelveme-a-mi-chica.mp3' => 'Devuélveme a mi chica',
    'audio/die-versteinerung.mp3' => 'Die Versteinerung',
    'audio/marta-tiene-un-marcapasos.mp3' => 'Marta tiene un marcapasos',
    'audio/mozart-symphony-40.mp3' => 'Sinfonía n.º 40 de Mozart',
    'audio/smells-like-teen-spirit.mp3' => 'Smells Like Teen Spirit',
    'audio/thriller.mp3' => 'Thriller'
];

    ?>
    <form method="post" action="<?= e($accion) ?>">
        <?= campoCsrf() ?>
        <?php if ($id !== null) { ?><input type="hidden" name="id" value="<?= e($id) ?>"><?php } ?>

        <p><label for="title">Título:</label><br>
        <input id="title" name="title" maxlength="80" required value="<?= e($v['title'] ?? '') ?>">
        <?php if (isset($errores['title'])) { ?><br><strong><?= e($errores['title']) ?></strong><?php } ?></p>

        <p><label for="artist">Artista:</label><br>
        <input id="artist" name="artist" maxlength="80" required value="<?= e($v['artist'] ?? '') ?>">
        <?php if (isset($errores['artist'])) { ?><br><strong><?= e($errores['artist']) ?></strong><?php } ?></p>

        <p><label for="category">Categoría:</label><br>
        <select id="category" name="category" required>
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categorias as $key => $label) { ?>
                <option value="<?= e($key) ?>" <?= ($v['category'] ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php } ?>
        </select>
        <?php if (isset($errores['category'])) { ?><br><strong><?= e($errores['category']) ?></strong><?php } ?></p>

        <p><label for="duration">Duración (segundos):</label><br>
        <input type="number" id="duration" name="duration" min="1" max="1800" required value="<?= e($v['duration'] ?? '') ?>">
        <?php if (isset($errores['duration'])) { ?><br><strong><?= e($errores['duration']) ?></strong><?php } ?></p>

        <p><label for="src">Archivo de audio:</label><br>
        <select id="src" name="src" required>
            <option value="">Selecciona un audio</option>
            <?php foreach ($audios as $key => $label) { ?>
                <option value="<?= e($key) ?>" <?= ($v['src'] ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php } ?>
        </select>
        <?php if (isset($errores['src'])) { ?><br><strong><?= e($errores['src']) ?></strong><?php } ?></p>

        <button type="submit"><?= e($boton) ?></button>
    </form>
    <?php
}
