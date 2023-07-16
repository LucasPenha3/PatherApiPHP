<?php

namespace App\Utils;

class ArrayList {
   
   private $list = array();
   private $filter = array();
   private $operadorFiltro;
   
   /**
    * Adiciona o objeto ao list. Se o paramentro não for um objeto não é adicionado
    * @param type $object : Especifique um objeto
    */
   public function add($object){
      if(is_object($object))
        array_push($this->list, $object);
   }
   
   /**
    * Cria o list com o array especificado
    * @param type $array : Especifique um array de objetos
    */
   public function addAll($array){
      // verifica já na primeira linha se não for um objeto, não adiciona ao list
      foreach ($array as $a){
         if(!is_object($a)){
            break;
         }else{
            $this->list = $array;
            break;
         }
      }
      
   }
   
   /**
    * @param type $indexOuObject : Indice a ser removido ou o objeto a ser removido
    */
   public function remove($indexOuObject){
      if(is_object($indexOuObject)){
         $index = $this->indexOf($indexOuObject);
         unset($this->list[$index]);
      }else if(is_int($indexOuObject)){
         unset($this->list[$indexOuObject]);
      }
      $this->reorderList();
   }
   
   public function cloneArray(){
      return $this->list;
   }
   
   /**
    * retorna o objeto contido o indice, se não houver retorna nulo
    * @param type $index : Indice a ser procurado
    * @return object 
    */
   public function getObject($index){
      if(array_key_exists($index, $this->list)){
         return $this->list[$index];
      }else{
         return null;
      }
              
   }
   
   /**
    * Retorna o indice que faz referencia ao objeto, se não encontrar retorna -1
    * @param type $object : objeto procurado
    * @return int : retorna o indice do objeto procurado ou -1
    */
   public function getIndex($object){
      $key = array_search($object, $this->list);
      if(empty($key) || empty($object))
         return -1;
      else
         return $key;
   }
   
   /** Verifica se o list está vazio
    * @return boolean : true ou false */
   public function isEmpty(){
      if(empty($this->list)){
         return true;
      }else{
         return false;
      }
   }
   
   /**
    * Retorna o tamanho do list
    * @return int
    */
   public function size(){
      return count($this->list);
   }
   
   /**
    * Retorna true se o objeto exisitr no array
    * @param type $object : Informe o objeto a ser procurado
    * @return boolean : true se encontrar false se não
    */
   public function contains($object){
      if(in_array($object, $this->list)){
         return true;
      }else{
         return false;
      }
      
      /*$flag = false;
      foreach ($this->list as $list) {
         if ($list == $object) {
            $flag == true;
         }
      }
      return $flag;*/
   }
   
   
   public function getList(){
      return $this->list;
   }

   /**
    * 
    * @param type $filter : array contendo campo e valor a ser filtrado
    * @param type $operador = enum tipo de operador relacional
    * @return array : array filtrado
    */
   public function getFilter($filter, $operador = null) {
      $this->operadorFiltro = $operador;
      if(is_array($filter)){
         $this->filter = $filter;
         
         return array_filter($this->list, array($this,'filterList'));
         //var_dump(array_filter($this->list, array($this,'filterList')));
         
      }else{
         return null;
      }
   }

   /**
    * Filtra o array de acordo com a propriedade filtro (array)
    * @param type $value : é o campo que está passando no arrayList
    */
   protected function filterList($value) {
      $newArray = array();
      foreach ($this->filter as $k => $v) {
         $newArray = $this->getArrayFilter($v, $k, $value,$newArray);
         //if(trim($v) == substr($value->$k, 0, strlen(trim($v))))
         //   $newArray[] = $value;
         
      }
      //var_dump($newArray); echo "<hr>";
      return $newArray;
   }
   
   protected function filterListMaiorIgual($value) {
      $newArray = array();
      foreach ($this->filter as $k => $v) {
         if($v >= $value->$k)
            $newArray[] = $value;  
      }
      return $newArray;
   }
   
   /**
    * Retorna o filtro referente
    * @param string $v : valor procurado
    * @param string $k : Campo onde procurar
    * @param object $value : Objeto onde procurar
    * @param array $arrayRetorno : array que será preenchido e retornado
    * @return array
    */
   private function getArrayFilter($v, $k, $value,$arrayRetorno) : array {
      if($value instanceof \stdClass){
         $flag = true;
      }else{
         $falg = false;
         $metodo = "get".$k;
      }
      
      switch ($this->operadorFiltro){
         case "==":
            //echo $this->operadorFiltro;echo " if(".$value->$k." == $v)<br>";
            if(trim($v) == substr((!$flag) ? $value->{$metodo}() : $value->$k, 0, strlen(trim($v))))
               $arrayRetorno[] = $value;
            break;
         case ">=":
            if($value->$k >= $v)
               $arrayRetorno[] = $value;
            break;
         case "<=":
            if($value->$k <= $v)
               $arrayRetorno[] = $value;
            break;   
         case ">":
            if($value->$k > $v)
               $arrayRetorno[] = $value;
            break;
         case "<":
            if($value->$k < $v)
               $arrayRetorno[] = $value;
            break;
         case "!=":
            if($v != $value->$k)
               $arrayRetorno[] = $value;
            break;
      }
      //\gerais::dumpArray($retorno);
      return $arrayRetorno;
   }
   
   private function reorderList() {
      $array = array();
      $cont = 0;
      foreach ($this->list as $list){
         $array[$cont] = $list;
         $cont++;
      }
      $this->list = $array;
   }
}