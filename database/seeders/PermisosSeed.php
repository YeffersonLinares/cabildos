<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;


class PermisosSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed the default permissions
        foreach (Permission::permisosAdministrador() as $key => $permiso) {
            foreach($permiso as $perm){
                DB::connection('dinamico')->table('permissions')->insert(['name' => $perm,'id_componente' => $key,'guard_name' => 'web']);
            }
        }

        $this->command->info('Permisos agregados por defecto.');
    }
}
