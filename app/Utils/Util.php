<?php

namespace App\Utils;

use Carbon\Carbon;
use Exception;

class Util
{
    public static function convertMessageErrorFormRequest(array $messages)
    {
        $message = '';

        foreach ($messages as $key => $value) 
            $message .= $key.": ".$value[0].";";
        
        return $message;
    }

    public static function getStatusCode($code)
    {
        return $code > 1 ? ($code > 500 ? 500 : $code) : 404;
    }

    public static function formatCpfCnpj(string $document): string
    {
        $cpf_cnpj = preg_replace("/[^0-9]/", "", $document);
        $tipo_dado = NULL;

        if(strlen($cpf_cnpj) == 11) $tipo_dado = "cpf";
        if(strlen($cpf_cnpj) == 14) $tipo_dado = "cnpj";

        switch($tipo_dado) {
            default:
                $cpf_cnpj_formatado = $document;
            break;

            case "cpf":
                $bloco_1 = substr($cpf_cnpj,0,3);
                $bloco_2 = substr($cpf_cnpj,3,3);
                $bloco_3 = substr($cpf_cnpj,6,3);
                $dig_verificador = substr($cpf_cnpj,-2);
                $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;
            break;

            case "cnpj":
                $bloco_1 = substr($cpf_cnpj,0,2);
                $bloco_2 = substr($cpf_cnpj,2,3);
                $bloco_3 = substr($cpf_cnpj,5,3);
                $bloco_4 = substr($cpf_cnpj,8,4);
                $digito_verificador = substr($cpf_cnpj,-2);
                $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;
            break;
        }

        return $cpf_cnpj_formatado;
    }

    public static function formatDataPt(string $date): string
    {
        $createdAt = Carbon::parse($date);
        return $createdAt->format('d/m/Y');
    }

    public static function formatDataEn()
    {
        //..
    }
}