<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCabildoAbiertoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dinamico')->create('cabildo_abierto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dep_id')->nullable()->constrained('departamentos')->onDelete('restrict');
            $table->foreignId('ciu_id')->nullable()->constrained('ciudades')->onDelete('restrict');
            $table->string('radicado_CNE')->nullable();
            $table->string('nombre_tema',100)->nullable();
            $table->string('description')->nullable();
            $table->date('fecha_realizacion')->nullable();
            $table->integer('estado')->default(1); // 0: inactivo, 1: activo, 2: pendiente
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
        Schema::connection('dinamico')->dropIfExists('cabildo_abierto');
    }
}
