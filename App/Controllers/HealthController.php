<?php

namespace App\Controllers;

use App\Utils\Utils;

/**
 * Description of Health : Valida saúde da api e conexão com bacno de dados
 * @author Lucas
 */
class HealthController extends Controller 
{

   public function index() 
   {
      //echo $this->getResponse(200, "Success - Index");
      echo "<br>";
      
      $a = \App\Utils\Serializacao::encrypt('123');
      echo $a;
      echo "<br>";
      echo \App\Utils\Serializacao::decrypt($a);
      
      
   }
   
   public function Conect() 
   {
      $service = new \App\Services\HealthService();
      
      echo $service->ValidarConexao()
              ? $this->getResponse(200, "Connection true")
              : $this->getResponse(500, "Connection false");
   }

}
