<?php


namespace App\Lib;

use App\Models\DAO\Envios_ConfigDao;
use App\Models\Entidades\Envios_Pac;
use App\Models\Entidades\Envios_Sedex;
use App\Models\Entidades\ModeloRetornoFrete;
use App\Utils\Formata;
use App\Utils\Objeto;
use App\Utils\Serializacao;

class Correios {
   
   private $erro;
   private $msgErro;
   
   /* Váriaveis de parametro para buscar valor e prazo */
   private
        $cdEmpresa = "", // obrigatório para clientes com contrato
        $dsSenha = "", // Obrigatório para clientes com contrato
        $servico,
        $cepOrigem,
        $cepDestino,
        $peso,
        $formato = '1',
        $comprimento,
        $altura,
        $largura,
        $diametro = "0", // para produtos redondos
        $maoPropria = 'N',
        $valordeclarado = '0',
        $avisoRecebimento = 'N',
        $retorno = 'xml';
   
   private $sedxLiberado = false;
   private $pacLiberado = false;
   
   /** @var Envios_Sedex*/
   private $objSedex = null;
   
   /** @var Envios_Pac */
   private $objPac = null;
   
   private $prazoEntrega;
   private $valorEntrega;
   private $urlInicial = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";
   
   private $arrayRetorno;
   
   
   public function __construct() {
      $dao = new Envios_ConfigDao();
      
      foreach ($dao->getConfigs() as $config){
         $config = Objeto::envios_config($config);
         
         if($config->getNid() == 1 && $config->getLativo() == 1){
            $this->pacLiberado = true;
            $this->objPac = new Envios_Pac(true,$dao);
         }
         if($config->getNid() == 2 && $config->getLativo() == 1){
            $this->objSedex = new Envios_Sedex(true, $dao);
            $this->sedxLiberado = true;
         }
      }
      
      $this->arrayRetorno = array("PAC"  => new ModeloRetornoFrete(array("Tipo"=>"PAC","Entrega"=>false,"Valor"=>0, "Prazo"=>0, "MensagemErro"=>false)),
                                  "SEDEX"=> new ModeloRetornoFrete(array("Tipo"=>"SEDEX","Entrega"=>false,"Valor"=>0, "Prazo"=>0, "MensagemErro"=>false))
                                 );  
   }
 
   
   function setPeso($peso, $qtde): void {
      if(strpos($peso, ",") >= 1)
         $peso = Formata::toDecimalBd($peso) * $qtde;
      
      $this->peso = $peso*$qtde;
   }

   function setComprimento($comprimento): void {
      if(strpos($comprimento, ",") >= 1)
         $comprimento = (Formata::toDecimalBd($comprimento) < 16) ? 16 : Formata::toDecimalBd($comprimento);
      
      $this->comprimento = ($comprimento < 16) ? 16 : $comprimento;
   }

   function setAltura($altura): void {
      if(strpos($altura, ",") >= 1)
         $altura = (Formata::toDecimalBd($altura) < 2) ? 2 : Formata::toDecimalBd($altura);
      
      $this->altura = ($altura < 2) ? 2 : $altura;
   }

   function setLargura($largura): void {
      if(strpos($largura, ",") >= 1)
         $largura = Formata::toDecimalBd($largura);
      
      $this->largura = ($largura < 11) ? 11 :$largura;
   }

   function getArrayRetorno() {
      return $this->arrayRetorno;
   }
   
   public function calculaValores($cepDestino, $peso, $comprimento,$altura,$largura,$qtde, $valor){
      
      $this->setPropriedades($cepDestino, $peso, $comprimento, $altura, $largura, $qtde, $valor);
      
      if($this->pacLiberado){
         $arrayPac = $this->getArrayDadosCorreios("PAC");
         $this->setArrayRetorno($arrayPac, "PAC");
      }
            
      if($this->sedxLiberado){
         $arraySedex = $this->getArrayDadosCorreios("Sedex");
         $this->setArrayRetorno($arraySedex, "SEDEX");
      }
      
   }
   
   /**
    * Calcula a raiz cubica dos itens do carrinho
    * @param array $carrinho : Session carrinho
    * @param string $chaveItens : nome da chave do array referente aos itens 
    * @param string $chaveQuantidade : nome da chave do array referente as quantidades
    * @return array : array contendo peso total do carrinho (peso) e raiz cubica das medidas * quantidade (raiz)
    */
   public function calcularRaizCubicaCarrinho($carrinho, $chaveItens, $chaveQuantidade) : array{
      
      $totalPeso = 0;
      $totalCubagem = 0;
      
      foreach ($carrinho[$chaveItens] as $item){
         $quantidade = $item[$chaveQuantidade];
         $produto = Objeto::produtos(Serializacao::decrypt($item['produto']));
         
         $pesoItem = $produto->getNpeso() * $quantidade;
         $cubagemItem = ($produto->getNaltura() * $produto->getNaltura() * $produto->getNcomprimento()) * $quantidade;
         
         $totalPeso += $pesoItem;
         $totalCubagem += $cubagemItem;
      }
      
      $raiz = round(pow($totalCubagem, 1/3),2);
      
      return array("peso"=>$totalPeso, "raiz"=>$raiz);
      
   }
   
   private function setPropriedades($cepDestino, $peso, $comprimento,$altura,$largura,$qtde, $valor){
      
      $this->cepDestino = Formata::somenteNumeros($cepDestino);
      $this->setPeso($peso,$qtde);
      $this->setComprimento($comprimento);
      $this->setAltura($altura);
      $this->setLargura($largura);
      //$this->valordeclarado = 2703;
      
      if($qtde >= 2){
         $totalCubagem = (Formata::toDecimalBd($altura) * Formata::toDecimalBd($largura) * Formata::toDecimalBd($comprimento)) * $qtde;
         
         //$totalCubagem = (77 * 100 * 10) * 1;
         //$totalCubagem += (39 * 25 * 31) * 1;
         //$totalCubagem += (50 * 100 * 2) * 1;
         //$this->setPeso(14.5, 1);
         
         $raiz_cubica = round(pow($totalCubagem, 1/3), 2);
         $this->setAltura($raiz_cubica);
         $this->setLargura($raiz_cubica);
         $this->setComprimento($raiz_cubica);
         
         //$this->setComprimento($comprimento*$qtde);
         //$this->setAltura($altura*$qtde);
         //$this->setLargura($largura*$qtde);
      }
      
   }
   
   private function getArrayDadosCorreios($tipo) {
      //$this->diametro = hypot($this->comprimento, $this->largura); 
      if($tipo == "PAC"){
            $valores = array(
               'nCdEmpresa'            => $this->objPac->getLcontrato() == 1 ? $this->objPac->getNcodEmpresa() : "",
               'sDsSenha'              => $this->objPac->getLcontrato() == 1 ? $this->objPac->getCsenhaAcesso() : "",
               'sCepOrigem'            => Formata::somenteNumeros($this->objPac->getCcepOrigem()),
               'sCepDestino'           => $this->cepDestino,
               'nVlPeso'               => $this->peso,
               'nCdFormato'            => $this->formato,
               'nVlComprimento'        => $this->comprimento,
               'nVlAltura'             => $this->altura,
               'nVlLargura'            => $this->largura,
               'sCdMaoPropria'         => $this->objPac->getLmaoPropria(),
               'nVlValorDeclarado'     => $this->valordeclarado,
               'sCdAvisoRecebimento'   => $this->objPac->getLavisoRecebimento(),
               'nCdServico'            => $this->objPac->getLcontrato() == 1 ? $this->objPac->getCservico() : "04510",
               'nVlDiametro'           => $this->diametro,
               'StrRetorno'            => $this->retorno,
               'nIndicaCalculo'        => 3
         );
      }else{
         $valores = array(
               'nCdEmpresa'            => $this->objSedex->getLcontrato() == 1 ? $this->objSedex->getNcodEmpresa() : "",
               'sDsSenha'              => $this->objSedex->getLcontrato() == 1 ? $this->objSedex->getCsenhaAcesso() : "",
               'sCepOrigem'            => Formata::somenteNumeros($this->objSedex->getCcepOrigem()),
               'sCepDestino'           => $this->cepDestino,
               'nVlPeso'               => $this->peso,
               'nCdFormato'            => $this->formato,
               'nVlComprimento'        => $this->comprimento,
               'nVlAltura'             => $this->altura,
               'nVlLargura'            => $this->largura,
               'sCdMaoPropria'         => $this->objSedex->getLmaoPropria(),
               'nVlValorDeclarado'     => $this->valordeclarado,
               'sCdAvisoRecebimento'   => $this->objSedex->getLavisoRecebimento(),
               'nCdServico'            => $this->objSedex->getLcontrato() == 1 ? $this->objSedex->getCservico() : "04014",
               'nVlDiametro'           => $this->diametro,
               'StrRetorno'            => $this->retorno,
               'nIndicaCalculo'        => 3
         );
      }
      return $valores;
   }
   
   private function setArrayRetorno(array $valores,$tipoServico) {
      
      if(strtoupper($tipoServico) != "PAC" && strtoupper($tipoServico) != "SEDEX"){
         return ;
      }
      
      $queryString = http_build_query($valores);
      
      $url = "{$this->urlInicial}?{$queryString}";
      $xml = simplexml_load_file($url);
      
      
      
      if($xml->cServico->Erro ==0){
                  
         $this->arrayRetorno[$tipoServico] = new ModeloRetornoFrete(
                 array("Tipo"=>"$tipoServico",
                       "Entrega"=>true,
                       "Valor"=>(string) $xml->cServico->Valor, 
                       "Prazo"=>(string) $xml->cServico->PrazoEntrega, 
                       "MensagemErro"=>false));
      }else{
         $this->erro = (string) $xml->cServico->Erro;
         $this->msgErro = ($xml->cServico->MsgErro && (string) $xml->cServico->MsgErro) ? (string) $xml->cServico->MsgErro : "Não foi possível verificar os valores nos correios";
         
         $this->arrayRetorno[$tipoServico] = new ModeloRetornoFrete(
                 array("Tipo"=>"$tipoServico",
                       "Entrega"=>false,
                       "Valor"=>0, 
                       "Prazo"=>0, 
                       "MensagemErro"=>$this->erro." | ".$this->msgErro));
      }
      
   }
}
