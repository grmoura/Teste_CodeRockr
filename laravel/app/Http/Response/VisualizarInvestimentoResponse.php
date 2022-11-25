<?php

namespace App\Http\Response;

use App\Http\Requests\VisualizarInvestimentoRequest;

class VisualizarInvestimentoResponse
{
    const STATUS_CODE_SUCESSO = 200;

    public static function responseSucesso($dados)
    {
        $code = $dados['code'];
        $investimentoValor = VisualizarInvestimentoResponse::formatarValorEmReais($dados['investimentoValor']);
        $saldoAtual = VisualizarInvestimentoResponse::formatarValorEmReais($dados['saldoAtual']);
        $dados['saldoAtual'] = $saldoAtual;
        $investimentoId = $dados['investimentoId'];
        $message = VisualizarInvestimentoRequest::message($code, false, $dados);

        $response = response()->json(
            [
                "success"               => true,
                "code"                  => $code,
                "message"               => $message,
                "investimentoId"        => $investimentoId,
                "investimentoValor"     => $investimentoValor,
                "saldoAtual"            => $saldoAtual,
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

    public static function responseErroInvestimentoJaSacado($dados)
    {
        $code = $dados['code'];
        $investimentoValor = VisualizarInvestimentoResponse::formatarValorEmReais($dados['investimentoValor']);
        $saldoAtual = VisualizarInvestimentoResponse::formatarValorEmReais($dados['saldoAtual']);
        $dados['saldoAtual'] = $saldoAtual;
        $investimentoId = $dados['investimentoId'];
        $message = VisualizarInvestimentoRequest::message($code, false, $dados);

        $response = response()->json(
            [
                "success"               => true,
                "code"                  => $code,
                "message"               => $message,
                "investimentoId"        => $investimentoId,
                "investimentoValor"     => $investimentoValor,
                "saldoAtual"            => $saldoAtual,
            ],
            VisualizarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }


    public static function formatarValorEmReais($value)
    {
        $quantidade = str_replace('.', '', number_format($value, 2, ',', '.'));
        $quantidade = str_replace('.', ',', $quantidade);
        return $quantidade;
    }
}
