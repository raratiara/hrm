<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function number_id_trim($n)
{
    if ($n === null || $n === '') {
        return '0';
    }

    $n = (float) $n;

    if (floor($n) == $n) {
        return number_format($n, 0, ',', '.');
    }
    return number_format($n, 2, ',', '.');
}




function normalize_text($str){
    return strtolower(trim($str ?? ''));
}

