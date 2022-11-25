<?php

namespace App\Http\Response;

use App\Http\Controllers\InvestimentoController;
use App\Http\Requests\ListarInvestimentoRequest;
use App\Models\Investimento;

class ListarInvestimentoResponse
{
    const STATUS_CODE_SUCESSO = 200;

    public static function responseSucesso($dados)
    {
        $code = $dados['code'];

        foreach (Investimento::getListInvestidorName($dados['investidorNome']) as $key => $value) {

            $dados['investimentoId'] =  $value->id;

            if ($value->investimento_saque == 1) {
                $investimentoDataInicio = $value->investimento_data_entrada;
                $investimentoDataSaque = $value->investimento_data_saida;
                $situacaoInvestimento = 'Inativo(sacado)';
            } else {
                $investimentoDataInicio = $value->investimento_data_entrada;
                $investimentoDataSaque = '';
                $situacaoInvestimento = 'Ativo(rendendo)';
            }

            $investimentoValor = InvestimentoController::formatarValorEmReais($value->investimento_valor);
            $saldoAtual = InvestimentoController::formatarValorEmReais(InvestimentoController::calcularMontante($dados));
            $list[] = [
                "investimentoId"                => $value->id,
                "investimentoDataInicio"        => $investimentoDataInicio,
                "investimentoDataFechamento"    => $investimentoDataSaque,
                "situacaoInvestimento"          => $situacaoInvestimento,
                "investimentoValor"             => $investimentoValor,
                "saldoAtual"                    => $saldoAtual,
            ];
        }

        $message = ListarInvestimentoRequest::message($code, false, $dados);
        $response = response()->json(
            [
                "success"               => true,
                "code"                  => $code,
                "message"               => $message,
                "listaDeInvestimentos" => $list

            ],
            ListarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }

    public static function responseErroInvestimentoIdNaoExiste($dados)
    {
        $message = ListarInvestimentoRequest::message($dados['code'], false, $dados);
        $code = $dados['code'];

        $response = response()->json(
            [
                "success"               => false,
                "code"                  => $code,
                "message"               => $message
            ],
            ListarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }
}
