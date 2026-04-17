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


function formatTanggalIndo($date)
{
    if (!$date || $date == '0000-00-00') return '';

    $bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $tanggal = explode('-', substr($date, 0, 10));
    return intval($tanggal[2]) . ' ' . $bulan[intval($tanggal[1]) - 1] . ' ' . $tanggal[0];
}



function terbilang($nilai)
{
    $nilai = abs($nilai);
    $angka = [
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas'
    ];

    if ($nilai < 12) {
        return ' ' . $angka[$nilai];
    } elseif ($nilai < 20) {
        return terbilang($nilai - 10) . ' belas';
    } elseif ($nilai < 100) {
        return terbilang(intval($nilai / 10)) . ' puluh' . terbilang($nilai % 10);
    } elseif ($nilai < 200) {
        return ' seratus' . terbilang($nilai - 100);
    } elseif ($nilai < 1000) {
        return terbilang(intval($nilai / 100)) . ' ratus' . terbilang($nilai % 100);
    } elseif ($nilai < 2000) {
        return ' seribu' . terbilang($nilai - 1000);
    } elseif ($nilai < 1000000) {
        return terbilang(intval($nilai / 1000)) . ' ribu' . terbilang($nilai % 1000);
    } elseif ($nilai < 1000000000) {
        return terbilang(intval($nilai / 1000000)) . ' juta' . terbilang($nilai % 1000000);
    } elseif ($nilai < 1000000000000) {
        return terbilang(intval($nilai / 1000000000)) . ' miliar' . terbilang($nilai % 1000000000);
    } elseif ($nilai < 1000000000000000) {
        return terbilang(intval($nilai / 1000000000000)) . ' triliun' . terbilang($nilai % 1000000000000);
    }

    return '';
}


function ceil_2($number){
    return ceil($number * 100) / 100;
}



function encrypt_text($plaintext)
{
    $key = 'staffora_secret_key_123'; 
    $cipher = "AES-256-CBC";

    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));

    $encrypted = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);

    return base64_encode($iv . $encrypted);
}


function decrypt_text($encryptedText)
{
    $key = 'staffora_secret_key_123'; 
    $cipher = "AES-256-CBC";

    $data = base64_decode($encryptedText);

    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $iv_length);
    $encrypted = substr($data, $iv_length);

    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}


