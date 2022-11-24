<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Investimento
{
    const EXCLUIDO_SIM = 1;
    const EXCLUIDO_NAO = 0;

    public static function table()
    {
        $table = DB::table('Investimentos');
        return $table;
    }

    public static function getInvestimentoId($id)
    {
        $getInvestimentoId = Investimento::table()->select('id', 'investidor', 'valor', 'data')->where(['id' => $id, 'excluido' => Investimento::EXCLUIDO_NAO]);
        return $getInvestimentoId;
    }


    public static function insertInvestimentoNovo($dados)
    {
        Investimento::table()->insert($dados);
        return Investimento::getInsertUltimo();
    }

    public static function getInsertUltimo()
    {
      return   Investimento::table()->select('id')->orderByDesc('id')->limit(1)->value('id');
    }
}
