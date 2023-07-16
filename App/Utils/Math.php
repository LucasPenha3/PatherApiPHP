<?php


namespace App\Utils;

/**
 * Description of Math Retorna dados numericos
 * 
 * @author Lucas
 */
class Math {
   
    

   public static function Arredonda($valor, int $casasDecimais, $tipoArredondamento = PHP_ROUND_HALF_EVEN) {
   
       return round($valor, $casasDecimais, $tipoArredondamento);
   }
   
   public static function trunca($valor, int $casasDecimais, string $separadorDecimal = ".") {
      $tmp = explode($separadorDecimal, $valor);
      
      $retCasasDecimais = $tmp[1];
      if(strlen($tmp[1]) > $casasDecimais)
         $retCasasDecimais = substr($tmp[1], 0,$casasDecimais);
      
      $valorRetorno = $tmp[0].".".$retCasasDecimais;
      return $valorRetorno;
         
   }
   
   public static function formata($valor, int $casasDecimais) {
   
       return number_format($valor, $casasDecimais, ",", ".");
   }
   
}
