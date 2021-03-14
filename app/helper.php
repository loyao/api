<?php

use Illuminate\Support\Facades\Redis;

/**
 * 数据加密
 * @param $data
 * @return false|string
 */

function encrypt_data($data){
    $iv = substr(hash('sha256', $data), 0, 16);

    $ivlen = openssl_cipher_iv_length("aes-128-gcm");
    $iv = openssl_random_pseudo_bytes($ivlen);

    return openssl_encrypt($data,"aes-128-gcm",env("hash_key"),$options=OPENSSL_RAW_DATA,$data,$iv);
}

/**
 * 数据解密
 * @param $data
 * @return false|string
 */
function decrypt_data($data){
    $iv = substr(hash('sha256', $data), 0, 16);
    return openssl_decrypt($data,"AES-256-CBC",env("hash_key"),$options=OPENSSL_RAW_DATA,$data,$iv);
}
