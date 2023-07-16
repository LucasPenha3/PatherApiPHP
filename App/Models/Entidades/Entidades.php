<?php

namespace App\Models\Entidades;

use ReflectionClass;

/**
 * Description of Entidades
 * @author Lucas
 */
abstract class Entidades {

   private $entidade;

   /**
    * Seta a entidade passada no primeiro parametro
    * @param Obj $obj : Entidade a ser preenchida
    * @param type $arrayObject
    */
   public function setEntidade($arrayObject) {

      $obj = $this;
      
      $object = (object) $arrayObject;
      if(!is_object($arrayObject) && isset($arrayObject[0]) && is_object($arrayObject[0])){
         $object = $arrayObject[0];
      }
      
      //$obj = new $class();

      $api = new ReflectionClass($obj->getClass($obj));
      foreach ($api->getProperties() as $p) {
         $nomeProp = $p->name;
         $nomeFunction = 'set' . $nomeProp;
         
         $nomePropObj = (isset($object->{$nomeProp})) ?  $nomeProp : substr($nomeProp, 1);
         if( isset($object->{$nomePropObj}) && !is_null($object->{$nomePropObj}) )
            $obj->{$nomeFunction}($object->{$nomePropObj});
      }

      //$this->entidade = $obj;
   }
   
   
   public function setEntidadeArrayJson($arrayObject) {

      $obj = $this;
      
      $object = $arrayObject;
      if(!is_object($arrayObject) && isset($arrayObject[0]) && is_object($arrayObject[0])){
         $object = $arrayObject[0];
      }
      
      //$obj = new $class();

      $api = new ReflectionClass($obj->getClass($obj));
      foreach ($api->getProperties() as $p) {
         $nomeProp = $p->name;
         $nomeFunction = 'set' . $nomeProp;
         
         $nomePropArray = substr($p->name, 1);
         $obj->{$nomeFunction}($object->{$nomePropArray});
      }

      $this->entidade = $obj;
   }


   public function getEntidade() {
      return $this->entidade;
   }
   
   
   public function getDadosSerializados($obj){
      $array = array();
      $api = new ReflectionClass($obj->getClass());
      foreach ($api->getProperties() as $p) {
         $nomeProp = $p->name;
         $nomeFunction = 'get' . $nomeProp;
         
         //$nomePropObj = (isset($object->{$nomeProp})) ?  $nomeProp :substr($nomeProp, 1);
         $array[$nomeProp] = $obj->{$nomeFunction}();
      }
      $json = utf8_encode(json_encode($array));
      return $json;
      //echo  $json;
   }
      
   /**
    * Retorna as propriedades da classe em forma de array : A primeira letra é desconsiderada
    * @param type $obj : Entidade desejada
    * @return type
    */
   public function getArrayDados($obj){
      $array = array();
      $api = new ReflectionClass($obj->getClass());
      foreach ($api->getProperties() as $p) {
         $nomeProp = $p->name;
         $nomeFunction = 'get' . $nomeProp;
         
         // tiros os primeiros caracteres
         $usar = $nomeProp == "sku" || $nomeProp == "tags" ? $nomeProp : substr($nomeProp, 1);
         $array[$usar] = $obj->{$nomeFunction}();
      }
      return $array;
   }
   
   public function getClass($obj = null){
      return get_class($obj);
   }
   
}
