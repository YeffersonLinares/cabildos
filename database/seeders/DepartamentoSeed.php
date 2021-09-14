<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;

class DepartamentoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $departamentos = array(
            array(
                'id'          => 5,
                'nombre'      => 'Antioquia',
            ),
            array(
                'id'          => 8,
                'nombre'      => 'Atlántico',
            ),
            array(
                'id'          => 13,
                'nombre'      => 'Bolívar',
            ),
            array(
                'id'          => 15,
                'nombre'      => 'Boyacá',
            ),
            array(
                'id'          => 17,
                'nombre'      => 'Caldas',
            ),
            array(
                'id'          => 18,
                'nombre'      => 'Caquetá',
            ),

            array(
                'id'          => 19,
                'nombre'      => 'Cauca',
            ),
            array(
                'id'          => 20,
                'nombre'      => 'Cesar',
            ),
            array(
                'id'          => 23,
                'nombre'      => 'Córdoba',
            ),
            array(
                'id'          => 25,
                'nombre'      => 'Cundinamarca',
            ),
            array(
                'id'          => 27,
                'nombre'      => 'Chocó',
            ),
            array(
                'id'          => 41,
                'nombre'      => 'Huila',
            ),
            array(
                'id'          => 44,
                'nombre'      => 'La Guajira',
            ),
            array(
                'id'          => 47,
                'nombre'      => 'Magdalena',
            ),
            array(
                'id'          => 50,
                'nombre'      => 'Meta',
            ),
            array(
                'id'          => 52,
                'nombre'      => 'Nariño',
            ),
            array(
                'id'          => 54,
                'nombre'      => 'Norte de Santander',
            ),
            array(
                'id'          => 63,
                'nombre'      => 'Quindío',
            ),
            array(
                'id'          => 66,
                'nombre'      => 'Risaralda',
            ),
            array(
                'id'          => 68,
                'nombre'      => 'Santander',
            ),
            array(
                'id'          => 70,
                'nombre'      => 'Sucre',
            ),
            array(
                'id'          => 73,
                'nombre'      => 'Tolima',
            ),
            array(
                'id'          => 76,
                'nombre'      => 'Valle del Cauca',
            ),
            array(
                'id'          => 81,
                'nombre'      => 'Arauca',
            ),
            array(
                'id'          => 85,
                'nombre'      => 'Casanare',
            ),
            array(
                'id'          => 86,
                'nombre'      => 'Putumayo',
            ),
            array(
                'id'          => 88,
                'nombre'      => 'Archipiélago de San Andrés, Providencia y Santa Catalina',
            ),
            array(
                'id'          => 91,
                'nombre'      => 'Amazonas',
            ),
            array(
                'id'          => 94,
                'nombre'      => 'Guainía',
            ),
            array(
                'id'          => 95,
                'nombre'      => 'Guaviare',
            ),
            array(
                'id'          => 97,
                'nombre'      => 'Vaupés',
            ),
            array(
                'id'          => 99,
                'nombre'      => 'Vichada',
            ),
           
        );
                 

        foreach($departamentos as $departamento)
        {
            DB::connection('dinamico')->table('departamentos')->insert($departamento);
    
        }
      
    }
}
