<?php

namespace App\Services\Configuracoes;

use App\Models\Configuracoes\DataBaseConfig;

class DataBaseConfigService {
   
   private const NOME_ARQUIVO = 'database';
   
   /** @var \App\Models\Configuracoes\DataBaseConfig */
   private static $objeto;
   
 
   public static function getConfiguracoes($cnpj) : DataBaseConfig {
      
      $arquivoIni = realpath("./"). "/conf/".self::NOME_ARQUIVO.".ini";
      
      $arrayIni = array();
      if (file_exists($arquivoIni))
         $arrayIni = parse_ini_file($arquivoIni);

      self::setObjeto($arrayIni);
      self::$objeto->setBanco_dados('store-'.$cnpj);
      
      return self::$objeto;
   }
   
   public static function getConfiguracoesInternas() : DataBaseConfig {
      
      $arquivoIni = realpath("./"). "/conf/".self::NOME_ARQUIVO."-config.ini";
      
      $arrayIni = array();
      if (file_exists($arquivoIni))
         $arrayIni = parse_ini_file($arquivoIni);

      self::setObjeto($arrayIni);
      
      return self::$objeto;
   }
   
   private static function setObjeto(array $arrayIni) {
      
      self::$objeto = new DataBaseConfig();
      self::$objeto->setEntidade($arrayIni);
      
   }
   
}
