<?php

namespace App\Lib;

use App\Lib\Excecoes\PersonalException;
use App\Utils\Formata;
use Swoole\MySQL\Exception;

class File {

   private $name;
   private $tmpName;
   private $size;
   private $type;
   private $error;
   private $isValid;
   private $extensionFile;
   
   private $extensoes = array();
   private $extensoesImagen  = array("png","jpg","bmp");
   private $diretorioSalvar = "";
   private $diretorioTemp = "";

   
   
    public function __construct($file = "") {
      if(!empty($file['tmp_name'])){
         $this->name = $file['name'];
         $this->tmpName = $file['tmp_name'];
         $this->size = $file['size'];
         $this->type = $file['type'];
         $this->error = $file['error'];
         $this->extensionFile = end(explode(".", $this->name));
         $this->isValid = ($this->error == UPLOAD_ERR_OK) ? true : false;
      }
   }
   
   public function setDadosBase64($stringBase64){
      $binario =  base64_decode($stringBase64);
      $dados = getimagesizefromstring($binario);
      
      switch ($dados['mime']){
         case "image/jpeg":
            $this->type = "jpg";
            break;
         case "image/png":
            $this->type = "png";
            break;
         case "image/gif":
            $this->type = "gif";
            break;
         case "image/bmp":
            $this->type = "bmp";
            break;
      }
   }
   
   public static function gravarArquivoFopen(string $caminho, string $nome, string $conteudo){
      try {
         $fp = fopen($caminho.$nome, "a+");
         fwrite($fp, $conteudo);
         fclose($fp);
      } catch (Exception $ex) {
         throw  $ex;
      }
   }
   
   public function setName($name){
      $this->name = $name;
   }
   
   public function getName() {
      return $this->name;
   }

   public function getTmpName() {
      return $this->tmpName;
   }

   public function getSize() {
      return $this->size;
   }

   public function getType() {
      return $this->type;
   }

   public function getError() {
      return $this->error;
   }

   public function isValid() {
      return $this->isValid;
   }
   
   function getExtensoes() {
      return $this->extensoes;
   }

   function setExtensoes($extensoes): void {
      $this->extensoes = $extensoes;
   }

   function getDiretorioSalvar() {
      return $this->diretorioSalvar;
   }

   function getDiretorioTemp() {
      return $this->diretorioTemp;
   }

   function setDiretorioTemp($diretorioTemp): void {
      $this->diretorioTemp = $diretorioTemp;
   }
   
   public function renameComExtensao($newName){
      $this->name = $newName;
   }

   public function rename($newname) {
      $this->name = $newname . "." . pathinfo($this->name, PATHINFO_EXTENSION);
   }
   
   function getExtensionFile() {
      return $this->extensionFile;
   }

   function setExtensionFile($extensionFile): void {
      $this->extensionFile = $extensionFile;
   }

      
    /**
    * Cria o diretorio recursivamente se não exisitr e seta a propriedade diretorio salvar
    * @param type $diretorioSalvar
    * @return void
    */
   function setDiretorioSalvar($diretorioSalvar): void {
      if(!is_dir($diretorioSalvar))
         $a = mkdir ($diretorioSalvar,0777,true);
      $this->diretorioSalvar = $diretorioSalvar;
   }

   public function deletarArquivo($caminhoCompleto) : bool {
      return unlink($caminhoCompleto);
   }
           
   public function upload() : bool{
      
      if(empty($this->name) && empty($this->tmpName)){
         return true;
      }
      
      try {
         
         if($this->verificaExtensao()){
            $caminhoArquivo = $this->diretorioSalvar.$this->name;
            
            if(move_uploaded_file($this->tmpName, $caminhoArquivo))
               return true;
            else
               return false;
            
         }else{
            $extensoesString = Formata::arrayToString($this->extensoes, " | ");
            throw new PersonalException("O arquivo deve estar nas seguintes extensoes: <b>".$extensoesString."</b>.");
         }
      } catch (Exception $ex) {
         throw $ex;
      }  
   }
   
   /**
    * Move um arquivo de uma pasta para outra excluindo o antigo
    * Move do diretório temporário setada para a diretório salvar setado
    * @param type $nomeFileTemp : nome do arquivo que está na pasta temporária
    * @param type $nomeFileNovo : nome do novo arquivo que vai ser criado ao mover
    * @return type bool
    */
   public function move($nomeFileTemp, $nomeFileNovo) : bool {
      return rename($this->diretorioTemp.$nomeFileTemp, $this->diretorioSalvar.$nomeFileNovo);
   }
   
   public function isFile($fileName){
      return is_file($fileName);
   }
   
   function deleteDir($dir) {
      if(!is_dir($dir)){
         return true;
      }
      
      $files = array_diff(scandir($dir), array('.','..')); 
      foreach ($files as $file) { 
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
      } 
      return rmdir($dir); 
    }
   
   private function verificaExtensao() : bool{
      
      if(count($this->extensoes) == 0){
         return true;
      }
      
      $array = explode(".", $this->name);
      $extensao = end($array);
      
      if(!in_array($extensao, $this->extensoes)){
         return false;
      }
      return true;
   }
   
   /**
    * Deleta as pastas e arquivos de um diretório recursivamente
    * @param type $dir : Diretório
    * @return bool
    */
   public static function deletarRecursivo($dir) { 
      if(!is_dir($dir))
         return true;
      
      $files = array_diff(scandir($dir), array('.','..')); 
      foreach ($files as $file) { 
        (is_dir("$dir/$file")) ? deletarRecursivo("$dir/$file") : unlink("$dir/$file"); 
      } 
      return rmdir($dir); 
   }
   
   public function verificaExtensaoImagem(){
      
      if(in_array($this->extensionFile, $this->extensoesImagen))
         return true;
      else
         return false;
      
   }
   
}
