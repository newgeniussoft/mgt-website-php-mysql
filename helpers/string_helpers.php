<?php

function str_slug($string, $separator = '-') {
    $string = preg_replace('/[^A-Za-z0-9-]+/', $separator, strtolower($string));
    return trim($string, $separator);
}

function str_limit($string, $limit = 100, $end = '...') {
    if (strlen($string) <= $limit) {
        return $string;
    }
    return substr($string, 0, $limit) . $end;
}

function encrypt($value) {
    $key = config('app.key');
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($value, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($value) {
    $key = config('app.key');
    list($encrypted, $iv) = explode('::', base64_decode($value), 2);
    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
}