<?php

namespace App\Utils;

/**
 * Description of Formata Formatação de data, cep, cnpj, cpf, etc
 *
 * @author Lucas
 */
class Formata {

    public static function getLike($valor) {
        return str_replace(" ", "%", trim($valor)) . "%";
    }

    public static function somenteNumeros($string): string {
        return preg_replace("/[^0-9]/", "", $string);
    }

    public static function padLeft($string, $padLenght, $valorPad = "0") {
        return str_pad($string, $padLenght, $valorPad, STR_PAD_LEFT);
    }

    public static function padRight($string, $padLenght, $valorPad = "0") {
        return str_pad($string, $padLenght, $valorPad, STR_PAD_RIGHT);
    }

    /**
     * @param STRING $str :: Passe o CNPJ, CPF ou CEP
     * @return String :: Retorna CPF, CNPJ ou CEP com pontos e traços */
    public static function cnpjCpfCep($str): string {
        $str = self::somenteNumeros($str);
        $var = '';
        switch (strlen($str)) {
            case 11:
                for ($i = 0; $i < 11; $i++) {
                    if ($i == 2) {
                        $var .= $str[$i] . ".";
                    } else if ($i == 5) {
                        $var .= $str[$i] . ".";
                    } else if ($i == 8) {
                        $var .= $str[$i] . "-";
                    } else {
                        $var .= $str[$i];
                    }
                }
                break;

            case 14:
                for ($i = 0; $i < 14; $i++) {
                    if ($i == 1) {
                        $var .= $str[$i] . ".";
                    } else if ($i == 4) {
                        $var .= $str[$i] . ".";
                    } else if ($i == 7) {
                        $var .= $str[$i] . "/";
                    } else if ($i == 11) {
                        $var .= $str[$i] . "-";
                    } else {
                        $var .= $str[$i];
                    }
                }
                break;

            case 8:
                for ($i = 0; $i < 8; $i++) {
                    if ($i == 4) {
                        $var .= $str[$i] . "-";
                    } else {
                        $var .= $str[$i];
                    }
                }
                break;
        }
        return $var;
    }

    public static function toReal($valor, $soNumero = false): string {

        $valor = empty($valor) ? 0 : $valor;

        // para garantir numero
        try {
            if (strpos($valor, ",") >= 1)
                $valor = Formata::toDecimalBd($valor);

            $valor = round($valor * 1, 2);
        } catch (Exception $ex) {
            $valor = 0;
        }


        if (is_double($valor) || is_numeric($valor)) {
            return ((!$soNumero) ? "R$ " : "") . number_format($valor, 2, ',', '.');
        } else {
            return ((!$soNumero) ? "R$ " : "") . " 0.00";
        }
    }

    public static function toDollar($valorEmReais, $soNumero = false): string {
        if (empty(valorEmReais == "")) {
            return 0;
        } else {
            $valor = str_replace(".", "", $valorEmReais);
            $valor = str_replace(",", ".", $valor);
            return ((!$soNumero) ? "$ " : "") . $valor;
        }
    }

    public static function toDecimalBd($decimalBrasil): string {
        if (empty(trim($decimalBrasil))) {
            return 0;
        } else {
            if (strpos($decimalBrasil, ",") >= 1) {
                $decimalBrasil = str_replace(".", "", $decimalBrasil);
                $decimalBrasil = str_replace(",", ".", $decimalBrasil);
            }
            return $decimalBrasil;
        }
    }

    public static function toDecimalPagSeguro($decimalBrasil) {
        if (empty(trim($decimalBrasil))) {
            return number_format(0, 2, ".", "");
        } else {
            if (strpos($decimalBrasil, ",") >= 1) {
                $decimalBrasil = str_replace(".", "", $decimalBrasil);
                $decimalBrasil = str_replace(",", ".", $decimalBrasil);
            }
            $decimalBrasil = (float) $decimalBrasil;
            return number_format($decimalBrasil, 2, ".", "");
        }
    }

    /** Transform o array em string dependedo do separador
     * @param type $array : array a ser transformado
     * @param type $separadorString : separador para os campos da string
     * @return string
     */
    public static function arrayToString($array, $separadorString = '|'): string {
        if (is_string($array)) {
            return $array;
        }
        if (!is_array($array) || count($array) == 0) {
            return "";
        }
        $string = implode($separadorString, $array);
        return $string;
    }

    public static function stringToArray($string, $separadorString): array {
        $array = explode($separadorString, $string);
        return $array;
    }

    public static function getBool($valor) {
        if (is_numeric($valor)) {
            if ($valor <> "0" && $valor <> 0) {
                return true;
            } else {
                return false;
            }
        } else
            return false;
    }

    public static function removeAcento($string) {
        return strtolower(preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string));
    }

    /**
     * Retorna primeiro nome de uma string
     * @param string $string
     * @return string
     */
    public static function getPrimeiroNome(string $string): string {
        return ucfirst(mb_substr($string, 0, mb_strpos($string, ' ')));
    }

    /**
     * Retorna o sobrenome de um string
     * @param string $string
     * @return string
     */
    public static function getSobreNome(string $string): string {
        // pego a quantidade de caracteres do nome
        $nome = mb_strlen(mb_substr($string, 0, mb_strpos($string, ' ')));
        return mb_substr($string, $nome, mb_strlen($string));
    }

}
