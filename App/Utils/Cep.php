<?php

namespace App\Utils;

use SimpleXMLElement;

/**
 * Description of Cep : Pesquisa endereço via Cep
 * @author luksf
 */
class Cep {
    
    private static $cep;
    private static $logradouro;
    private static $complemento;
    private static $bairro;
    private static $nomeCidade;
    private static $uf;
    private static $ibge;
    
    private const URL = "https://viacep.com.br/ws/CEPAQUI/xml/";
    
    static function getCep() {
        return self::$cep;
    }

    static function getLogradouro() {
        return self::$logradouro;
    }

    static function getComplemento() {
        return self::$complemento;
    }

    static function getBairro() {
        return self::$bairro;
    }

    static function getNomeCidade() {
        return self::$nomeCidade;
    }

    static function getUf() {
        return self::$uf;
    }

    static function getIbge() {
        return self::$ibge;
    }

        
    public static function setEndereco(string $cep) : SimpleXMLElement{
        $cep = Formata::somenteNumeros($cep);
        $url = str_replace("CEPAQUI", $cep, self::URL);
        
        $xml = simplexml_load_file($url);
        
        self::$cep = $cep;
        self::$bairro = $xml->bairro;
        self::$complemento = $xml->complemento;
        self::$ibge = $xml->ibge;
        self::$logradouro = $xml->logradouro;
        self::$nomeCidade = $xml->localidade;
        
    }
    
}
