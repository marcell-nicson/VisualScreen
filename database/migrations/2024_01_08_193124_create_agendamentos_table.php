<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('arquivo_id');
            $table->foreign('arquivo_id')->references('id')->on('arquivos')->onDelete('cascade');
            $table->dateTime('DataHoraInicio');
            $table->dateTime('DataHoraFim');
            $table->enum('Status', ['Ativo', 'Inativo', 'Pausado'])->default('Ativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos');
    }
}
