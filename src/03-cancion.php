
<?php

// Datos de la canción
$title = '<b>Demo</b>';
$artist = 'Grupo del aula';
$duration = 185;
$volume = 0.5;
$available = true;

// Constante de la aplicación
const APP_NAME = 'Jukebox de la asociación';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Primera canción</title>
</head>
<body>

    <h1><?php echo APP_NAME; ?></h1>

    
<h2>
    <?php
    echo htmlspecialchars(
        $title,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
    ?>
</h2>


    <p>
        
La canción
<?php
echo htmlspecialchars(
    $title,
    ENT_QUOTES | ENT_SUBSTITUTE,
    'UTF-8'
);
?>

        pertenece a <?php echo $artist; ?>.
    </p>

    <p>
        Duración: <?php echo $duration; ?> segundos.
    </p>

    <p>
        Volumen: <?php echo $volume; ?>
    </p>

    <p>
        Disponible:
        <?php echo $available ? 'Sí' : 'No'; ?>
    </p>

</body>
</html>
