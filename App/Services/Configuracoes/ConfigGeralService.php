<?php

namespace App\Services\Configuracoes;

use App\Models\Configuracoes\Config_Geral;

class ConfigGeralService {
   
   private const NOME_ARQUIVO = 'config-geral.ini';
   
   /** @var \App\Models\Configuracoes\Config_Geral */
   private static $objeto;
   
   public static function getConfiguracoesGerais() : Config_Geral {
      
      $arquivoIni = realpath("./"). "/conf/".self::NOME_ARQUIVO;
      
      $arrayIni = array();
      if (file_exists($arquivoIni))
         $arrayIni = parse_ini_file($arquivoIni);

      self::setObjeto($arrayIni);
      
      return self::$objeto;
   }
   
   private static function setObjeto(array $arrayIni) {
      
      self::$objeto = new Config_Geral();
      self::$objeto->setEntidade($arrayIni);
      
   }
   
}
