<?php


namespace App\Utils;



/**
 * Description of Validacao
 * Responsável por validar dados de entrada
 * @author Lucas
 */
class Validacao {
      
   public static function verificaCampoArray(array $array, string $nomeCampo, $valor = false){
      if(isset($array[$nomeCampo])){
         if($valor){
            if($array[$nomeCampo] == $valor)
               return $array[$nomeCampo];
            else
               return false;
         }else{
            return $array[$nomeCampo];
         }
      }
      else
         return false;
         
   }
   
   /**
    * @param type $variavel : variavel a ser filtrada
    * @param type $filtro : Filtro a ser aplicado
    * @return string : string validada ou false se validação falhar
    */
   public static function getFilterVariavel($variavel, $filtro = FILTER_DEFAULT) : string{
      $retorno = filter_var($variavel, $filtro);
      return $retorno;
   }
   
   /**
    * @param array $variavel : array a ser filtrado
    * @param type $filtro : Filtro a ser aplicado
    * @return array : array validada ou false se validação falhar
    */
   public static function getFilterArray(array $variavel) {
      $retorno = filter_var_array($variavel, FILTER_DEFAULT);
      return $retorno;
   }
   
   /**
    * @param array $postGet : Array de post ou get já passado pelo métdodo getFilterRequisicao
    * @return array : Array com todos os campos filtrados com FILTER_DEFAULT
    */
   public static function getFilterVariaveisRequisicao($postGet) : array{
      
      try {
         $arrayFiltrado  =array();
         foreach ($postGet as $key => $value) {
            $arrayFiltrado[$key] = filter_var($value, FILTER_DEFAULT);
         }
         return $arrayFiltrado;
      } catch (Exception $ex) {
         return $ex;
      }
      
   }
   
   /**
    * @param type INPUT_POST ou INPUT_GET
    * @param type $typeFilter - tipo de filtro
    * @return type retorna o array da requisição filtrado ou false caso não dê para filtrar
    */
   public static function getFilterRequisicao($typeInput, $typeFilter = FILTER_DEFAULT) {
      
      $var = filter_input_array($typeInput, $typeFilter);
      return $var;
   }
   
   
   /**
    * @param string $nomeInput : nome do input
    * @param type $typeFilter 
    * @return Mixed : retorna o valor do nome do input ou false caso ocorra erro nos filtros 
    */
   public static function inputGet($nomeInput, $typeFilter = FILTER_DEFAULT) {
      $get = self::getFilterRequisicao(INPUT_GET);
            
      return (is_array($get["{$nomeInput}"])) ?
                  self::getFilterArray ($get["{$nomeInput}"]) :
                  self::getFilterVariavel($get["{$nomeInput}"],$typeFilter); 
      
   }
   
   /**
    * @param string $nomeInput : nome do input
    * @param type $typeFilter 
    * @return Mixed : retorna o valor do nome do input ou false caso ocorra erro nos filtros 
    */
   public static function inputPost($nomeInput, $typeFilter = FILTER_DEFAULT) {
      $post = self::getFilterRequisicao(INPUT_POST);
            
      return (is_array($post["{$nomeInput}"])) ?
                  self::getFilterArray ($post["{$nomeInput}"]) :
                  self::getFilterVariavel($post["{$nomeInput}"],$typeFilter); 
      
   }
   
   public static function isJson($json) {
      
      if(is_object($json) || is_array($json) || is_numeric($json))
         return false;
      
      if(is_string($json))
         $result = json_decode($json);
      
      
      if(json_last_error() === JSON_ERROR_NONE && $result != null)
         return true;
      else{
         return false;
      }
      
   }
   
   
   public static function isValidCEP($cep) : bool
   {
      $cep = str_replace("-", "", $cep);
      if(!is_numeric($cep))
         return false;
      
      if(strlen($cep) != 8)
         return false;
      
      if($cep == "00000000")
         return false;
      
      return true;
   }
   public static function isValidCPF($number) : bool
   {

       $number = preg_replace('/[^0-9]/', '', (string) $number);

       if (strlen($number) != 11)
           return false;

       for ($i = 0, $j = 10, $sum = 0; $i < 9; $i++, $j--){
           $sum += $number[$i] * $j;
       }
       
       $rest = $sum % 11;
       if ($number[9] != ($rest < 2 ? 0 : 11 - $rest))
           return false;

       for ($i = 0, $j = 11, $sum = 0; $i < 10; $i++, $j--)
           $sum += $number[$i] * $j;
       $rest = $sum % 11;

       return ($number[10] == ($rest < 2 ? 0 : 11 - $rest));

   }
}
