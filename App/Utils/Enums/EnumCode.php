<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils\Enums;

/**
 * Description of EnumCodeError
 *
 * @author luksf
 */
class EnumCode {
   
   /**
    * Sucesso Porém pode haver erros
    */
   const E200 = 200; 
   
   /**
    * Sucesso
    */
   const E201 = 201; 
   
   /**
    * Solicitação Inválida
    */
   const E400 = 400; 
   
   /**
    * Não autorizado
    */
   const E401 = 401; 
   
   /**
    *`Página não encontrada
    */
   const E404 = 404; 
   
   /**
    * Erro de servidor
    */
   const E500 = 500;
   
   /**
    * Json Mal formatado = usado na API
    */
   const E600 = 600;
   
   /**
    * Token Inválido = usado na API
    */
   const E601 = 601;
}
