<?php
require_once __DIR__ . '/backend/controller/User/User.php';

require_once __DIR__ . '/components/pagina.php';
require_once __DIR__ . '/components/input.php';

if(isset($_POST["email"]) && isset($_POST["password"])){
    $usuarioController = new User();

    $usuario_info = $usuarioController->tryLogin($_POST["email"], $_POST["password"]);

    if(isset($usuario_info["id"])){
        $_SESSION["usuario"] = $usuario_info;
    }
}

echo Pagina([
    "title" => "Login",
    "css" => "css/login.css",
    "content" => '
        <div class="col-image"></div>
        <div class="col-form">
            <form method="POST">
                <h1>Login</h1>
                '.Input(["type" => "text", "name" => "email", "placeholder"=> "Email"]).'
                '.Input(["type" => "password", "name" => "password", "placeholder"=> "Senha"]).'
                <div class="cadastro-div">
                    <a href="/cadastro.php">Cadastrar</a>
                </div>
                <div class="button-div">
                    <button type="submit">Entrar</button>
                </div>
            </form>
        </div>
    '
]);
?>
