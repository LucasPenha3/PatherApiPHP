<?php

namespace App\Utils;

use Exception;

/**
 * Description of Log : Gravação de logs do sistema
 * @author luksf
 */
class Log
{

    private static $pasta = "/logs";

    private static function getPastaLogs(): string
    {
        $array = explode("App", __DIR__);
        $local = $array[0] . "App" . self::$pasta;
        return $local;
    }

    /**
     * @param string $msgErro : Mensagem de erro personalizada
     * @param type $e : Excessão 
     * @param string $localErro : Onde aconteceu o erro (cadastros, venda, email, etc)
     */
    public static function setLogErro($msgErro, $e, $localErro)
    {

        $localErro = str_replace(" ", "_", trim($localErro));

        $msgComplete = "";
        if ($e)
        {
            $msgComplete = "\n Excessão: " . $e->getMessage() . " | Código: " . $e->getCode() . " | Linha " . $e->getLine() . " | File: " . $e->getFile();
        }
        Data::setTimeZone();
        $dateErro = date('Y-m-d');
        $horaErro = date('Y-m-d-H-i');
        $erroTxt = "\n\r" . $horaErro . "\n-----------------------------\nErro: " . $erro . $msgComplete;
        $handle = fopen(self::getPastaLogs() . "/{$localErro}_logErro_{$dateErro}.txt", "a+");
        if ($handle)
        {
            fwrite($handle, $erroTxt);
            fclose($handle);
        }
    }

    /**
     * @param string $mensagem : Mensagem de erro personalizada
     * @param string $localErro : Onde aconteceu o erro (cadastros, venda, email, etc)
     */
    public static function setLog($mensagem, $localErro)
    {
        try
        {
            $localErro = str_replace(" ", "_", trim($localErro));
            Data::setTimeZone();
            $dateErro = date('Y-m-d');
            $horaErro = date('Y-m-d-H-i');
            $erroTxt = "\n\r" . $horaErro . "\n-----------------------------\nErro: " . $mensagem;
            $handle = fopen(self::getPastaLogs() . "/{$localErro}_logErro_{$dateErro}.txt", "a+");
            if ($handle)
            {
                fwrite($handle, $erroTxt);
                fclose($handle);
            }
        }
        catch (Exception $ex)
        {
            
        }
    }
}

