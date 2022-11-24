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

        if (empty($request->investidor)) {
            return CriarInvestimentoRequest::MESSAGE_CAMPO_INVESTIDOR_VAZIO;
        }

        if (empty($request->valor)) {
            return CriarInvestimentoRequest::MESSAGE_CAMPO_VALOR_VAZIO;
        }
        if (empty($request->data)) {
            return CriarInvestimentoRequest::MESSAGE_CAMPO_DATA_VAZIO;
        }
        if (preg_match("/^[0-9\.\,\d]+$/", $request->valor) == 0) {
            return CriarInvestimentoRequest::MESSAGE_VALOR_SO_NUMERO;
        }
        if ($request->valor <= 0) {
            return CriarInvestimentoRequest::MESSAGE_VALOR_MAIOR_ZERO;
        }

        if (!CriarInvestimentoRequest::validarData($request->data)) {
            return CriarInvestimentoRequest::MESSAGE_DATA_INVALIDA;
        }

        return false;
    }

    public static function message($code, $id = false)
    {
        switch ($code) {
            case CriarInvestimentoRequest::MESSAGE_CAMPO_INVESTIDOR_VAZIO:
                $message = "O Input [investidor] não foi Informado ou está vazio.";
                break;
            case CriarInvestimentoRequest::MESSAGE_CAMPO_VALOR_VAZIO:
                $message = "O Input [valor] não foi Informado ou está vazio.";
                break;
            case CriarInvestimentoRequest::MESSAGE_CAMPO_DATA_VAZIO:
                $message = "O Input [data] não foi Informado ou está vazio.";
                break;
            case CriarInvestimentoRequest::MESSAGE_VALOR_SO_NUMERO:
                $message = "O Input [value] só é permitido números.";
                break;
            case CriarInvestimentoRequest::MESSAGE_VALOR_MAIOR_ZERO:
                $message = "O Input [value] deve ser maior que 0.00 BRL.";
                break;
            case CriarInvestimentoRequest::MESSAGE_DATA_INVALIDA:
                $message = "O Input [data] inválida digite uma corretamente (ANO-MES-DIA).";
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
