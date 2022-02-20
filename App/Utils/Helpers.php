<?php

namespace App\Utils;

class Helpers {
    static function randomString($length = 10){
        $characters = 'abcdefghijklmnopqrstuvwxyz1234567890';

        $string = "";
        for($i = 0; $i < $length; $i++){
            $string .= $characters[rand(0, strlen($characters))];
        }

        return $string;
    }
}