<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExchangeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moeda', function(Blueprint $table){
            $table->string('id')->primary();
            $table->string('descricao');
        });

        Schema::create('historico_conversao', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->date('ref_date');//Data de Referência da Conversão
            $table->unsignedBigInteger('usuario_id');
            $table->string('moeda_origem_id');
            $table->unsignedDecimal('valor_origem',14,6);
            $table->string('moeda_destino_id');
            $table->unsignedDecimal('valor_destino',14,6);
            $table->boolean('cached')->default(0);
            $table->string('api_timestamp');
            $table->timestamps();

            $table->foreign('usuario_id','fk_usr_hist_conv')->references('id')->on('users');
            $table->foreign('moeda_origem_id','fk_hist_conv_moeda_o')->references('id')->on('moeda');
            $table->foreign('moeda_destino_id','fk_hist_conv_moeda_d')->references('id')->on('moeda');
            $table->index('api_timestamp','idx_hist_api_time');
        });

        Schema::create('cache_conversao', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->date('ref_date');//Data de Referência da Conversão
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedDecimal('valor_usd',14,6)->default(1); //Por padrão, dólar será a nova moeda base
            $table->unsignedDecimal('valor_brl',14,6);
            $table->unsignedDecimal('valor_cad',14,6);
            $table->string('api_timestamp');
            $table->timestamps();

            $table->foreign('usuario_id','fk_cache_user')->references('id')->on('users');
            //cache para busca mais rápida por data
            $table->index('ref_date','idx_ref_date_cache_conv');
            $table->index('api_timestamp','idx_cache_api_time');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cache_conversao');
        Schema::dropIfExists('historico_conversao');
        Schema::dropIfExists('moeda');
    }
}
