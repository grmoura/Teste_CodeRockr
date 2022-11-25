<?php

namespace App\Http\Requests;

class ListarInvestimentoRequest
{
    const MESSAGE_CAMPO_INVESTIDOR_VAZIO = 1;
    const MESSAGE_INVESTIDOR_NAO_EXISTE = 2;
    const MESSAGE_INVESTIDOR_LISTA = 4;
    const STATUS_CODE_FAIL = 422;

    public static function inputs($request)
    {

        if (empty($request->investidorNome)) {
            return ListarInvestimentoRequest::MESSAGE_CAMPO_INVESTIDOR_VAZIO;
        }

        return false;
    }

    public static function message($code, $id = false, $dados = [])
    {

        switch ($code) {
            case ListarInvestimentoRequest::MESSAGE_CAMPO_INVESTIDOR_VAZIO:
                $message = "O Input [investidorNome] não foi Informado ou está vazio.";
                break;

            case ListarInvestimentoRequest::MESSAGE_INVESTIDOR_NAO_EXISTE:
                $message = "Esse investidor não existe.";
                break;

                case ListarInvestimentoRequest::MESSAGE_INVESTIDOR_LISTA:
                    $message = "Lista do investidor carregada com sucesso.";
                    break;

            default:
                $message = null;
                break;
        }

        if ($id == true) {
            return response()->json(["success" =>  false, "code" => $code, "message" => $message],   ListarInvestimentoRequest::STATUS_CODE_FAIL);
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
