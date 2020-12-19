<?php

namespace Kraken7\helper;

class Helper
{
    const REGEX = '/^(?=.*[0-9])(?=.*[!~@#$%^&{}:<>\+\?\[\]\(\)\.\"_\-\=*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!~@#$%^&><{}:\+\?\[\]\(\)\.\"_\-\=*]{4,}$/';

    public static function debug($arr, $die = false)
    {
        echo '<pre>' . print_r($arr, true) . '</pre>';
        if ($die) die;
    }

    public static function redirect($http = false)
    {
        if ($http) $redirect = $http;
        else $redirect = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $redirect");
        exit;
    }

    public static function h($data)
    {
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES);
        }
        if (is_array($data)) {
            foreach ($data as &$v) {
                $v = self::h($v);
            }
        }
        return $data;
    }

    public static function generate_password($count)
    {
        try {
            if ($count < 4) throw new \Exception("Пароль не может быть меньше 4 символов!");

            $value = round($count / 4);
            $value_end = $count - ($value * 3);
            $pass = '';
            $str[] = "!@#$^%\"&*()_-+={}<>?:[].~";
            $str[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
            $str[] = "abcdefghjkmnpqrstuvwxyz";
            $str[] = "0123456789";

            while (preg_match(self::REGEX, $pass) == 0 || preg_match(self::REGEX, $pass) == false) {
                for ($j = 0; $j < 4; $j++) {
                    $val = array_rand($str, 1);
                    if ($j == 3) {
                        for ($i = 0; $i < $value_end; $i++) {
                            $index = rand(0, strlen($str[$val]) - 1);
                            $pass .= $str[$val][$index];
                        }
                    } else {
                        for ($i = 0; $i < $value; $i++) {
                            $index = rand(0, strlen($str[$val]) - 1);
                            $pass .= $str[$val][$index];
                        }
                    }
                    unset($str[$val]);
                }
            }
            return $pass;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public static function get_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}