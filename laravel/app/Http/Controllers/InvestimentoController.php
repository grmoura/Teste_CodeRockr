<?php

namespace App\Http\Controllers;

use App\Http\Requests\CriarInvestimentoRequest;
use App\Http\Requests\ListarInvestimentoRequest;
use App\Http\Requests\RetirarInvestimentoRequest;
use App\Http\Requests\VisualizarInvestimentoRequest;
use App\Http\Response\CriarInvestimentoResponse;
use App\Http\Response\ListarInvestimentoResponse;
use App\Http\Response\RetirarInvestimentoResponse;
use App\Http\Response\VisualizarInvestimentoResponse;
use App\Models\Investimento;
use Carbon\Carbon;
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
            $dados['investimentoId'] = $request->investimentoId;

            if (empty($investimentoVisualizar->value('id'))) {

                $dados['code'] = VisualizarInvestimentoRequest::MESSAGE_NAO_EXISTE_INVESTIMENTO_ID;

                return VisualizarInvestimentoResponse::responseErroInvestimentoIdNaoExiste($dados);
            } else {

    
                $dados['code'] = VisualizarInvestimentoRequest::MESSAGE_VALOR_COM_GANHOS;
                $dados['saldoAtual'] =  InvestimentoController::calcularMontante($dados);

                return VisualizarInvestimentoResponse::responseSucesso($dados);
            }
        }
    }


    public static function retirarInvestimento(Request $request)
    {

        if (!RetirarInvestimentoRequest::inputs($request) == false) {

            return RetirarInvestimentoRequest::message(RetirarInvestimentoRequest::inputs($request), true);
        } else {

            $investimentoVisualizar = Investimento::getInvestimentoId($request->investimentoId);

            if (empty($investimentoVisualizar->value('id'))) {
                $dados['investimentoId'] = $request->investimentoId;
                $dados['code'] = RetirarInvestimentoRequest::MESSAGE_NAO_EXISTE_INVESTIMENTO_ID;

                return RetirarInvestimentoResponse::responseErroInvestimentoIdNaoExiste($dados);
            } else {

                $investimentoDataCriacao =  $investimentoVisualizar->value('investimento_data_entrada');

                $dados['investimentoSaque'] = $investimentoVisualizar->value('investimento_saque');
                $dados['investimentoValor'] = $investimentoVisualizar->value('investimento_valor');
                $dados['investimentoId'] =  $investimentoVisualizar->value('id');
                $dados['investimentoDataRetirada'] = $request->investimentoDataRetirada;

                if ($dados['investimentoDataRetirada'] < $investimentoDataCriacao) {

                    $dados['investimentoDataCriacao'] =  $investimentoDataCriacao;
                    $dados['code'] = RetirarInvestimentoRequest::MESSAGE_NAO_DATA_SAIDA_INFERIOR_DATA_ENTRADA_INVESTIMENTO_ID;

                    return RetirarInvestimentoResponse::responseErroDataRetiradaInferior($dados);
                } elseif ($investimentoVisualizar->value('investimento_saque') == 1) {

                    $dados['code'] = RetirarInvestimentoRequest::MESSAGE_INVESTIMENTO_JA_SACADO;
                    
                    return RetirarInvestimentoResponse::responseErroInvestimentoJaSacado($dados);
                } else {

                    $dados['code'] = RetirarInvestimentoRequest::MESSAGE_INVESTIMENTO_SACADO_COM_SUCESSO;
                  
                    Investimento::updateInvestimentoId($dados);
                    return RetirarInvestimentoResponse::responseSucesso($dados);
                }
            }
        }
    }


    public static function listarInvestimento(Request $request)
    {

        if (!ListarInvestimentoRequest::inputs($request) == false) {

            return ListarInvestimentoRequest::message(ListarInvestimentoRequest::inputs($request), true);
        } else {

            $investimentoVisualizar = Investimento::getListInvestidorName($request->investidorNome);
            $dados['investidorNome'] = $request->investidorNome;
      
            if (empty($investimentoVisualizar->value('id'))) {

                $dados['code'] = ListarInvestimentoRequest::MESSAGE_INVESTIDOR_NAO_EXISTE;

                return ListarInvestimentoResponse::responseErroInvestimentoIdNaoExiste($dados);

            } else {
                
                $dados['code'] = ListarInvestimentoRequest::MESSAGE_INVESTIDOR_LISTA;

                return ListarInvestimentoResponse::responseSucesso($dados);
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
                case 'retirar':
                    $message = RetirarInvestimentoRequest::message($code, false);
                    break;

                case 'listar':
                    $message = ListarInvestimentoRequest::message($code, false);
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

        $taxaDeJuros = 0.52;
        $investimentoAcumulado = $investimentoVisualizar->value('investimento_valor');
        $jurosCompostosTotal = 0;

        for ($i = 0; $i < $mesesPassados; $i++) {
            $jurosCompostos = ($investimentoAcumulado * $taxaDeJuros) / 100;
            $jurosCompostosTotal += $jurosCompostos;
            $investimentoAcumulado += $jurosCompostos;
        }
        $montante = $investimentoVisualizar->value('investimento_valor') + $jurosCompostosTotal;

        return $montante;
    }

    public static function validarData($data)
    {
        $data = explode('-', $data);
        return checkdate($data[1], $data[2], $data[0]);
    }


    public static function formatarValorEmReais($value)
    {
        $quantidade = str_replace('.', '', number_format($value, 2, ',', '.'));
        $quantidade = str_replace('.', ',', $quantidade);
        return $quantidade;
    }
}
