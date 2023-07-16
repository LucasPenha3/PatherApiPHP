<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils;

class Response {

   private $message;
   private $code;

   public function __construct() {
      $this->message = '';
      $this->code = 0;
   }

   public function setStatus($code, $message) {
      $this->code = $code;
      $this->message = $message;
   }

   /**
    * Escreve o JSON e die
    */
   public function resolve() {
      header("Content-type: application/json; charset=utf-8");

      echo json_encode(array("code" => $this->code,
          "message" => $this->message), 1);
      
      die;
   }

}
