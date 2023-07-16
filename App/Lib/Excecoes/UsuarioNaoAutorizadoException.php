<?php

namespace App\Lib\Excecoes;

class UsuarioNaoAutorizadoException extends \Exception
{

    public function __construct($message = "Seu usuário não está autorizado a acessar essa página!")
    {
        parent::__construct($message, 401);
    }
}