<?php

namespace App\Services\Validacoes;

use App\Utils\Enums\EnumCode;

class RetornoValidacao 
{
   public bool $isValid;
   
   public $codigo = 200;
   public $propriedade;
   public $mensagem;
   
   public function __construct($propriedade, $mensagem) 
   {
      if($propriedade == "" && $mensagem == "")
      {
         $this->isValid = true;
      }   
      else
      {
         $this->isValid = false;
         $this->codigo = EnumCode::E400;
         $this->propriedade = $propriedade;
         $this->mensagem = $mensagem;
      }
      
      
   }
}
