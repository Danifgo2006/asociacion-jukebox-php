
<?php

// Duración total de la canción en segundos
$duration = 185;

// Calcular los minutos completos
$minutes = intdiv($duration, 60);

// Calcular los segundos restantes
$seconds = $duration % 60;

// Mostrar el resultado
echo "La canción dura $minutes minutos y $seconds segundos.";

?>
