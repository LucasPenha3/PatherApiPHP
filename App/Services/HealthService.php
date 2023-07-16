<?php


namespace App\Services;

class HealthService {
   
   public function ValidarConexao() : bool
   {
      $dao = new \App\Models\DAO\ClientesDao();
      return $dao->validaConexao();
   }
   
}
