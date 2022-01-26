<?php
function pagina(array $options = []):string{
    $links_css = [];
    if(isset($options["css"])){
        if(is_array($options["css"])){
            foreach($options["css"] as $link_css){
                $links_css[] = '<link rel="stylesheet" href="'.$link_css.'">';
            }
        }else{
            $links_css[] = '<link rel="stylesheet" href="'.$options["css"].'">';
        }
    }
    return '
    <!DOCTYPE html>
    <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/global.css">
            '.join("\n ", $links_css).'
            <title>'.(isset($options["title"]) ? $options["title"] : "Agropesca").'</title>
        </head>
        <body>
            '.(isset($options["content"]) ? $options["content"] : "").'
        </body>
    </html>
    ';
}