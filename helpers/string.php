<?php

function random_string($length = 8) {    
    $chars = 'bcdfghjklmnprstvwxzaeiou';
    $result="";
    for ($p = 0; $p < $length; $p++) {
        $result .= ($p%2) ? $chars[mt_rand(19, 23)] : $chars[mt_rand(0, 18)];
    }
   
    return $result;
}