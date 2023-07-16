<?php

namespace App\Utils;

use App\Models\DAO\EstadosDao;

/**
 * Description of Views : Retorna algumas visualizações úteis em php
 * @author Lucas
 */
class Componentes {

    public static function getTooltip($message, $local = "top") {
        return "data-toggle='tooltip' data-placement='$local' title='$message'";
    }

    public static function alert($mensagem, $type = 'success', $demissible = true) {
        $classDemissible = ($demissible) ? "alert-dismissible fade show" : "";
        $buttonFechar = ($demissible) ?
                "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
               </button>" : "";


        $alert = "<div class='alert alert-$type $classDemissible' role='alert'>";
        $alert .= $mensagem;
        $alert .= $buttonFechar;
        $alert .= "</div>";

        return $alert;
    }

    public static function getEstadoCidade($estadoPadrao = "", $cidadePadrao = "Selecione a UF...") {
        $estadoDao = new EstadosDao();
        ?>
        <div class="col-md-6 col-lg-2 col-sm-3">
            Estado
            <select name="estado" class="form-control" onchange="preencheCidadesPadrao(this.value,0,'cidade')" id="estado">
                <option value='<?= $estadoPadrao ?>'><?= $estadoPadrao ?></option>
                <?= $estadoDao->preencheCombo(); ?>
            </select>
        </div>
        <div class="col-md-6 col-lg-4 col-sm-6">
            Cidade
            <select name="cidade" class="form-control" id="cidade" >
                <option value='<?= ($cidadePadrao == "Selecione a UF...") ? "" : $cidadePadrao ?>'><?= $cidadePadrao ?></option>
            </select>
        </div>
        <?php
    }

    /**
     * @param string $id : id do campo
     * @param string $nome : nome do campo
     * @param string $valorSelecionar : valor a selecionar
     * @param bool $colorir : se colore o componente de verde e vermelho para seleção ou não
     * @param int $width : tamanho do componente em percentual
     */
    public static function getSelectSimOuNao($id, $nome, $valorSelecionar, $colorir = false, $width = 100) {
        $corSelect = "";
        $selecionaSim = $valorSelecionar == 1 ? 'selected' : '';
        $selecionaNao = $valorSelecionar == 0 ? 'selected' : '';
        $onchange = "";

        if ($colorir) {
            $corSelect = $valorSelecionar == 1 ? "background: #0174DF; color: white;" : "background: #B40404; color: white;";
            $onchange = "onchange='alteraCorSelect(\"$id\",this.value,$width)'";
        }

        echo "
      <select name='$nome' id='$id' style='$corSelect width: $width%;' class='form-control' $onchange>
         <option value='1' style='background: white; color: black;' $selecionaSim > Sim</option>
         <option value='0' style='background: white; color: black;' $selecionaNao > Não</option>
      </select>";
    }

    public static function getTrueOrFalse($valor) {

        if ($valor == "0")
            $valor = "<font color='red'>✘</font>";
        else if ($valor == "1")
            $valor = "<font color='green'>✔</font>";
        else
            $valor = $valor;

        return $valor;
    }

}
