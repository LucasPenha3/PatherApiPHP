<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils;

/**
 * Description of String
 *
 * @author Lucas
 */
class ValidaString {
   
   public static function desfazQuery(string $queryGet) : array {
      
      $retorno = [];
      if(strpos($queryGet, "&") === false || strpos($queryGet, "=") === false)
         return $retorno;
      
      foreach (explode("&", $queryGet) as $campo){
         $param = explode("=", $campo);
         $retorno[urldecode($param[0])] = urldecode($param[1]);
      }
      
      return $retorno;
   }
   
}
