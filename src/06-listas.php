
<?php
// Primera lista: utilizar for
echo '<h1>Lista con for</h1>';

for ($i = 1; $i <= 4; $i++) {
    echo '<p>Posición ' . $i . '</p>';
}
?>

<?php
// Segunda lista: utilizar while
echo '<h1>Lista con while</h1>';

$contador = 1;

while ($contador <= 4) {
    echo '<p>Posición ' . $contador . '</p>';
    $contador++;
}
?>


<?php
// Array de canciones
$titulos = [
    'Sesión de prueba',
    'Canción de rock',
    'Música de aventura'
];

// Recorrer el array con foreach
echo '<h1>Lista de canciones</h1>';

foreach ($titulos as $indice => $titulo) {
    echo '<p>Canción ' . ($indice + 1) . ': ' .
        htmlspecialchars($titulo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') .
        '</p>';
}
?>
