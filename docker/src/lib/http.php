<?php
declare(strict_types=1);

function idPositivo(mixed $valor): ?int
{
    if (!is_string($valor) && !is_int($valor)) return null;
    $id = filter_var($valor, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}

function responderMetodoNoPermitido(array $permitidos): never
{
    header('Allow: ' . implode(', ', $permitidos));
    http_response_code(405);
    exit('Método no permitido.');
}
