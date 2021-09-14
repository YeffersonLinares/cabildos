 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('dinamico')->create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('component')->nullable()->comment('componente a al que redirige el item del menú (Si es nulo es un item desplegable)');
            $table->string('icon')->nullable()->comment('Nombre del icono asociado al item del menú');
            $table->unsignedInteger('menu_id')->nullable()->comment('Item padre, si el el mismo que el id significa que es de primer nivel o menú principal');
            $table->integer('ubicacion')->nullable();
            $table->text('ruta')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('dinamico')->dropIfExists('menus');
    }
}
