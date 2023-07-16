<?php

namespace App\Controllers;

use App\App;
use App\Utils\Enums\EnumCode;
use App\Utils\Response;



abstract class Controller {

   protected $app;
   private $viewVar;

   public function __construct(App $app) 
   {
      
   }
   
   /**
    * Instancia a classe response e retorna o Json referente a código e mensagem
    * @param int $code : codigo do erro - Passar enumCode
    * @param string $message : Mensagem referente ao código
    */
   public function getResponse($code, $message)
   {
      http_response_code($code);
      $response = new Response();
      $response->setStatus($code, $message);
      $response->resolve();
   }

   public function getResponsePersonalizada($arrayResposta, $code = 200)
   {   
      header("Content-type: application/json; charset=utf-8");
      http_response_code($code);
      echo json_encode($arrayResposta, 1);
      
   }
   
   /**
    * Pega os dados enviados via raw (json, xml, etc)
    * @return string : dados contidos no raw
    */
   public function getRawData() {
      
      $rawData = utf8_encode(file_get_contents("php://input"));
      
      if(trim($rawData) == "")
         $this->getResponse(EnumCode::E404,"Request Post vazio");
      
      return $rawData;
   }
}
