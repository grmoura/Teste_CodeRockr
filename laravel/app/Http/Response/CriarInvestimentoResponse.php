<?php

namespace App\Http\Response;

use App\Http\Requests\CriarInvestimentoRequest;

class CriarInvestimentoResponse
{
    const STATUS_CODE_SUCESSO = 200;
    
    public static function response($dados)
    {
        $message =  CriarInvestimentoRequest::message($dados['code'], false);

        $response = response()->json([
            "success"   => true,
            "code"      => $dados['code'],
            "id"        => $dados['id'],
            "message"   => $message], 
            CriarInvestimentoResponse::STATUS_CODE_SUCESSO
        );

        return $response;
    }
}
