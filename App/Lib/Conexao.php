<?php

namespace App\Lib;

use App\Services\Configuracoes\DataBaseConfigService;
use App\Utils\Validacao;
use Exception;
use PDO;
use PDOException;
use const CNPJLOGIN;
use function Aws\parse_ini_file;

class Conexao {

   private static $connection;

   private function __construct() {
      
   }

   public static function getConnection() {
      
      $objeto = DataBaseConfigService::getConfiguracoes(CNPJLOGIN);
      
      $driver = $objeto->getTipo();
      $host   = $objeto->getServidor();
      $dbName = $objeto->getBanco_dados();
      $user   = $objeto->getUsuario();
      $senha  = $objeto->getSenha();

      $pdoConfig = $driver . ":" . "host=" . $host . ";";
      $pdoConfig .= "dbname=" . $dbName . ";";

      try {
         if (!isset(self::$connection)) {
            self::$connection = new PDO($pdoConfig, $user, $senha);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self::$connection->exec("set names utf8");
         }
         return self::$connection;
      } catch (PDOException $e) {
         throw new Exception("Erro de conexão com o banco de dados", 500);
      }
   }
   
   
   public static function getConnectionConfig() {
      $caminho = realpath('./');
      $arquivoIni = $caminho."/conf/database-config.ini";
            
      $arrayIni = array();
      if (file_exists($arquivoIni))
         $arrayIni = parse_ini_file($arquivoIni);

      $driver = Validacao::verificaCampoArray($arrayIni, 'tipo', "mysql");
      $host   = Validacao::verificaCampoArray($arrayIni, 'servidor');
      $dbName = Validacao::verificaCampoArray($arrayIni, 'banco_dados');
      $user   = Validacao::verificaCampoArray($arrayIni, 'usuario');
      $senha  = Validacao::verificaCampoArray($arrayIni, 'senha');

      $pdoConfig = $driver . ":" . "host=" . $host . ";";
      $pdoConfig .= "dbname=" . $dbName . ";";

      try {
         if (!isset($connection)) {
            $connection = new PDO($pdoConfig, $user, $senha);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $connection->exec("set names utf8");
         }
         return $connection;
      } catch (PDOException $e) {
         throw new Exception("Erro de conexão com o banco de dados", 500);
      }
   }


}
