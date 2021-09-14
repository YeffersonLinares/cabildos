<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;


class MigrationSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Menú Dinamico
		DB::connection('dinamico')->table('migrations')->insert([
        	[
	        	'id'		 => '1',
	        	'migration'  => '2010_10_26_111117_log_user_login_c_table',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '2',
	        	'migration'  => '2010_10_26_111118_create_password_log_table',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '3',
	        	'migration'  => '2011_07_29_122704_create_permission_tables',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '4',
	        	'migration'  => '2011_07_29_122705_create_menu_table',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '5',
	        	'migration'  => '2014_05_11_150242_create_departamentos_table',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '6',
	        	'migration'  => '2014_05_11_150245_create_ciudades_table',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '7',
	        	'migration'  => '2014_10_12_100000_create_password_resets_table',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '8',
	        	'migration'  => '2019_08_19_000000_create_failed_jobs_table',
	        	'batch'      => 1
	        ],
			[
	        	'id'		 => '9',
	        	'migration'  => '2020_07_28_172724_create_activity_log_table',
	        	'batch'      => 1
	        ]
        ]);	
    }
}
