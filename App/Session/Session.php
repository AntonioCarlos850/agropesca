<?php

namespace App\Session;

class Session {
    protected static function init():void{
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }
}