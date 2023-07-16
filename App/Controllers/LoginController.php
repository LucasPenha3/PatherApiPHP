<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Services\LoginService;
use App\Utils\Utils;
use App\Utils\Validacao;

class LoginController extends Controller {

   public function index() {
      $this->setJs();
      $this->setScripts();
      
      $this->renderLogin('login/index');
   }

   public function logar() {
      Sessao::setFormulario(Validacao::getFilterRequisicao(INPUT_POST));
      
      $service = new LoginService();
      $service->setLogin();
     
      $code = ($service->getLoginRealizado()) ? 200 : 500;
      $this->getResponse($code, $service->getMensagem());
      
   }
   
}
