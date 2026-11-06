
<?php

// Duración de la canción en segundos
$duration = 185;

// Comprobar la duración
if ($duration === 0) {
    $message = 'Sin duración';

} elseif ($duration <= 240) {
    $message = 'Hasta cuatro minutos';

} else {
    $message = 'Más de cuatro minutos';
}

// Mostrar el resultado
echo $message;


echo '<hr>';

var_dump(0 === '0');


?>
