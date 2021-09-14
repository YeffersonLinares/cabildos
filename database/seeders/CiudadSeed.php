<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ciudad;
use Illuminate\Support\Facades\DB;

class CiudadSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ruta = database_path('json/ciudades.json');
        $ciudades = file_get_contents($ruta);

        foreach(json_decode($ciudades) as $ciudad)
        {
            DB::connection('dinamico')->table('ciudades')
            ->insert(
                array(
                    'nombre'          => $ciudad->municipio,
                    'id_departamento' => $ciudad->c_digo_dane_del_departamento
                )
            );
        }
    }
}
