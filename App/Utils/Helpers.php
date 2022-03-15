<?php

namespace App\Utils;

class Helpers
{
    static function randomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz1234567890';

        $string = "";
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    static function removeAccents(string $string)
    {
        return preg_replace(
            [
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/"
            ],
            explode(" ", "a A e E i I o O u U n N"),
            $string
        );
    }

    static function removeSpecialCharacteres(string $string) : string
    {
        $letters = ['!'. '@', '#', '$', '%', '¨', '&', '*', '(', ')', "´". '"', '[', ']', '{', '}', '~', '^', ',', '.', '<', '>', ';', ':', '?', '/'];

        foreach($letters as $letter) {
            $string = str_replace($letter, '', $string);
        }

        return $string;
    }

    static function slugfy(string $string):string
    {
        return str_replace(' ','-',str_replace('  ',' ',trim(strtolower(Helpers::removeSpecialCharacteres(Helpers::removeAccents($string))))));
    }

    static function verifyArrayFields(array $array, array $fieldNames, bool $acceptNull = true): bool
    {
        $hasFields = true;

        foreach ($fieldNames as $fieldName) {
            if ($acceptNull) {
                if (!array_key_exists($fieldName, $array)) {
                    $hasFields = false;
                    break;
                }
            } else {
                if (!isset($array[$fieldName])) {
                    $hasFields = false;
                    break;
                }
            }
        }

        return $hasFields;
    }

    static function contructQueryParams(array $queryParams): string
    {
        if (count($queryParams)) {
            $string = "?";
            $count = 0;

            foreach ($queryParams as $key => $value) {
                if ($count == 0) {
                    $string .= "$key=$value";
                } else {
                    $string .= "&$key=$value";
                }

                $count++;
            }

            return $string;
        }

        return '';
    }
}
