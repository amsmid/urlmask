<?php // -*- encoding utf-8 -*-

class ShortHashConverter
{

    public static function convertStringToHash($str, $salt = 'hoge')
    {
        if(empty($str) === true) return null;
        $hashed_str = sha1($str . $salt);
        $crc32_str = CRC32($hashed_str);
        $packed_str = pack('H*', $crc32_str);
        $base64_str = base64_encode($packed_str);
        $trimed_str = str_replace('/', '_', rtrim($base64_str, '='));
        return $trimed_str; 
    }
}
