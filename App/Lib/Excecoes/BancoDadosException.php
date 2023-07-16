<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Lib\Excecoes;

use Exception;
use PDOException;

/**
 * Description of BancoDados
 *
 * @author Lucas
 */
class BancoDadosException extends Exception{
   
   public function __construct(PDOException $e) {
      
      $mensagem = $e->getMessage();
      $sqlState   = $e->getCode();
      $codigo   = $e->errorInfo[1];
      
      $array = $this->getErro($sqlState, $codigo, $mensagem);
      
      if(count($array) == 2){
         parent::__construct ($array['message'], $array['code']);
      }else{
         parent::__construct ($mensagem, $codigo);
      }
      
   }
   
   private function getErro($sqlState, $codigo, $message) {
                  
      $array = array();
      switch ($codigo){
         case "1364":
            $campo = substr($message, strpos($message, "'") + 1);
            $campo = substr($campo, 0, strpos($campo, "'"));
            $campo = substr($campo,1);
            $mensagem = "O campo \"$campo\" não tem um valor padrão.";
            $array['message'] =  $mensagem;
            $array['code'] = 1364;
            break;
         case "1054":
            $campo = substr($message, strpos($message, "'") + 1);
            $campo = substr($campo, 0, strpos($campo, "'"));
            $campo = substr($campo, 1);
            $mensagem = "O campo \"$campo\" não foi encontrado no banco de dados.";
            $array['message'] =  $mensagem;
            $array['code'] = 1364;
            break;
         case "1292":
            $campo = substr($message, strpos($message, "'") + 1);
            $campo = substr($campo, 0, strpos($campo, "'"));
            $campo = substr($campo, 1);
            $mensagem = "Valor incorreto para a coluna \"$campo\".";
            $array['message'] =  $mensagem;
            $array['code'] = 1364;
            break;
         case "1064":
            $campo = substr($message, strpos($message, "'") + 1);
            $campo = substr($campo, 0, strpos($campo, "'"));
            $mensagem = "Erro de sintaxe próximo à \"$campo\".";
            $array['message'] =  $mensagem;
            $array['code'] = 1364;
            break;
      }
      
      return $array;
   }
}
