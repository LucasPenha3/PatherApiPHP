<?php

namespace App\Utils;

/**
 * Description of ValidacaoSenhas
 * Classe responsável por validar senhas de acesso
 * @author Lucas
 */
class ValidacaoSenhas {
   
   private static $chave1 = "Project-Pathern205797";
   private static $chave2 = "Pathern-Project#Lu@St.4d";
   
   public static function getSenhaMd5($senha){
      $stringSenha = self::$chave1.$senha.self::$chave2;
      return md5($stringSenha);
   }
}
