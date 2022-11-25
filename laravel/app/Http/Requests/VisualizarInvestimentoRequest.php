<?php

namespace App\Http\Requests;

class VisualizarInvestimentoRequest
{
    const MESSAGE_CAMPO_INVESTIMENTO_ID_VAZIO = 1;
    const MESSAGE_VALOR_COM_GANHOS = 2;
    const MESSAGE_NAO_EXISTE_INVESTIMENTO_ID = 3;
    const MESSAGE_NAO_EXISTE_INVESTIMENTO_JA_SACADO = 4;
    const STATUS_CODE_FAIL = 422;

    public static function inputs($request)
    {

        if (empty($request->investimentoId)) {
            return VisualizarInvestimentoRequest::MESSAGE_CAMPO_INVESTIMENTO_ID_VAZIO;
        }

        return false;
    }

    public static function message($code, $id = false, $dados = [])
    {
        if (!$dados) {
            $dados['valorMontante'] = 1;
            $dados['investimentoId']  = 1;
        }
        switch ($code) {
            case VisualizarInvestimentoRequest::MESSAGE_CAMPO_INVESTIMENTO_ID_VAZIO:
                $message = "O Input [investimentoId] não foi Informado ou está vazio.";
                break;

            case VisualizarInvestimentoRequest::MESSAGE_VALOR_COM_GANHOS:
                $message = "O montante(investimento+ganhos) até o momento presente é R$ : " . $dados['saldoAtual'] . ".";
                break;

            case VisualizarInvestimentoRequest::MESSAGE_NAO_EXISTE_INVESTIMENTO_ID:
                $message = "O investimentoId " . $dados['investimentoId'] . " não existe.";
                break;

            default:
                $message = null;
                break;
        }

        if ($id == true) {
            return response()->json(["success" =>  false, "code" => $code, "message" => $message],   VisualizarInvestimentoRequest::STATUS_CODE_FAIL);
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
