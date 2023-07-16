<?php

namespace App\Models\DTOS;

use App\Utils\Log;
use Exception;
use ReflectionClass;

abstract class AbstractDTO
{

    public function ConvertToObject(\stdClass $objStd, InterfaceDTO $objetoRetorno)
    {
        try
        {
            $api = new ReflectionClass($objetoRetorno->GetClass());
            foreach ($api->getProperties() as $p)
            {
                $propExiste = true;
                
                $prop = $p->name;
                $propUc = ucwords($p->name);
                $propLower = ucwords($p->name);

                // verifica se a propriedade da classe existe no STD enviado (sem alterações)
                if(!isset($objStd->{$prop}))
                    $propExiste = false;
                
                // verifica se a propriedade da classe existe no STD enviado (Primeira letra maiuscula)
                if($propExiste == false && isset($objStd->{$propUc}))
                {
                    $prop = $propUc;
                    $propExiste = true;
                }
                
                // verifica se a propriedade da classe existe no STD enviado (todas minusculas)
                if($propExiste == false && isset($objStd->{$propLower}))
                {
                    $prop = $propLower;
                    $propExiste = true;
                }
                
                if(!$propExiste)
                    continue;

                $valorAdicionar = $objStd->{$prop};
                $valorAdicionar = $this->VerificaObjeto($valorAdicionar, $objetoRetorno, $prop);
                $valorAdicionar = $this->VeriricaArray($valorAdicionar, $objetoRetorno, $prop);

                $objetoRetorno->{$p->name} = $valorAdicionar;
            }

            return $objetoRetorno;
        }
        catch (Exception $ex)
        {
            Log::setLogErro("Erro ao mapear objeto " . $ex, "PedidoDto");
            throw $ex;
        }
    }

    private function VerificaObjeto($propriedadeStdClass, $objetoRetorno, $prop)
    {

        if (is_object($propriedadeStdClass))
        {
            $propriedadeStdClass = $this->ConvertToObject($propriedadeStdClass, $objetoRetorno->{$prop});
        }

        return $propriedadeStdClass;
    }

    private function VeriricaArray($propriedadeStdClass, $objetoRetorno, $prop)
    {
        if (is_array($propriedadeStdClass))
        {
            $cont = 0;
            foreach ($propriedadeStdClass as $item)
            {
                $valor = $item;

                if (is_object($item))
                {
                    $varTipoArray = $prop . "Array";
                    $class = $objetoRetorno->{$varTipoArray}->GetClass();
                    $obj = new $class();
                    $valor = $this->ConvertToObject($item, $obj);
                }

                $propriedadeStdClass[$cont] = $valor;
                $cont++;
            }
        }

        return $propriedadeStdClass;
    }

}
