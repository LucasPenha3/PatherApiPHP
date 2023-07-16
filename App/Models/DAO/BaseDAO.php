<?php

namespace App\Models\DAO;

use App\Lib\Conexao;
use App\Lib\Excecoes\BancoDadosException;
use App\Lib\Excecoes\PersonalException;
use App\Utils\Formata;
use App\Utils\Log;
use PDOException;
use ReflectionClass;
use Swoole\MySQL\Exception;

abstract class BaseDAO{

   private $conexao;
   protected $table;

   public function __construct() {
      try {
         $this->conexao = Conexao::getConnection();      
      }catch (PDOException $ex) {
         throw new BancoDadosException($ex);
      } catch (\Exception $e){
         throw $e;
      }
      
   }
   
   public function validaConexao(){
      return $this->conexao != null;
   }
   
   public function findByFiltroLockForUpdate(array $filtro, $table = '')
   {
      try
      {
         $this->table = strtolower($this->table);
         if (empty($table))
            $table = $this->table;

         $array = $this->getFiltro($filtro);
         $stringValores = $array["string"];
         $valores = $array['valores'];

         if (!empty($stringValores))
         {

            $stringValores = substr($stringValores, 0, strlen(trim($stringValores)) - 3);

            $sql = "Select * from $table where $stringValores for update";

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($valores);
            return $stmt->fetchAll();
         } else
         {
            return array();
         }
      } catch (Exception $exc)
      {
         Log::setLog($exc->getMessage(), "FindByFiltroLockForUpdateBaseDao");
         echo $exc->getMessage();
         die;
      }
   }

   public function findByFiltroLock(array $filtro, $table = '') {
      
      try {
      
         $this->table = strtolower($this->table);
         if(empty($table))
            $table = $this->table;

         $array = $this->getFiltro($filtro);
         $stringValores = $array["string"];
         $valores = $array['valores'];

         if (!empty($stringValores)) {

            $stringValores = substr($stringValores, 0, strlen(trim($stringValores)) - 3);

            $sql = "Select * from $table where $stringValores for update";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($valores);
            return $stmt->fetchAll();
         } else {
            return array();
         }
         
      } catch (Exception $exc) {
         Log::setLog($exc->getMessage(), "FindByFiltroLockBaseDao");
         echo $exc->getMessage();
         die;
      }      
   }

   
   
   /**
    * pesquisa todos os registros do dao instanciado
    * @return Bool
    */
   public function findAll() {
      $this->table = strtolower($this->table);
      $sql = "Select * from $this->table ";
      $stmt = $this->conexao->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll();
   }
   
   /**
    * pesquisa usando uma string sql como paramentro
    * @param type $sql : Sql a ser executado
    * @return bool
    */
   public function findBySql($sql) {
      if (!empty($sql)) {
         $stmt = $this->conexao->prepare($sql);
         $stmt->execute();
         return $stmt->fetchAll();
      }
   }

   /**
    * Pesquisa usando um array para filtrar
    * @param array $filtro : array contendo os dados a serem filtrados, as chaves devem ser o nome dos campos no BD
    * @param type $limit : (opcional) Limit do sql
    * @param type $table : (opcional) Se não for passada pegará o nome contido no dao instanciado
    * @return type
    */
   public function findByFiltro(array $filtro, $limit = 10000, $table = '') {
      
      try {
      
         $this->table = strtolower($this->table);
         if(empty($table))
            $table = $this->table;

         $stringLimit = "limit $limit";

         $array = $this->getFiltro($filtro);

         $stringValores = $array["string"];
         $valores = $array['valores'];

         if (!empty($stringValores)) {

            $stringValores = substr($stringValores, 0, strlen(trim($stringValores)) - 3);

            $sql = "Select * from $table where $stringValores $stringLimit";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($valores);
            return $stmt->fetchAll();
         } else {
            return array();
         }
         
      } catch (Exception $exc) {
         Log::setLog($exc->getMessage(), "FindByFiltroBaseDao");
         echo $exc->getMessage();
         die;
      }

      
   }

   /**
    * Responsável por devolver os dados do array passado como filtro
    * @param type $filtro : array Filtro
    * @return array : array contendo "string"=>$stringSql (usada depois do where), "valores"=>$valores (a serem passados ao execute())
    */
   private function getFiltro($filtro) : array{
      $valores = array();
      $stringValores = "";

      foreach ($filtro as $key => $value) {
         
         $arrayVal = explode("&", $value);
         
         if(!empty($arrayVal[1])){
            
            switch ($arrayVal[1]){
               case "like":
                  // valor&like
                  $stringValores = $key . " like :" . $key . " and " . $stringValores;
                  $valores[$key] = Formata::getLike(str_replace("&like", "", $value));
                  break;
               case "between":
                  // dataANDdata&between
                  $stringValores = $key . " :" . $key . "1 and " .$key."2 and".  $stringValores;
                  $val = explode("AND", $arrayVal[0]);
                  $valores[$key."1"] = $val[1];
                  $valores[$key."2"] = $val[2];
                  break;
            }
            
         }  
         else{
            $stringValores = $key . "= :" . $key . " and " . $stringValores;
            $valores[$key] = $value;
         }
         
      }
      
      return array("string"=>$stringValores, "valores"=>$valores);
   }
   
   /**
    * pesquisa usando um limit
    * @param type $limit
    * @return type
    */
   public function findByLimit($limit) {
      $this->table = strtolower($this->table);
      $sql = "Select * from $this->table limit $limit";
      $stmt = $this->conexao->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll();
   }

   /**
    * Insere um Objeto no banco de dados. O Objeto deve ser uma instancia preenchida das classes Model (Entidade)
    * @param type $obj : Entidade a ser inserida
    * @return bool
    * @throws Exception
    */
   public function insertObj($obj) :bool {

      $this->table = strtolower($this->table);
      try {
         $this->validaObjeto($obj);
         $class = $obj->getClass($obj);
         
         $stringProperties = "";
         $arrayValues = array();
         $api = new ReflectionClass($class);
         foreach ($api->getProperties() as $p) {
            
            $arrayExclusoes = $this->getArrayExclusoes($obj);
            if ($p->name != 'nid' && !in_array($p->name, $arrayExclusoes)) {
               $method = "get" . $p->name;

               $stringProperties = ":" . $p->name . "," . $stringProperties;
               $arrayValues[":$p->name"] = $obj->{$method}();
            }
         }

         $stringProperties = substr($stringProperties, 0, strlen($stringProperties) - 1);
         return $this->buildInsert($class, $stringProperties, $arrayValues);
         
      } catch (Exception $ex) {
         Log::setLogErro("Erro ao inserir Objeto ".$this->table, $ex, "insertObjBaseDao");
         throw $ex;
      }catch (PersonalException $ex) {
         Log::setLogErro("Erro ao inserir Objeto ".$this->table, $ex, "insertObjBaseDao");
         throw $ex;
      }
      
   }
   
   /**
    * Insere o objeto no banco
    * @param type $class : GetClass da entidade
    * @param type $obj : Entidade a ser inserida
    * @return bool
    * @throws Exception
    */
   public function insert($class, $obj) :bool {
      
      $this->table = strtolower($this->table);
      try {
         $this->validaObjeto($obj);         
         
         $stringProperties = "";
         $arrayValues = array();
         $api = new ReflectionClass($class);
         foreach ($api->getProperties() as $p) {
            
            $arrayExclusoes = $this->getArrayExclusoes($obj);
            if ($p->name != 'nid' && !in_array($p->name, $arrayExclusoes)) {
               $method = "get" . $p->name;

               $stringProperties = ":" . $p->name . "," . $stringProperties;
               $arrayValues[":$p->name"] = $obj->{$method}();
            }
         }

         $stringProperties = substr($stringProperties, 0, strlen($stringProperties) - 1);
         return $this->buildInsert($class, $stringProperties, $arrayValues);
         
      } catch (Exception $ex) {
         Log::setLogErro("Erro ao inserir Objeto ".$this->table, $ex, "insertBaseDao");
         throw $ex;
      }catch (PersonalException $ex) {
         Log::setLogErro("Erro ao inserir Objeto ".$this->table, $ex, "insertBaseDao");
         throw $ex;
      }
      
   }

   /**
    * Responsável por complmentar e executar o insert (métod insert())
    * @param type $table
    * @param type $cols
    * @param type $values
    * @return bool
    * @throws BancoDadosException
    * @throws Exception
    */
   private function buildInsert($table, $cols, $values) : bool{
      
      $this->table = strtolower($this->table);
      try {
         if (!empty($table) && !empty($cols) && !empty($values)) {
            $parametros = $cols;
            $colunas = str_replace(":", "", $cols);

            $stmt = $this->conexao->prepare("INSERT INTO $this->table ($colunas) VALUES ($parametros)");
            $stmt->execute($values);

            return true;
         } else {
            return false;
         }
      } catch (PDOException $e){
         throw new BancoDadosException($e);
      }catch (Exception $ex) {
         throw $ex;
      }
      
   }
   
   /**
    * Atualiza os dados do banco usando o objeto passado
    * @param type $objeto : Instancia da classe model (entidade) a ser atualizado no banco
    * @return type
    * @throws Exception
    */
   public function update($objeto){
      
      $this->table = strtolower($this->table);
      try {
         $this->validaObjeto($objeto);
         
         $id = $objeto->getNid();
         $class = $objeto->getClass($objeto);
         
         $stringProperties = "";
         $arrayValues = array();
         $api = new ReflectionClass($class);
         foreach ($api->getProperties() as $p) {
            
            $arrayExclusoes = $this->getArrayExclusoes($objeto);
            if ($p->name != 'nid' && !in_array($p->name, $arrayExclusoes)) {
               $method = "get" . $p->name;

               $stringProperties = $p->name." = :" . $p->name . "," . $stringProperties;
               $arrayValues[":$p->name"] = $objeto->{$method}();
            }
         }

         $arrayValues['nid'] = $id;
         $stringProperties = substr($stringProperties, 0, strlen($stringProperties) - 1);

         return $this->buildUpdate($class, $stringProperties, $arrayValues, $id);
      
      } catch (Exception $ex) {
         Log::setLogErro("Erro ao atualizar tabela ".$this->table, $ex, "UpdateBaseDao");
         throw $ex;
      }catch (PersonalException $ex) {
         Log::setLogErro("Erro ao atualizar tabela ".$this->table, $ex, "UpdateBaseDao");
         throw $ex;
      }
      
      
   }
   
   /**
    * Atualiza o objeto no banco
    * @param type $class : GetClass 
    * @param type $obj : Entidade a ser atualizada
    * @param type $id : NID
    * @return bool
    * @throws Exception
    */
   public function updateById($class, $obj, $id="") :bool {
      
      $this->table = strtolower($this->table);
      $id = (empty($id)) ? $obj->getNid() : $id;
            
      try {
         $stringProperties = "";
         $arrayValues = array();
         $api = new ReflectionClass($class);
         foreach ($api->getProperties() as $p) {
            
            $arrayExclusoes = $this->getArrayExclusoes($obj);
            if ($p->name != 'nid' && !in_array($p->name, $arrayExclusoes)) {
               $method = "get" . $p->name;

               $stringProperties = $p->name." = :" . $p->name . "," . $stringProperties;
               $arrayValues[":$p->name"] = $obj->{$method}();
            }
         }

         $arrayValues['nid'] = $id;
         $stringProperties = substr($stringProperties, 0, strlen($stringProperties) - 1);

         return $this->buildUpdate($class, $stringProperties, $arrayValues, $id);
      
      } catch (Exception $ex) {
         Log::setLogErro("Erro ao atualizar tabela ".$this->table, $ex, "UpdateByIdBaseDao");
         throw $ex;
      }
      
   }

   /**
    * Responsável por complementar e executar o update (nmétodo updateById()) no banco de dados
    * @param type $table : Tabela da entidade
    * @param type $cols : Colunas a serem alteradas
    * @param type $values : Novos valores
    * @param type $id : NID
    * @return bool
    * @throws BancoDadosException
    * @throws Exception
    */
   private function buildUpdate($table, $cols, $values, $id) : bool {
      
      $this->table = strtolower($this->table);
      try {
         if (!empty($table) && !empty($cols) && !empty($values)) {
         
            $stmt = $this->conexao->prepare("UPDATE $this->table  set $cols where nid = :nid");
            $stmt->execute($values);

            return true;
         } else {
            return false;
         }
      } catch (PDOException $e){
         Log::setLogErro("Erro ao atualizar tabela ".$this->table, $ex, "BuildUpdateBaseDao");
         throw new BancoDadosException($e);
      } catch (Exception $ex) {
         Log::setLogErro("Erro ao atualizar tabela ".$this->table, $ex, "BuildUpdateBaseDao");
         throw $ex;
      }
      
      
   }
   
   /**
    * Atualiza usando o array, ao invés de usar o objeto inteiro. Usa o $table do DAO instanciado
    * @param array $camposValores : Campos e valores a serem atualizados
    * @param type $id : NID
    * @return bool
    * @throws Exception
    */
   public function updateArrayById(array $camposValores, $id) : bool{
      $this->table = strtolower($this->table);
      try {
         $string = "";
         $arraValues = array();

         foreach ($camposValores as $key => $value) {
            $string = $string." $key = :$key,";
            $arraValues[":$key"] = $value;
         }

         $arraValues[":nid"] = $id;
         $string = substr(trim($string), 0, strlen(trim($string))-1);

         $sql = "UPDATE $this->table set $string where nid = :nid";
         $stmt = $this->conexao->prepare($sql);
         $stmt->execute($arraValues);
         return true;
         
      } catch (PDOException $e){
         return new BancoDadosException($e);
      }catch (Exception $ex) {
         throw $ex;
      }
      
      
   }
   /**
    * Atualiza campos usando o array e na clausula where recebe array de parms
    * @param array $camposValores
    * @param array $filtro
    * @return bool
    * @throws Exception
    */
    public function updateArrayByArray(array $camposValores, array $filtro) : bool{
      $this->table = strtolower($this->table);
      try {
         $string = "";
         $arraValues = array();

         foreach ($camposValores as $key => $value) {
            $string = $string." $key = :$key,";
            $arraValues[":$key"] = $value;
         }
         
         foreach ($filtro as $key => $value) {
            $arraValues[":$key"] = $value;
         }
         
         $arrayFiltro = $this->getFiltro($filtro);
         $stringFiltroValores = $arrayFiltro["string"];
         $valoresFiltro = $arrayFiltro['valores'];
         
         if (!empty($stringFiltroValores)) {
            $string = substr(trim($string), 0, strlen(trim($string))-1);
         
            $stringFiltroValores = substr($stringFiltroValores, 0, strlen(trim($stringFiltroValores)) - 3);
            $sql = "UPDATE $this->table set $string where $stringFiltroValores";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute($arraValues);
         }
        
         return true;
         
      } catch (PDOException $e){
         throw new BancoDadosException($e);
      }catch (Exception $ex) {
         throw $ex;
      }
      
      
   }
   
   /**
    * Atualiza por sql especifico
    * @param type $sql : String SQL
    * @return boolean
    * @throws Exception
    */
   public function updateBySql($sql) {
      try {
         $stmt = $this->conexao->prepare($sql);
         $stmt->execute();
         return true;
      } catch (Exception $ex) {
         throw $ex;
      }
      
   }
   
   /**
    * Insere por sql especifico
    * @param type $sql : String SQL
    * @return boolean
    * @throws Exception
    */
   public function insertBySql($sql) {
      try {
         $stmt = $this->conexao->prepare($sql);
         $stmt->execute();
         return true;
      } catch (Exception $ex) {
         throw $ex;
      }
      
   }
   
   /**
    * Deleta pelo NID especifico - Usa a $table do dao instanciado
    * @param type $id
    * @return bool
    * @throws BancoDadosException
    */
   public function deleteById($id) : bool{
      $this->table = strtolower($this->table);
      try {
         $stmt = $this->conexao->prepare("Delete from $this->table where nid = :nid");
         $stmt->bindParam(':nid', $id);
         return $stmt->execute();
      } catch (PDOException $ex) {
         throw new BancoDadosException($ex);
      }
      
   }
   
   public function deleteBySql($sql) : bool{
      try {
         $stmt = $this->conexao->prepare($sql);
         return $stmt->execute();
      } catch (PDOException $ex) {
         throw new BancoDadosException($ex);
      }
      
   }
   
   /**
    * Deleta filtrando pelo field e fieldValue especificado
    * @param type $field : Campo a ser filtrado
    * @param type $fieldValue : Valor do campo a ser filtrado
    * @return type
    * @throws BancoDadosException
    */
   public function deleteByField($field, $fieldValue){
      $this->table = strtolower($this->table);
      try {
         $stmt = $this->conexao->prepare("Delete from $this->table where $field = :fieldValue");
         $stmt->bindParam(':fieldValue', $fieldValue);
         return $stmt->execute();
         
      } catch (PDOException $ex) {
         throw new BancoDadosException($ex);
      }
      
   }
   
   /**
    * Deleta usando o array de para filtrar no banco de dados
    * @param array $filtro : Array contendo campos e valores a serem filtrados
    * @return boolean
    * @throws BancoDadosException
    */
   public function deleteByFiltro(array $filtro){
      $this->table = strtolower($this->table);
      try {
         
         $array = $this->getFiltro($filtro);
         $stringValores = $array["string"];
         $valores = $array['valores'];
         
         if (!empty($stringValores)) {
            $stringValores = substr($stringValores, 0, strlen(trim($stringValores)) - 3);
            $sql = "Delete from $this->table where $stringValores";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute($valores);
            
         }else
            return true;
         

         
      } catch (PDOException $ex) {
         throw new BancoDadosException($ex);
      }
      
   }
   
   /**
    * Retorna o ultimo ID inserido depois de um insert
    * @return type
    */
   public function lastInsertId(){
      return $this->conexao->lastInsertId();
   }
   
   /**
    * Inicia transação
    * @return type
    */
   public function beginTransaction(){
      return $this->conexao->beginTransaction();
   }
   
   /**
    * Executa a transação no banco de dados
    * @return type
    */
   public function commit(){
      return $this->conexao->commit();
   }
   
   /**
    * Desfaz transação
    * @return type
    */
   public function rollback(){
      return $this->conexao->rollBack();
   }
   
   
   private function getArrayExclusoes($objeto) {
      $arrayExclusoes = array();
      if(method_exists($objeto, "getNotBd"))
         $arrayExclusoes = $objeto->getNotBd();
      
      return $arrayExclusoes;
   }
   
   private function validaObjeto($objeto){
      
      if( is_null($objeto))
         throw new PersonalException ("Objeto passado para DAO é nulo");
       
      if(empty($objeto))
         throw new PersonalException ("Objeto passado para DAO é vazio");
       
      if(!is_object($objeto))
          throw new PersonalException ("Parametro passado para DAO não é um objeto");
       
      return true;

   }

}
