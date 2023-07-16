<?php

namespace App\Utils;

use const APP_HOST;
use const APP_HOST_SITE;
use const PROTOCOL;
use function mb_strlen;
use function mb_strpos;
use function mb_substr;

class Utils {

   /** @var int : Indica a versão dos arquivos css e js. Para não precisar dar ctrl+f5 */
   private static $versionFiles = 2;
   
   /** @var string : Indica a versão do projeto */
   private static $versionApp = "1.0";
   
   
   public static function getVersionApp(){
      return self::$versionApp;
   }
   
   public static function getHrefPublic() {
      return PROTOCOL . "://" . APP_HOST . "/public";
   }

   public static function getHrefPage() {
      return PROTOCOL . "://" . APP_HOST;
   }

   public static function getHrefInicialSite() {
      return PROTOCOL . "://" . APP_HOST_SITE;
   }

   public static function getDirPublicPage() {
      
      $dir = realpath("./");
      $diretorio = $dir . "\public";
      return $diretorio;
   }
   
  

   public static function getUrlAtual() {

      $uriAtual = $_SERVER['REQUEST_URI'];
      $urlAtual = substr($uriAtual, strpos($uriAtual, '/') + 1);

      if (strrchr($urlAtual, '/') === '/') {
         $urlAtual = substr($urlAtual, 0, strlen($urlAtual) - 1);
      }

      return "http://" . APP_HOST . $urlAtual;
   }

   public static function getJsPage($array): string {
      $js = "";
      if (!empty($array)) {
         foreach ($array as $j) {
            $js .= "<script src='{$j}?". self::$versionFiles."'></script>";
         }
      }
      return $js;
   }

   public static function getCssPage($array): string {
      $css = "";
      if (!empty($array)) {
         foreach ($array as $j) {
            $css .= "<link rel='stylesheet' href='{$j}?". self::$versionFiles."'>";
         }
      }
      return $css;
   }

   public static function getScriptsPage($array) {
      $js = "";
      if (!empty($array)) {
         foreach ($array as $j) {
            $js .= $j . "";
         }
      }
      return $js;
   }

   /**
    * Exibe o conteudo do tab
    * @param string $target
    * @param string $tab
    * @return string/null
    */
   public static function ativaTabs(string $target, string $tab): string {
      if (empty($target)) {
         $target = 'basico';
      }
      if (str_replace('?', '', $target) == $tab) {
         return 'show active';
      }

      return null;
   }

   /**
    * Ativa a link do tab
    * @param string $target
    * @param string $tab
    * @return string|null
    */
   public static function ativaLinkTab(string $target, string $tab): string {
      if (empty($target)) {
         $target = 'basico';
      }
      if (str_replace('?', '', $target) == $tab) {
         return 'active';
      }
      return null;
   }

   /**
    * Retorna o tab selecioando
    * @return string
    */
   public static function getTabSelecionado(): string {
      $urlAtual = Utils::getUrlAtual();

      $posicao = (mb_strpos($urlAtual, '?') ?? strlen($urlAtual));

      if ($posicao == false) {
         $posicao = mb_strlen($urlAtual);
      }

      return mb_substr($urlAtual, $posicao);
   }

   
   /**
    * @param type $parametro
    * @return boolean : Returna false se o parametro for false, zero ou null
    */
   public static function getValorLogico($parametro){
      
      if(is_string($parametro)){
         if(trim($parametro) != "0" && trim($parametro) != "")
            return true;
         else 
            return false;
      }
      
      if(is_numeric($parametro)){
         if(trim($parametro) != 0)
            return true;
         else 
            return false;
      }
      
      if($parametro == false || $parametro == null)
         return false;
      else 
         return true;
      
   }
}

//