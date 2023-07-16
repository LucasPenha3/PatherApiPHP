<?php

namespace App\Models\BDCONFIG\Entidades;

use App\Models\Entidades\Entidades;

class Clientes extends Entidades{
   
   private $id;
   private $razaoSocial;
   private $nomeFantasia;
   private $cnpj;
   private $inscricao;
   private $uf;
   
   function getId() {
      return $this->id;
   }

   function getRazaoSocial() {
      return $this->razaoSocial;
   }

   function getNomeFantasia() {
      return $this->nomeFantasia;
   }

   function getCnpj() {
      return $this->cnpj;
   }

   function getInscricao() {
      return $this->inscricao;
   }

   function getUf() {
      return $this->uf;
   }

   function setId($id): void {
      $this->id = $id;
   }

   function setRazaoSocial($razaoSocial): void {
      $this->razaoSocial = $razaoSocial;
   }

   function setNomeFantasia($nomeFantasia): void {
      $this->nomeFantasia = $nomeFantasia;
   }

   function setCnpj($cnpj): void {
      $this->cnpj = $cnpj;
   }

   function setInscricao($inscricao): void {
      $this->inscricao = $inscricao;
   }

   function setUf($uf): void {
      $this->uf = $uf;
   }


   
}
