<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;

class RolSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles_array = Rol::rolesDefault();

        foreach($roles_array as $role)
        {
        
            $roleSave = DB::connection('dinamico')->table('roles')->insert(['name' => trim($role),'guard_name' => 'web']);
            $permisos = DB::connection('dinamico')->table('permissions')->get();
            foreach($permisos as $permiso){
                DB::connection('dinamico')->table('role_has_permissions')->insert(['permission_id' => $permiso->id,'role_id' => '1']);
            }
                $this->command->info('Permisos agregados a rol Administrador');             
        }
    }
}
