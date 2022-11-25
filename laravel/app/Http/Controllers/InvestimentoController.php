<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriarInvestimentoRequest;
use App\Http\Requests\VisualizarInvestimentoRequest;
use App\Http\Response\CriarInvestimentoResponse;
use App\Http\Response\VisualizarInvestimentoResponse;
use App\Models\Investimento;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class InvestimentoController extends Controller
{
    public static function criarInvestimento(Request $request)
    {

        if (!CriarInvestimentoRequest::inputs($request) == false) {
            return CriarInvestimentoRequest::message(CriarInvestimentoRequest::inputs($request), true);
        } else {
            $dados['id'] = Investimento::insertInvestimentoNovo($request->all());
            $dados['code'] = CriarInvestimentoRequest::MESSAGE_INVESTIMENTO_CRIADO;

            return CriarInvestimentoResponse::responseSucesso($dados);
        }
    }

    public static function visualizarInvestimento(Request $request)
    {

        if (!VisualizarInvestimentoRequest::inputs($request) == false) {
            return VisualizarInvestimentoRequest::message(VisualizarInvestimentoRequest::inputs($request), true);
        } else {

            $investimentoVisualizar = Investimento::getInvestimentoId($request->investimentoId);

            if (empty($investimentoVisualizar->value('id'))) {
                $dados['investimentoId'] = $request->investimentoId;
                $dados['code'] = VisualizarInvestimentoRequest::MESSAGE_NAO_EXISTE_INVESTIMENTO_ID;

                return VisualizarInvestimentoResponse::responseErroInvestimentoIdNaoExiste($dados);
            } else {

                $dados['investimentoValor'] = $investimentoVisualizar->value('investimento_valor');
                $dados['investimentoId'] =  $investimentoVisualizar->value('id');
                $dados['code'] = VisualizarInvestimentoRequest::MESSAGE_VALOR_COM_GANHOS;
                $dados['saldoAtual'] =  InvestimentoController::calcularMontante($dados);

                // if ($investimentoVisualizar->value('investimento_saque') == 1) {
                //     return VisualizarInvestimentoResponse::responseErroInvestimentoJaSacado($dados);
                // } else {
                return VisualizarInvestimentoResponse::responseSucesso($dados);
                // }
            }
        }
    }


    public static function code($id)
    {
        for ($code = 1; $code < 100; $code++) {
            switch ($id) {
                case 'criar':
                    $message = CriarInvestimentoRequest::message($code, false);
                    break;
                case 'visualizar':
                    $message = VisualizarInvestimentoRequest::message($code, false);
                    break;
            }
            if (empty($message))  break;
            $codes[] = ["code" => $code, "message" => $message];
        }
        return response()->json(["success" =>  true, "codeList" => $codes], 200);
    }


    public static function calcularMontante($dados)
    {
        $investimentoVisualizar = Investimento::getInvestimentoId($dados['investimentoId']);

        $dataDoInvestimento = $investimentoVisualizar->value('investimento_data_entrada');
        $dataAtual = Carbon::now()->format('Y-m-d');
        if ($investimentoVisualizar->value('investimento_saque') == 1)
            $dataAtual = $investimentoVisualizar->value('investimento_data_saida'); 

        $diferencaDatas = strtotime($dataAtual) - strtotime($dataDoInvestimento);
        $mesesPassados = floor(($diferencaDatas / (60 * 60 * 24)) / 30);

        $taxaDeJuros = 10;
        $investimentoAcumulado = $dados['investimentoValor'];
        $jurosCompostosTotal = 0;

        for ($i = 0; $i < $mesesPassados; $i++) {
            $jurosCompostos = ($investimentoAcumulado * $taxaDeJuros) / 100;
            $jurosCompostosTotal += $jurosCompostos;
            $investimentoAcumulado += $jurosCompostos;
        }
        $montante = $dados['investimentoValor'] + $jurosCompostosTotal;

        return $montante;
    }
}
