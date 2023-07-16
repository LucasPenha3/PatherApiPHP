<?php

namespace App\Models\Configuracoes;

use App\Models\Entidades\Entidades;

class Config_Geral extends Entidades{

   private $nomeProjeto;
   private $pastaRootDev;
   private $pastaRoot;
   private $logoProjeto;
   
   function getNomeProjeto() {
      return $this->nomeProjeto;
   }

   function getPastaRootDev() {
      return $this->pastaRootDev;
   }

   function getPastaRoot() {
      return $this->pastaRoot;
   }

   function setNomeProjeto($nomeProjeto): void {
      $this->nomeProjeto = $nomeProjeto;
   }

   function setPastaRootDev($pastaRootDev): void {
      $this->pastaRootDev = $pastaRootDev;
   }

   function setPastaRoot($pastaRoot): void {
      $this->pastaRoot = $pastaRoot;
   }

   function getLogoProjeto() {
      return $this->logoProjeto;
   }

   function setLogoProjeto($logoProjeto): void {
      $this->logoProjeto = $logoProjeto;
   }




   
   
}
