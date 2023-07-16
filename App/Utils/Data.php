<?php

namespace App\Utils;

/**
 * Description of Data 
 *
 * @author Lucas
 */
class Data {
   
   
   public static function setTimeZone() {
      date_default_timezone_set('America/Sao_Paulo');
   }
   
   /** @param Date $data Devolve data no estilo YYYY-mm-dd (mesmo se o parametro já vier correto */
   public static function getDataBD($data) {
      if (strripos($data, '/')) {
         $d = explode('/', $data);
         $dataRetorna = "{$d[2]}-{$d[1]}-{$d[0]}";
         return $dataRetorna;
      } else {
         return $data;
      }
   }

   /**
    * @param type $data : data ou data hora
    * @return string retorna a data ou data hora, dependendo do paramentro
    */
   public static function getDataHoraBrasil($data) {
      if($data == null || trim($data) == ""){
         return "";
      }
      
      $array = explode(" ", $data);
      $arrayData = explode("-", $array[0]);
      if(!empty($array[1])){
         $dataHora = $arrayData[2] . "/" . $arrayData[1] . "/" . $arrayData[0] . " " . $array[1];
      }else{
         $dataHora = $arrayData[2] . "/" . $arrayData[1] . "/" . $arrayData[0];
      }
      return $dataHora;
   }
   
   
   /** 
    * Retorna quantas horas minutos e segundos de determinado numero de segundos
    * @param int $segundos - tempo em segundos para ser convertido
    * @return String - Horas : Minutos : Seguntos
    */
   public static function getTime($paramSegundos){
      $horas = str_pad(floor($paramSegundos / 3600), 2, '0', STR_PAD_LEFT);
      $minutos = str_pad(floor(($paramSegundos - ($horas * 3600)) / 60), 2, '0', STR_PAD_LEFT);
      $segundos = str_pad(floor($paramSegundos % 60), 2, '0', STR_PAD_LEFT);
      return $horas . ":" . $minutos . ":" . $segundos;
   }
   
   public static function getDiferencaDatas($data1, $data2){
      if(trim($data1) == "" || $data1 == null || trim($data2) == "" || $data2 == null)
         return "";
     
      $segundosData1 = strtotime($data1);
      $segundosData2 = strtotime($data2);
      
      $segundosFinal = ($segundosData2 - $segundosData1);
      if($segundosFinal < 0)
         $segundosFinal *= -1;
      
      return self::getTime($segundosFinal);
   }
   
}
