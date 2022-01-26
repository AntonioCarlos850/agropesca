<?php
require_once __DIR__ .'/components/pagina.php';
require_once __DIR__ .'/../components/input.php';

require_once __DIR__ .'/../backend/controller/User/User.php';

$usuarioController = new User();

if(isset($_POST["name"])){
    $usuarioController->updateUser([

    ]);
}else{
    $usuario_info = $usuarioController->selectUser([
        [
            "key"=>"id",
            "reference"=>":id",
            "value"=> $_SESSION["usuario"]["id"]
        ]
    ]);
    
    if(isset($usuario_info[0]["id"])){
        $_SESSION["usuario"] = $usuario_info[0];
        echo pagina([
            "title" => "Editar Usuário",
            "css" => "../css/painel/usuario.css",
            "content" => '
            <div class="col-form">
                <form method="POST">
                    <h1>Olá, '.$_SESSION["usuario"]["name"].'</h1>
                    '.Input(["type" => "text", "name" => "name", "placeholder"=> "Nome", "value" => $_SESSION["usuario"]["name"]]).'
                    <div class="button-div">
                        <button type="submit">Salvar</button>
                    </div>
                </form>
            </div>'
        ]);
    }else{
        header("Location: /");   
    }
}


