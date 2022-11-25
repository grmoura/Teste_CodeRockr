<?php

namespace App\Http\Response;

use App\Http\Requests\CriarInvestimentoRequest;

class CriarInvestimentoResponse
{
    const STATUS_CODE_SUCESSO = 200;

    public static function responseSucesso($dados)
    {
        $message =  CriarInvestimentoRequest::message($dados['code'], false);
        $investimentoId = $dados['id'];
        $code = $dados['code'];
        $response = response()->json(
            [
                "success"               => true,
                "investimentoId"        => $investimentoId,
                "code"                  => $code,
                "message"               => $message
            ],
            CriarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }
}
