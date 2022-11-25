<?php

namespace App\Http\Requests;

use App\Http\Controllers\InvestimentoController;

class RetirarInvestimentoRequest
{
    const MESSAGE_CAMPO_INVESTIMENTO_ID_VAZIO = 1;
    const MESSAGE_CAMPO_DATA_VAZIO = 2;
    const MESSAGE_DATA_INVALIDA = 3;
    const MESSAGE_NAO_EXISTE_INVESTIMENTO_ID = 4;
    const MESSAGE_NAO_DATA_SAIDA_INFERIOR_DATA_ENTRADA_INVESTIMENTO_ID = 5;
    const MESSAGE_INVESTIMENTO_JA_SACADO = 6;
    const MESSAGE_INVESTIMENTO_SACADO_COM_SUCESSO = 7;
    const STATUS_CODE_FAIL = 422;

    public static function inputs($request)
    {

        if (empty($request->investimentoId)) {
            return RetirarInvestimentoRequest::MESSAGE_CAMPO_INVESTIMENTO_ID_VAZIO;
        }

        if (empty($request->investimentoDataRetirada)) {
            return RetirarInvestimentoRequest::MESSAGE_CAMPO_DATA_VAZIO;
        }
        if (!InvestimentoController::validarData($request->investimentoDataRetirada)) {
            return RetirarInvestimentoRequest::MESSAGE_DATA_INVALIDA;
        }

        return false;
    }

    public static function message($code, $id = false, $dados = [])
    {
        if (!$dados) {
            $dados['investimentoId']  = "X";
            $dados['investimentoDataCriacao']  = "XXXX-XX-XX";
            $dados['montanteTotal']  = "XX,XX";
        }
        switch ($code) {
            case RetirarInvestimentoRequest::MESSAGE_CAMPO_INVESTIMENTO_ID_VAZIO:
                $message = "O Input [investimentoId] não foi Informado ou está vazio.";
                break;

            case RetirarInvestimentoRequest::MESSAGE_CAMPO_DATA_VAZIO:
                $message = "O Input [investimentoDataRetirada] não foi Informado ou está vazio.";
                break;

            case RetirarInvestimentoRequest::MESSAGE_DATA_INVALIDA:
                $message = "O Input [investimentoDataRetirada] inválida digite uma corretamente (ANO-MES-DIA).";
                break;

            case RetirarInvestimentoRequest::MESSAGE_NAO_EXISTE_INVESTIMENTO_ID:
                $message = "O investimentoId " . $dados['investimentoId'] . " não existe.";
                break;

            case RetirarInvestimentoRequest::MESSAGE_NAO_DATA_SAIDA_INFERIOR_DATA_ENTRADA_INVESTIMENTO_ID:
                $message = "A  Data de retirada do investimento não pode ser inferior a data da criação do investimento " . $dados['investimentoDataCriacao'] . ".";
                break;

            case RetirarInvestimentoRequest::MESSAGE_INVESTIMENTO_JA_SACADO:
                $message = "Atenção esse investimento já foi sacado.";
                break;

            case RetirarInvestimentoRequest::MESSAGE_INVESTIMENTO_SACADO_COM_SUCESSO:
                $message = "Investimento sacado com sucesso, o montante(investimento+ganhos) foi de : R$ " . $dados['montanteTotal'] . ".";
                break;

            default:
                $message = null;
                break;
        }

        if ($id == true) {
            return response()->json(["success" =>  false, "code" => $code, "message" => $message],   RetirarInvestimentoRequest::STATUS_CODE_FAIL);
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
