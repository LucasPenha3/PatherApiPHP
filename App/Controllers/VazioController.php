<?php

namespace App\Controllers;

use App\Utils\Utils;

/**
 * Description of Vazio : Controlador vazio. Aqui ainda sentado js e css para view. Caso de não ser api
 * @author Lucas
 */
class VazioController extends Controller {

   public static $menu = "NomeMenu";

   
   public function index() {
      $this->setCss();
      $this->setJs();
   }
   
   
  
   
   /** Setando Js para a view */
   private function setJs() {
      $this->setViewParam('js', array(
                                      Utils::getHrefPublic() . "/../plugins/sweetalert2/sweetalert2.min.js",
                                      Utils::getHrefPublic() . "/dist/js/pages/cadastros/cadastro.js",
                                      Utils::getHrefPublic() . "/dist/js/jsPadrao.js"
                                      )
                         );
   }
   
   /** Setando CSS para a View*/
   private function setCss() {
      $this->setViewParam('css', array(
          
                                       Utils::getHrefPublic() . "/../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"
                                      ));
   }
   


}
