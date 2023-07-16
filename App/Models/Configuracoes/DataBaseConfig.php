<?php


namespace App\Models\Configuracoes;

use App\Models\Entidades\Entidades;

class DataBaseConfig extends Entidades{

   private $tipo;
   private $servidor;
   private $banco_dados;
   private $usuario;
   private $senha;
   
   function getTipo() {
      return $this->tipo;
   }

   function getServidor() {
      return $this->servidor;
   }

   function getBanco_dados() {
      return $this->banco_dados;
   }

   function getUsuario() {
      return $this->usuario;
   }

   function getSenha() {
      return $this->senha;
   }

   function setTipo($tipo): void {
      $this->tipo = $tipo;
   }

   function setServidor($servidor): void {
      $this->servidor = $servidor;
   }

   function setBanco_dados($banco_dados): void {
      $this->banco_dados = $banco_dados;
   }

   function setUsuario($usuario): void {
      $this->usuario = $usuario;
   }

   function setSenha($senha): void {
      $this->senha = $senha;
   }

   
}
