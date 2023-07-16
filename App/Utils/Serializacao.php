<?php

namespace App\Utils;

/**
 * Description of Serializacao
 * Serializa e deserializa objetos e arrays
 * @author Lucas
 */
class Serializacao {

   
   private static $chaveSerializacao = "Adicione-Uma-Chave-De-Serializacao";
   private static $chave = "Adicione-Uma-Chave-De-Uso";
   private static $methodCrip = "aes-256-cbc";
   
   private static $chaveApi = "tkApi%10@2021";

   public static function getMd5($string){
      return md5($string.self::$chaveSerializacao);
   }
   
   public static function serializaJson($valor){
      return (is_array($valor)) ? json_encode($valor) : (Validacao::isJson($valor) ? $valor : false);
   }
   
   public static function desserializaJson($json){
      if (Validacao::isJson($json)) 
               return json_decode($json);
      else if(is_object($json) || is_array($json))
              return $json;
      else
          return false;
   }
   
   public static function serializar($val) {
      if(is_array($val) || is_object($val)){
         return base64_encode(serialize($val));
      }else{
         $valor = $val."::@".self::$chaveSerializacao;
         return base64_encode(serialize($valor));
      }
   }

   public static function desserializar($val) {
      $valor = unserialize(base64_decode($val));
      
      if(is_object($valor) || is_array($valor)){
         return $valor;
      }else{
         $valor = explode("::@", $valor);

         if(isset($valor[1]))
            return $valor[0];
         else 
            return $string;
      }
   }

   public static function encrypt($data) {
      
      $dataSerializado = serialize($data);
      $encryption_key = self::getEncriptionKey();
      
      $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$methodCrip));
      $encrypted = openssl_encrypt($dataSerializado, self::$methodCrip, $encryption_key, 0, $iv);
      
      return base64_encode($encrypted . '::' . $iv);
   }

   public static function decrypt($data) {
      
      $encryption_key = self::getEncriptionKey();
      list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
      $dec = openssl_decrypt($encrypted_data, self::$methodCrip, $encryption_key, 0, $iv);
      
      $decript = unserialize($dec);
      return $decript;
   }
   
   public static function encryptTokenAPI($data) {
      
      $dataSerializado = serialize($data);
      $encryption_key = self::$chaveApi;
      
      $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$methodCrip));
      $encrypted = openssl_encrypt($dataSerializado, 'AES128', $encryption_key, 0, $iv);
      
      return base64_encode($encrypted . '::' . $iv);
   }

   public static function decryptTokenAPI($data) {
      
      $encryption_key = self::$chaveApi;
      list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
      $dec = openssl_decrypt($encrypted_data, 'AES128', $encryption_key, 0, $iv);
      return unserialize($dec);
   }

   private static function getEncriptionKey() {
      $encryption_key = base64_decode(md5(self::$chave));
      return $encryption_key;
   }
   
}
