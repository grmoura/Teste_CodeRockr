<?php

namespace App\Http\Requests;

class CriarInvestimentoRequest
{
    const MESSAGE_CAMPO_INVESTIDOR_VAZIO = 1;
    const MESSAGE_CAMPO_VALOR_VAZIO = 2;
    const MESSAGE_CAMPO_DATA_VAZIO = 3;
    const MESSAGE_VALOR_SO_NUMERO = 4;
    const MESSAGE_VALOR_MAIOR_ZERO = 5;
    const MESSAGE_DATA_INVALIDA = 6;
    const MESSAGE_INVESTIMENTO_CRIADO = 7;
    const STATUS_CODE_FAIL = 422;
  
    public static function inputs($request)
    {
       
        if (empty($request->investidorNome)) {
            return CriarInvestimentoRequest::MESSAGE_CAMPO_INVESTIDOR_VAZIO;
        }

        if (empty($request->investimentoValor)) {
            return CriarInvestimentoRequest::MESSAGE_CAMPO_VALOR_VAZIO;
        }
        if (empty($request->investimentoData)) {
            return CriarInvestimentoRequest::MESSAGE_CAMPO_DATA_VAZIO;
        }
        if (preg_match("/^[0-9\.\,\d]+$/", $request->investimentoValor) == 0) {
            return CriarInvestimentoRequest::MESSAGE_VALOR_SO_NUMERO;
        }
        if ($request->investimentoValor <= 0) {
            return CriarInvestimentoRequest::MESSAGE_VALOR_MAIOR_ZERO;
        }

        if (!CriarInvestimentoRequest::validarData($request->investimentoData)) {
            return CriarInvestimentoRequest::MESSAGE_DATA_INVALIDA;
        }

        return false;
    }

    public static function message($code, $id = false)
    {
        switch ($code) {
            case CriarInvestimentoRequest::MESSAGE_CAMPO_INVESTIDOR_VAZIO:
                $message = "O Input [investidorNome] não foi Informado ou está vazio.";
                break;
            case CriarInvestimentoRequest::MESSAGE_CAMPO_VALOR_VAZIO:
                $message = "O Input [investimentoValor] não foi Informado ou está vazio.";
                break;
            case CriarInvestimentoRequest::MESSAGE_CAMPO_DATA_VAZIO:
                $message = "O Input [investimentoData] não foi Informado ou está vazio.";
                break;
            case CriarInvestimentoRequest::MESSAGE_VALOR_SO_NUMERO:
                $message = "O Input [investimentoValor] só é permitido números.";
                break;
            case CriarInvestimentoRequest::MESSAGE_VALOR_MAIOR_ZERO:
                $message = "O Input [investimentoValor] deve ser maior que 0.00 BRL.";
                break;
            case CriarInvestimentoRequest::MESSAGE_DATA_INVALIDA:
                $message = "O Input [investimentoData] inválida digite uma corretamente (ANO-MES-DIA).";
                break;
            case CriarInvestimentoRequest::MESSAGE_INVESTIMENTO_CRIADO:
                $message = "Parabéns seu investimento foi criado.";
                break;

            default:
                $message = null;
                break;
        }

        if ($id == true) {
            return response()->json(["success" =>  false, "code" => $code, "message" => $message],   CriarInvestimentoRequest::STATUS_CODE_FAIL);
        } else {
            return $message;
        }
    }

    public static function validarData($data)
    {
        $data = explode('-', $data);
        return checkdate($data[1], $data[2], $data[0]);
    }
}
