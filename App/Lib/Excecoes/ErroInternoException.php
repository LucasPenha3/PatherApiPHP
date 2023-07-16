<?php

namespace App\Lib\Excecoes;

use App\Utils\Enums\EnumCode;
use Exception;

class ErroInternoException extends Exception
{

    public function __construct($message = "O servidor encontrou uma situação com a qual não sabe lidar.")
    {
        parent::__construct($message, EnumCode::E500);
    }
}