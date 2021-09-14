 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::connection('dinamico')->create('migrations', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('migration',191);
        //     $table->integer('batch');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::connection('dinamico')->dropIfExists('migrations');
    }
}
