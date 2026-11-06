<?php

function duracionLegible(int $total): string
{
    $minutos = intdiv($total, 60);
    $segundos = $total % 60;

    $textoMinutos = ($minutos === 1) ? 'minuto' : 'minutos';
    $textoSegundos = ($segundos === 1) ? 'segundo' : 'segundos';

    return "$minutos $textoMinutos y $segundos $textoSegundos";
}