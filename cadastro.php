<?php
require_once __DIR__ . '/backend/controller/User/User.php';

require_once __DIR__ . '/components/pagina.php';
require_once __DIR__ . '/components/input.php';

if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["name"])){
    $usuarioController = new User();

    $usuario_info_por_email = $usuarioController->selectUser([
        [
            "key"=>"email",
            "reference"=>":EMAIL",
            "value"=> $_POST["email"]
        ]
    ]);

    if(!isset($usuario_info_por_email[0]["id"])){
        $usuario_info = $usuarioController->insertUser([
            "email" => $_POST["email"], 
            "password" => $_POST["password"],
            "name" => $_POST["name"]
        ]);
    
        if(isset($usuario_info["id"])){
            $_SESSION["usuario"] = $usuario_info;
            header("Location: localhost/");
        }else{
            echo Pagina([
                "title" => "Cadastro",
                "css" => "css/cadastro.css",
                "content" => '
                    <div class="col-form">
                        <form method="POST">
                            <h1>Bem Vindo(a)</h1>
                            '.Input(["type" => "text", "name" => "name", "placeholder"=> "Nome", "value" => $_POST["name"]]).'
                            '.Input(["type" => "text", "name" => "email", "placeholder"=> "email", "value" => $_POST["email"]]).'
                            '.Input(["type" => "password", "name" => "password", "placeholder"=> "Senha"]).'
                            <div class="login-div">
                                <span>Já possui uma conta? <a href="/login.php">Login</a></span>
                            </div>
                            <div class="button-div">
                                <button type="submit">Entrar</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-image"></div>
                '
            ]);
        }
    }else{
        echo Pagina([
            "title" => "Cadastro",
            "css" => "css/cadastro.css",
            "content" => '
                <div class="col-form">
                    <form method="POST">
                        <h1>Bem Vindo(a)</h1>
                        '.Input(["type" => "text", "name" => "name", "placeholder"=> "Nome", "value" => $_POST["name"]]).'
                        '.Input(["type" => "text", "name" => "email", "placeholder"=> "email", "value" => $_POST["email"], "class" => "invalid"]).'
                        <span class="invalid">Email já cadastrado</span>
                        '.Input(["type" => "password", "name" => "password", "placeholder"=> "Senha"]).'
                        <div class="login-div">
                            <span>Já possui uma conta? <a href="/login.php">Login</a></span>
                        </div>
                        <div class="button-div">
                            <button type="submit">Entrar</button>
                        </div>
                    </form>
                </div>
                <div class="col-image"></div>
            '
        ]);
    }
}else{
    echo Pagina([
        "title" => "Cadastro",
        "css" => "css/cadastro.css",
        "content" => '
            <div class="col-form">
                <form method="POST">
                    <h1>Bem Vindo(a)</h1>
                    '.Input(["type" => "text", "name" => "name", "placeholder"=> "Nome"]).'
                    '.Input(["type" => "text", "name" => "email", "placeholder"=> "email"]).'
                    '.Input(["type" => "password", "name" => "password", "placeholder"=> "Senha"]).'
                    <div class="login-div">
                        <span>Já possui uma conta? <a href="/login.php">Login</a></span>
                    </div>
                    <div class="button-div">
                        <button type="submit">Entrar</button>
                    </div>
                </form>
            </div>
            <div class="col-image"></div>
        '
    ]);
}
