<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermisosSeed::class);
        $this->call(RolSeed::class);
        $this->call(DepartamentoSeed::class);
        $this->call(MenuSeed::class);
        $this->call(CiudadSeed::class);
        $this->call(MigrationSeed::class);
    }
}
