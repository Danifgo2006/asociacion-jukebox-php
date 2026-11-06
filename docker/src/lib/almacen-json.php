<?php
declare(strict_types=1);

const RUTA_CANCIONES_JSON = '/var/www/data/canciones.json';
const RUTA_USUARIOS_JSON = '/var/www/data/usuarios.json';

function leerColeccionJson(string $ruta): array
{
    $f = fopen($ruta, 'rb');
    if ($f === false) {
        throw new RuntimeException('No se pudo abrir el archivo JSON.');
    }
    $locked = false;
    try {
        if (!flock($f, LOCK_SH)) {
            throw new RuntimeException('No se pudo bloquear el archivo.');
        }
        $locked = true;
        $texto = stream_get_contents($f);
        if ($texto === false) {
            throw new RuntimeException('No se pudo leer el archivo JSON.');
        }
        $datos = json_decode($texto, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($datos) || !array_is_list($datos)) {
            throw new UnexpectedValueException('El JSON debe contener una lista.');
        }
        return $datos;
    } finally {
        if ($locked) {
            flock($f, LOCK_UN);
        }
        fclose($f);
    }
}

/*
 * El callback recibe la lista recién leída y devuelve:
 * [nuevaLista, resultado]. El bloqueo cubre lectura, modificación y escritura.
 */
function actualizarColeccionJson(string $ruta, callable $modificar): mixed
{
    $f = fopen($ruta, 'r+');
    if ($f === false) {
        throw new RuntimeException('No se pudo abrir el JSON para actualizar.');
    }
    $locked = false;
    try {
        if (!flock($f, LOCK_EX)) {
            throw new RuntimeException('No se pudo bloquear el JSON.');
        }
        $locked = true;
        $texto = stream_get_contents($f);
        if ($texto === false) {
            throw new RuntimeException('No se pudo leer el JSON.');
        }
        $lista = json_decode($texto, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($lista) || !array_is_list($lista)) {
            throw new UnexpectedValueException('El JSON debe contener una lista.');
        }

        $salida = $modificar($lista);
        if (!is_array($salida) || count($salida) !== 2 ||
            !is_array($salida[0]) || !array_is_list($salida[0])) {
            throw new UnexpectedValueException('Resultado de actualización incorrecto.');
        }
        [$nuevaLista, $resultado] = $salida;

        $json = json_encode(
            $nuevaLista,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
        );
        if (!rewind($f)) {
            throw new RuntimeException('No se pudo volver al inicio del archivo.');
        }
        $total = strlen($json);
        $escritos = 0;
        while ($escritos < $total) {
            $n = fwrite($f, substr($json, $escritos));
            if ($n === false || $n === 0) {
                throw new RuntimeException('Escritura incompleta del JSON.');
            }
            $escritos += $n;
        }
        if (!ftruncate($f, $escritos) || !fflush($f)) {
            throw new RuntimeException('No se pudo confirmar la escritura.');
        }
        return $resultado;
    } finally {
        if ($locked) {
            flock($f, LOCK_UN);
        }
        fclose($f);
    }
}

function leerCanciones(): array { return leerColeccionJson(RUTA_CANCIONES_JSON); }
function leerUsuarios(): array { return leerColeccionJson(RUTA_USUARIOS_JSON); }
function actualizarCanciones(callable $fn): mixed { return actualizarColeccionJson(RUTA_CANCIONES_JSON, $fn); }
function actualizarUsuarios(callable $fn): mixed { return actualizarColeccionJson(RUTA_USUARIOS_JSON, $fn); }
