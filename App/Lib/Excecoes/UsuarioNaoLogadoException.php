<?php

namespace App\Lib\Excecoes;

use Exception;
/**
 * Description of UsuarioNaoLogado
 * Classe responsável por tratar esceções de usurio não logado
 * @author Lucas
 */
class UsuarioNaoLogadoException extends Exception{
   
   public function __construct() {
      parent::__construct("Usuário não está logado no sistema... <br>Por favor faça o Login!",401);
   }
}
