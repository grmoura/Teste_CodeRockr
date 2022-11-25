<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investimentos', function (Blueprint $table) {
            $table->id();
            $table->string('investidor_nome');
            $table->double('investimento_valor', 8, 2);
            $table->date('investimento_data_entrada');
            $table->tinyInteger('investimento_saque')->default(0);
            $table->date('investimento_data_saida')->nullable($value = true);
            $table->tinyInteger('excluido')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investimentos');
    }
};
