<?php

namespace App\Utils;

class View
{
    /**
     * Método responsável por retornar o conteúdo de uma view
     */
    private static function getContentView(string $view): string {
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return is_file($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por retornar o conteúdo de uma view
     */
    public static function render(string $view, array $params = []): string
    {
        $contentView =  self::getContentView($view);

        $keys = array_map(function ($item) {
            return '{{'.$item.'}}';
        }, array_keys($params));

        return str_replace($keys, array_values($params), $contentView);
    }
}
