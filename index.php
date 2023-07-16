<?php

use App\App;
use App\Lib\Erro;

error_reporting(E_ALL & ~E_NOTICE);
require_once("vendor/autoload.php");

try 
{
   $app = new App();
   $app->run();
} 
catch (\Exception $e) {

   $oError = new Erro($e);
   http_response_code(500);
   \App\Utils\Log::setLog("Erro roda API.".$e->getMessage(), "appphp");
   echo json_encode(array("500"=>"Erro interno do servidor"));
}