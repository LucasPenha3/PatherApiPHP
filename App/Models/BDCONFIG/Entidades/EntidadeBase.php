<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\BDCONFIG\Entidades;

use ReflectionClass;

/**
 * Description of EntidadeBase
 *
 * @author Lucas
 */
abstract class EntidadeBase {
   
   public function setEntidade($obj, $arrayObject) {

      $object = (object) $arrayObject;
      if(!is_object($arrayObject) && isset($arrayObject[0]) && is_object($arrayObject[0])){
         $object = $arrayObject[0];
      }
      
      //$obj = new $class();

      $api = new ReflectionClass($obj->getClass());
      foreach ($api->getProperties() as $p) {
         $nomeProp = $p->name;
         $nomeFunction = 'set' . $nomeProp;
         
         $nomePropObj = (isset($object->{$nomeProp})) ?  $nomeProp : substr($nomeProp, 1);
         if( isset($object->{$nomePropObj}) && !is_null($object->{$nomePropObj}) )
            $obj->{$nomeFunction}($object->{$nomePropObj});
      }

      //$this->entidade = $obj;
   }
   
}
