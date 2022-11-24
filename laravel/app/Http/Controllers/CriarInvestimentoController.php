<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriarInvestimentoRequest;
use App\Http\Response\CriarInvestimentoResponse;
use App\Models\Investimento;
use Illuminate\Http\Request;

class CriarInvestimentoController extends Controller
{
    public static function criarInvestimento(Request $request)
    {

        if (!CriarInvestimentoRequest::inputs($request) == false) {
            return CriarInvestimentoRequest::message(CriarInvestimentoRequest::inputs($request), true);
        } else {
            $dados['id'] = Investimento::insertInvestimentoNovo($request->all());
            $dados['code'] = CriarInvestimentoRequest::MESSAGE_INVESTIMENTO_CRIADO;

            return CriarInvestimentoResponse::response($dados);
        }
        // $teste = Investimento::getInvestimentoId(1)->get();
        // return response()->json($teste, 200);
    }


    public static function code()
    {
        for ($code = 1; $code < 100; $code++) {
            $message = CriarInvestimentoRequest::message($code, false);
            if (empty($message))  break;
            $codes[] = ["code" => $code, "message" => $message];
        }
        return response()->json(["success" =>  true, "codeList" => $codes], 200);
    }
}
