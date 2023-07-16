<?php

namespace App\Lib\AWS;

use App\Utils\Enums\EnumCode;
use App\Utils\Utils;
use Aws\S3\S3Client;
use Exception;
use const CNPJLOGIN;
use function GuzzleHttp\json_encode;

class S3 {

   /** @var S3Client */
   private $s3;
   
   private static $caminhoInicialS3 = ""; // algo como "https://NomeDaFirma.s3.us-east-2.amazonaws.com/"
   private $bucketName = "";
   private $accessKey  = ""; // access key do usuario IAM
   private $secretKey  = ""; // secret key do usuario IAM
   private $caminhoBucket;
   private $urlLastFile;
   private $fileLocal;
   
   function getUrlLastFile() {
      return $this->urlLastFile;
   }

   
   public static function isFolderS3($link) {
      
      if(strpos($link, "//NomeDaFirma.s3.us-east-2") == false){
         return false;
      }
      return true;
   }
   
   /**
    * @param string $tipo : Use enum TipoPasta
    * @return tipo
    */
   public function __construct(string $tipoPasta) {
      try{
         $this->caminhoBucket = CNPJLOGIN."/".$tipoPasta."/";
         $this->s3 = new S3Client(array(
            "credentials"=>array(
                "key"=> $this->accessKey,
                "secret"=> $this->secretKey
            ),
            "version"=>"latest",
            "region"=>"us-east-2" 
         ));
         
      } catch (Exception $ex) {
         return json_encode(array(
             "code"=>EnumCode::E401,
             "message"=>$ex->getMessage()
         ));
      }
      
   }
   
   public static function getCaminhoBucketImagens(string $tipoPasta){
      return self::$caminhoInicialS3.CNPJLOGIN."/".$tipoPasta."/";
   }


   public function PutImgLink($link){
      
      $pathTmp = Utils::getDirPublicPage()."\\tmp\\tmpfile\\";
      $nameFile = basename($link);
      $pathS3   = $this->caminhoBucket.$nameFile;
      
      try {
            
         $this->saveFileLocal($pathTmp, $link);
         
         $result = $this->s3->putObject(array(
            "Bucket"=> $this->bucketName,
            "Key"=> $pathS3,
            "SourceFile"=> $this->fileLocal,
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'ACL'    => 'public-read',
         ));
         
         $this->urlLastFile = $result['ObjectURL'] . PHP_EOL;
         unlink($this->fileLocal);
         
      }
      catch (Exception $exc) {
         return json_encode(array(
            "code"=> EnumCode::E500,
            "message"=>$exc->getMessage()
         ));
      }
   }
   
   private function putImgFile(){}
   
  
   private function saveFileLocal($pathTmp, $link) {
      
      $rand = rand(1,9999);
      
      if (!file_exists($pathTmp))
         mkdir($pathTmp);
      
      $arquivoLocal = $pathTmp.$rand.basename($link);
      
      $a =  file_put_contents(
               $arquivoLocal,
               file_get_contents($link)
            );
      
      $this->fileLocal = $arquivoLocal;
      if(!$a)
         $this->fileLocal = "";
      
   }
   
}
