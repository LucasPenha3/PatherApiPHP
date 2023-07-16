<?php

namespace App\Services;

use App\Lib\Excecoes\BancoDadosException;
use App\Lib\Excecoes\UsuarioNaoLogadoException;
use App\Lib\Sessao;
use App\Models\DAO\UsuariosDAO;
use App\Models\Entidades\Firma;
use App\Models\Entidades\Usuarios;
use App\Utils\Enums\EnumCode;
use App\Utils\Validacao;
use App\Utils\ValidacaoSenhas;
use Exception;

class LoginService extends Services{
   
   private int $code;
   private string $mensagem;
   
   function getCode(): int {
      return $this->code;
   }

   function getMensagem(): string {
      return $this->mensagem;
   }

      
   public function getArrayLogin(array $arrayPost){
      try {
         $post = Validacao::getFilterVariaveisRequisicao($arrayPost);

         $usuario = Validacao::getFilterVariavel($post['usuario'], FILTER_SANITIZE_STRING);
         $senha = Validacao::getFilterVariavel($post['senha']);
         $senhaMd5 = ValidacaoSenhas::getSenhaMd5($senha);

         $usuarioDao = new UsuariosDAO();
         return $usuarioDao->findByFiltro(array("usuario"=>$usuario, "senha"=>$senhaMd5));
         
      } catch (BancoDadosException $e){
         throw $e;
      } 
      catch (Exception $ex) {
         throw new Exception($ex->getMessage(), EnumCode::E401);
      }
           
   }
   
   public function setLogin() {
      
      try {
         $post = Validacao::getFilterRequisicao(INPUT_POST);
      
         $arrayUsers = $this->getArrayLogin($post);
         
         if(count($arrayUsers) == 1){
            $usuarios = new Usuarios();
            
            $usuarios->setEntidade($usuarios, $arrayUsers);
            Sessao::setSessaoUsuario($usuarios);
            Sessao::setSessaoFirma(new Firma(true,"",false));
            
            $this->code = 200;
            $this->mensagem = "success";
         }else{
            Sessao::setSessaoUsuario(new Usuarios());
            $this->code = 400;
            $this->mensagem = "Usuario ou senha não encontrados";
         }
      } catch (UsuarioNaoLogadoException $ex) {
         $this->code = 500;
         $this->mensagem = "error: Code: {$ex->getCode()} - Message: {$ex->getMessage()}";
      }
      
      
   }
   
   
}
