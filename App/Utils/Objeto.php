<?php

namespace App\Utils;

use App\Lib\Excecoes\PersonalException;
use App\Lib\PagHiper\Responses\PagHipperResponseNotification;
use App\Models\Entidades\Categorias;
use App\Models\Entidades\Ceps;
use App\Models\Entidades\Clientes;
use App\Models\Entidades\ClientesEnderecos;
use App\Models\Entidades\Configuracoes;
use App\Models\Entidades\Emails;
use App\Models\Entidades\Envios_Config;
use App\Models\Entidades\Envios_Pac;
use App\Models\Entidades\Envios_Proprio;
use App\Models\Entidades\Envios_Sedex;
use App\Models\Entidades\Firma;
use App\Models\Entidades\FormasPagamento\Fpgto;
use App\Models\Entidades\ModeloRetornoFrete;
use App\Models\Entidades\NotDB\PossibilidadesEntrega;
use App\Models\Entidades\Pedidos;
use App\Models\Entidades\PedidosAbandonados;
use App\Models\Entidades\PedidosEnderecos;
use App\Models\Entidades\PedidosItens;
use App\Models\Entidades\PedidosPagamento;
use App\Models\Entidades\PedidosStatus;
use App\Models\Entidades\Produtos;
use App\Models\Entidades\ProdutosAvaliacoes;
use App\Models\Entidades\ProdutosCategorias;
use App\Models\Entidades\ProdutosFichaTecnica;
use App\Models\Entidades\ProdutosImagens;
use App\Models\Entidades\ProdutosRelacionados;
use App\Models\Entidades\ProdutosSimilares;
use App\Models\Entidades\SetoresAtendimento;
use App\Models\Entidades\Slides;
use App\Models\Entidades\Subcategorias;
use App\Models\Entidades\Usuarios;
use App\Models\Entidades\VPedidos;
use App\Models\Entidades\VPedidosItens;

/**
 * Description of Objeto : Retorna o objeto do tipo especificado
 * @author Lucas
 */
class Objeto {

   public static function usuario($objeto): Usuarios {

      if ($objeto instanceof Usuarios)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Usuarios\"");
   }

   public static function setoresAtendimento($objeto): SetoresAtendimento {
      if ($objeto instanceof SetoresAtendimento)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"SetoresAtendimento\"");
   }

   public static function clientes($objeto): Clientes {
      if ($objeto instanceof Clientes)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Clientes\"");
   }

   public static function clientesEndereco($objeto): ClientesEnderecos {
      if ($objeto instanceof ClientesEnderecos)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ClientesEnderecos\"");
   }

   public static function categorias($objeto): Categorias {
      if ($objeto instanceof Categorias)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Categorias\"");
   }

   public static function subCategorias($objeto): Subcategorias {
      if ($objeto instanceof Subcategorias)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Subcategorias\"");
   }

   public static function fichaTecnica($objeto): ProdutosFichaTecnica {
      if ($objeto instanceof ProdutosFichaTecnica)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ProdutosFichaTecnica\"");
   }

   public static function produtosCategorias($objeto): ProdutosCategorias {
      if ($objeto instanceof ProdutosCategorias)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ProdutosCategorias\"");
   }

   public static function produtos($objeto): Produtos {
      if ($objeto instanceof Produtos)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Produtos\"");
   }

   public static function produtosRelacionados($objeto): ProdutosRelacionados {
      if ($objeto instanceof ProdutosRelacionados)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ProdutosRelacionados\"");
   }

   public static function produtosSimilares($objeto): ProdutosSimilares {
      if ($objeto instanceof ProdutosSimilares)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ProdutosSimilares\"");
   }

   public static function produtosAvaliacoes($objeto): ProdutosAvaliacoes {
      if ($objeto instanceof ProdutosAvaliacoes)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ProdutosAvaliacoes\"");
   }

   public static function produtosImagens($objeto): ProdutosImagens {
      if ($objeto instanceof ProdutosImagens)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ProdutosImagens\"");
   }

   public static function firma($objeto): Firma {
      if ($objeto instanceof Firma)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Firma\"");
   }

   public static function pedidos($objeto): Pedidos {
      if ($objeto instanceof Pedidos)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Pedidos\"");
   }

   public static function pedidosStatus($objeto): PedidosStatus {
      if ($objeto instanceof PedidosStatus)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PedidosStatus\"");
   }

   public static function pedidosEndereco($objeto): PedidosEnderecos {
      if ($objeto instanceof PedidosEnderecos)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PedidosEnderecos\"");
   }

   public static function pedidosPagamento($objeto): PedidosPagamento {
      if ($objeto instanceof PedidosPagamento)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PedidosPagamento\"");
   }

   public static function pedidosItens($objeto): PedidosItens {
      if ($objeto instanceof PedidosItens)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PedidosItens\"");
   }

   public static function pedidosAbandonados($objeto): PedidosAbandonados {
      if ($objeto instanceof PedidosAbandonados)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PedidosAbandonados\"");
   }

   public static function emails($objeto): Emails {
      if ($objeto instanceof Emails)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Emails\"");
   }

   public static function vPedidos($objeto): VPedidos {
      if ($objeto instanceof VPedidos)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"VPedidos\"");
   }

   public static function vPedidosItens($objeto): VPedidosItens {
      if ($objeto instanceof VPedidosItens)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"VPedidosItens\"");
   }

   public static function ceps($objeto): Ceps {
      if ($objeto instanceof Ceps)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Ceps\"");
   }

   public static function envios_config($objeto): Envios_Config {
      if ($objeto instanceof Envios_Config)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Envios_Config\"");
   }

   public static function envios_pac($objeto): Envios_Pac {
      if ($objeto instanceof Envios_Pac)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Envios_Pac\"");
   }

   public static function envios_sedex($objeto): Envios_Sedex {
      if ($objeto instanceof Envios_Sedex)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Envios_Sedex\"");
   }

   public static function envios_proprio($objeto): Envios_Proprio {
      if ($objeto instanceof Envios_Proprio)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Envios_Proprio\"");
   }

   public static function modeloRetornoFrete($objeto): ModeloRetornoFrete {
      if ($objeto instanceof ModeloRetornoFrete)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ModeloRetornoFrete\"");
   }

   public static function fpgto($objeto): Fpgto {
      if ($objeto instanceof Fpgto)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Fpgto\"");
   }

   public static function possibilidadesEntrega($objeto): PossibilidadesEntrega {
      if ($objeto instanceof PossibilidadesEntrega)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PossibilidadesEntrega\"");
   }

   public static function pagHipperResponseNotification($objeto): PagHipperResponseNotification {
      if ($objeto instanceof PagHipperResponseNotification)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PagHipperResponseNotification\"");
   }

   public static function arrayList($objeto): ArrayList {
      if ($objeto instanceof ArrayList)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ArrayList\"");
   }

   public static function configuracoes($objeto): Configuracoes {
      if ($objeto instanceof Configuracoes)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Configuracoes\"");
   }

   public static function slides($objeto): Slides {
      if ($objeto instanceof Slides)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"Slide\"");
   }
   
   public static function clientesConfig($objeto): \App\Models\BDCONFIG\Entidades\Clientes {
      if ($objeto instanceof \App\Models\BDCONFIG\Entidades\Clientes)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ClientesConfig\"");
   }
   
   public static function modeloCss($objeto): \App\Lib\StyleCss\ModeloVariaveis {
      if ($objeto instanceof \App\Lib\StyleCss\ModeloVariaveis)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"ModeloVariaveisCSS\"");
   }   
   
   public static function pedidosItensDto($objeto): \App\Models\DTOS\SET\PedidoItensDto {
      if ($objeto instanceof \App\Models\DTOS\SET\PedidoItensDto)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"PedidoItensDto\"");
   }   
   
   public static function clientesIntegracaoDados($objeto): \App\Models\BDCONFIG\Entidades\ClientesIntegracaoDados {
      if ($objeto instanceof \App\Models\BDCONFIG\Entidades\ClientesIntegracaoDados)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"clientesIntegracaoDados\"");
   }   
   
   public static function app($objeto): \App\App {
      if ($objeto instanceof \App\App)
         return $objeto;
      else
         throw new PersonalException("Objeto não é do tipo \"App\"");
   }   

}
