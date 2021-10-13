<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Initial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {



        Schema::create('perfil_usuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 250);
            $table->string('descricao', 1000);
            $table->boolean('super')->default(false);
            $table->timestamps();
        });


        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('perfil_id')->after('password');
            $table->string('exchange_api_key',1000)->nullable()->after('remember_token');
            $table->boolean('ativo')->after('perfil_id');
            $table->foreign('perfil_id', 'fk_usuario_perfil')->references('id')->on('perfil_usuario');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_usuario_perfil');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('perfil_id');
            $table->dropColumn('ativo');
            $table->dropColumn('exchange_api_key');
        });

        Schema::dropIfExists('perfil_usuario');


    }
}
