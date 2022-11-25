<?php

namespace App\Http\Response;

use App\Http\Controllers\InvestimentoController;
use App\Http\Requests\VisualizarInvestimentoRequest;
use App\Models\Investimento;

class VisualizarInvestimentoResponse
{
    const STATUS_CODE_SUCESSO = 200;

    public static function responseSucesso($dados)
    {

        $investimentoVisualizar = Investimento::getInvestimentoId($dados['investimentoId']);
        $investimentoValor = InvestimentoController::formatarValorEmReais($investimentoVisualizar->value('investimento_valor'));
        $saldoAtual = InvestimentoController::formatarValorEmReais(InvestimentoController::calcularMontante($dados));
        $dados['saldoAtual'] = $saldoAtual;
        $investimentoId = $investimentoVisualizar->value('id');

        $code = $dados['code'];
        $message = VisualizarInvestimentoRequest::message($code, false, $dados);

        $situacaoInvestimento = 'Ativo(rendendo)';
        if ($investimentoVisualizar->value('investimento_saque'))
            $situacaoInvestimento = 'Inativo(sacado)';


        $investimento = [
                "investimentoId"        => $investimentoId,
                "situacaoInvestimento"  => $situacaoInvestimento,
                "investimentoValor"     => $investimentoValor,
                "saldoAtual"            => $saldoAtual
        ];

        $response = response()->json(
            [
                "success"               => true,
                "code"                  => $code,
                "message"               => $message,
                "investimentoDados"          => $investimento,
            ],
            VisualizarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }

    public static function responseErroInvestimentoIdNaoExiste($dados)
    {
        $message = VisualizarInvestimentoRequest::message($dados['code'], false, $dados);
        $code = $dados['code'];

        $response = response()->json(
            [
                "success"               => false,
                "code"                  => $code,
                "message"               => $message
            ],
            VisualizarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }
}
