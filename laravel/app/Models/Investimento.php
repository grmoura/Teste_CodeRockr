<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Investimento
{
    const EXCLUIDO_SIM = 1;
    const EXCLUIDO_NAO = 0;

    public static function table()
    {
        $table = DB::table('investimentos');
        return $table;
    }

    public static function getInvestimentoId($id)
    {
        $getInvestimentoId = Investimento::table()->select('id', 'investidor_nome', 'investimento_valor', 'investimento_data_entrada','investimento_saque','investimento_data_saida')->where(['id' => $id, 'excluido' => Investimento::EXCLUIDO_NAO])->get();
        return $getInvestimentoId;
    }

    public static function insertInvestimentoNovo($dados)
    {
        $insert['investimento_valor'] = $dados['investimentoValor'];
        $insert['investidor_nome'] = $dados['investidorNome'];
        $insert['investimento_data_entrada'] = $dados['investimentoData'];
        unset($dados);
        Investimento::table()->insert($insert);
        return Investimento::getInsertUltimo();
    }

    public static function getInsertUltimo()
    {
      return   Investimento::table()->select('id')->orderByDesc('id')->limit(1)->value('id');
    }
}
