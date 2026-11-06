<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/acceso.php';
exigirAcceso();
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Jukebox · Inicio</title><link rel="stylesheet" href="/assets/app.css?v=4"></head>
<body>
<h1>Bienvenido/a, <?= e($_SESSION['alias'] ?? '') ?></h1>
<p>Has iniciado sesión correctamente.</p>
<p><a href="canciones.php">Administrar catálogo musical</a></p>
<p><a href="12-catalogo-json.php">Ver catálogo JSON</a></p>
<section id="jukebox-player">
    <h2>Reproductor Jukebox</h2>
<p id="estado-canciones">Cargando canciones...</p>
<ul id="lista-canciones"></ul></section>
<h3>Ahora seleccionada:</h3>

<p id="cancion-actual">Ninguna canción seleccionada</p>

<div class="controles">
    <button id="btn-anterior" type="button" disabled>
        ⏮ Anterior
    </button>

    <button id="btn-siguiente" type="button" disabled>
        Siguiente ⏭
    </button>
    <button id="btn-reproducir" type="button" disabled>
    ▶ Reproducir
</button>

<button id="btn-pausar" type="button" disabled>
    ⏸ Pausar
</button>
</div>
<form method="post" action="logout.php">
    <?= campoCsrf() ?>
    <button type="submit">Cerrar sesión</button>
</form>
<script src="./scripts/reproductor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.min.js"></script>
<script src="./scripts/reproductor.js"></script>
</body></html>
