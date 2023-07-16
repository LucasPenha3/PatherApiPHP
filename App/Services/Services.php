<?php


namespace App\Services;

use App\Services\Validacoes\RetornoValidacao;
use App\Utils\Response;

/**
 * Description of Services : Validações em formulários e padrões para cada serviço
 * @author Lucas
 */
abstract class Services {
   
   
  protected function GetResponse($code, $message)
  {
      http_response_code($code);
      $response = new Response();
      $response->setStatus($code, $message);
      $response->resolve();
  }
   
  protected function GetResponseValidacao(RetornoValidacao $validacao)
  {
     $json = json_encode([
         "propriedade"=>$validacao->propriedade,
         "mensagem"=>$validacao->mensagem
     ]);
     
     $this->GetResponse($validacao->codigo, $json);
  }
}
