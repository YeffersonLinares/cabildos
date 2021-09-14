<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiudadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dinamico')->create('ciudades', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('id_departamento')->unsigned();
            $table->string('nombre',255);
            $table->boolean('estado')->default(1);
            $table->timestamps();
            $table->foreign('id_departamento')->references('id')->on('departamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dinamico')->dropIfExists('ciudades');
    }
}
