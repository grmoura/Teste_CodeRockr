<?php

namespace App\Http\Response;

use App\Http\Controllers\InvestimentoController;
use App\Http\Requests\RetirarInvestimentoRequest;


class RetirarInvestimentoResponse
{
    const STATUS_CODE_SUCESSO = 200;

    public static function responseSucesso($dados)
    {
        $code = $dados['code'];
        $montanteTotal = InvestimentoController::formatarValorEmReais(InvestimentoController::calcularMontante($dados));

        $dados['montanteTotal'] = $montanteTotal;
        $investimentoId = $dados['investimentoId'];
        $message = RetirarInvestimentoRequest::message($code, false, $dados);


        $investimento = [
            "investimentoId"        => $investimentoId,
            "saqueTotal"            => $montanteTotal,
        ];

        $response = response()->json(
            [
                "success"               => true,
                "code"                  => $code,
                "message"               => $message,
                "investimentoDados"          => $investimento
            ],
            RetirarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }

    public static function responseErroDataRetiradaInferior($dados)
    {
        $message = RetirarInvestimentoRequest::message($dados['code'], false, $dados);
        $code = $dados['code'];

        $response = response()->json(
            [
                "success"               => false,
                "code"                  => $code,
                "message"               => $message
            ],
            RetirarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }


    public static function responseErroInvestimentoIdNaoExiste($dados)
    {
        $message = RetirarInvestimentoRequest::message($dados['code'], false, $dados);
        $code = $dados['code'];

        $response = response()->json(
            [
                "success"               => false,
                "code"                  => $code,
                "message"               => $message
            ],
            RetirarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }

    public static function responseErroInvestimentoJaSacado($dados)
    {

        $code = $dados['code'];
        $saldoAtual = InvestimentoController::formatarValorEmReais(InvestimentoController::calcularMontante($dados));
        $investimentoId = $dados['investimentoId'];
        $message = RetirarInvestimentoRequest::message($code, false);


        $investimento = [
            "investimentoId"        => $investimentoId,
            "saldoRetornado"        => $saldoAtual,
        ];
        $response = response()->json(
            [
                "success"               => true,
                "code"                  => $code,
                "message"               => $message,
                "investimentoDados"     => $investimento
            ],
            VisualizarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }
}
