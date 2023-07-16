<?php

namespace App\Models\Entidades;

class Clientes extends Entidades {

   private $nid;
   private $cnome;
   private $cemail;
   private $ccpfcnpj;
   private $ddataNascimento;
   private $ctelefone;
   private $ccelular;
   private $lreceberPromocoes;
   private $csenha;

   function getNid() {
      return $this->nid;
   }

   function getCnome() {
      return $this->cnome;
   }

   function getCemail() {
      return $this->cemail;
   }

   function getCcpfcnpj() {
      return $this->ccpfcnpj;
   }

   function getDdataNascimento() {
      return $this->ddataNascimento;
   }

   function getCtelefone() {
      return $this->ctelefone;
   }

   function getCcelular() {
      return $this->ccelular;
   }

   function getLreceberPromocoes() {
      return $this->lreceberPromocoes;
   }

   function getCsenha() {
      return $this->csenha;
   }

   function setNid($nid): void {
      $this->nid = $nid;
   }

   function setCnome($cnome): void {
      $this->cnome = $cnome;
   }

   function setCemail($cemail): void {
      $this->cemail = $cemail;
   }

   function setCcpfcnpj($ccpfcnpj): void {
      $this->ccpfcnpj = $ccpfcnpj;
   }

   function setDdataNascimento($ddataNascimento): void {
      $this->ddataNascimento = $ddataNascimento;
   }

   function setCtelefone($ctelefone): void {
      $this->ctelefone = $ctelefone;
   }

   function setCcelular($ccelular): void {
      $this->ccelular = $ccelular;
   }

   function setLreceberPromocoes($lreceberPromocoes): void {
      $this->lreceberPromocoes = $lreceberPromocoes;
   }

   function setCsenha($csenha): void {
      $this->csenha = $csenha;
   }
   

}
