<?php
function criar_navbar(array $options = []):string{
    return '
    <header>
        <nav>
            <a href="/"><img src="https://catracalivre.com.br/wp-content/uploads/2019/03/download-1-1.jpg" class="brand"></a>
            <ul>
                <li>Home</li>
                <li>Minha Conta</li>
            </ul>
        </nav>
    </header>
    ';
}