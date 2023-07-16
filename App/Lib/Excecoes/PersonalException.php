<?php

namespace App\Lib\Excecoes;

use App\Utils\Enums\EnumCode;
use Exception;

class PersonalException extends Exception
{

    public function __construct($mensagem)
    {
        http_response_code(EnumCode::E500);
        parent::__construct($mensagem, EnumCode::E400);
    }

}
