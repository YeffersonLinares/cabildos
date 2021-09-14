<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;


class MenuSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Menú Dinamico
		DB::connection('dinamico')->table('menus')->insert([
        	[
	        	'id'		=> '1',
	        	'name' 		=> 'Administración',
	        	'component' => null,
	        	'icon' 		=> 'fa fa-cogs',
				'menu_id'   => null,
				'ubicacion' => '1',
				'ruta'	    => '/administracion',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '2',
	        	'name' 		=> 'Usuarios',
	        	'component' => '2',
	        	'icon' 		=> null,
				'menu_id'   => '1',
				'ubicacion' => null,
				'ruta'	    => '/administracion/usuarios',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '3',
	        	'name' 		=> 'Roles',
	        	'component' => '3',
	        	'icon' 		=> null,
				'menu_id'   => '1',
				'ubicacion' => null,
				'ruta'	    => '/administracion/roles',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '4',
	        	'name' 		=> 'Parametrizar Menus',
	        	'component' => '4',
	        	'icon' 		=> null,
				'menu_id'   => '1',
				'ubicacion' => null,
				'ruta'	    => '/administracion/parametrizar_menus',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '5',
	        	'name' 		=> 'Permisos',
	        	'component' => '5',
	        	'icon' 		=> null,
				'menu_id'   => '1',
				'ubicacion' => null,
				'ruta'	    => '/administracion/permisos',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '6',
	        	'name' 		=> 'Parametros',
	        	'component' => null,
	        	'icon' 		=> 'fa fa-wrench',
				'menu_id'   => null,
				'ubicacion' => '2',
				'ruta'	    => '/parametros',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '7',
	        	'name' 		=> 'Departamentos',
	        	'component' => '6',
	        	'icon' 		=> null,
				'menu_id'   => '6',
				'ubicacion' => null,
				'ruta'	    => '/parametros/departamentos',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '8',
	        	'name' 		=> 'Ciudades',
	        	'component' => '7',
	        	'icon' 		=> null,
				'menu_id'   => '6',
				'ubicacion' => null,
				'ruta'	    => '/parametros/ciudades',
	        	'status' 	=> '1'
	        ],
			[
	        	'id'		=> '9',
	        	'name' 		=> 'Tipo De Documentos',
	        	'component' => '8',
	        	'icon' 		=> null,
				'menu_id'   => '6',
				'ubicacion' => null,
				'ruta'	    => '/parametros/tipo_documento',
	        	'status' 	=> '1'
	        ],
        ]);
    }
}
