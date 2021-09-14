<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use app\RolePermission;
use Illuminate\Support\Facades\Session;

class Permission extends \Spatie\Permission\Models\Permission
{

    protected $fillable = ['name', 'guard_name','id_componente','updated_at','created_at'];

	protected $connection;
    protected $guard_name = "web";

	
	// function __construct() {
	// 	$this->connection = Session::get('conexion');
	// 	parent::__construct();
	// }
	public function __construct(array $attributes = [])
    {
		parent::__construct($attributes);
        $this->connection = Session::get('conexion');
    }

    public function role_permissions()
    {
        return $this->hasOne(RolePermission::class, 'permission_id', 'permission.id');
    }


    public static function permisosAdministrador()
    {
    	return [

    		'1' => [ 
					'view_administracion',
    				'add_administracion',
    		        'edit_administracion',
    		        'delete_administracion'
			],
			'2' => [ 
					'view_usuarios',
    				'add_usuarios',
    		        'edit_usuarios',
    		        'delete_usuarios'
			],
			'3' => [ 
					'view_roles',
    				'add_roles',
    		        'edit_roles',
    		        'delete_roles'
			],
			'4' => [
					'view_parametrizar_menus',
					'add_parametrizar_menus',
					'edit_parametrizar_menus',
					'delete_parametrizar_menus',
			],
			'5' => [
					'view_permisos',
					'add_permisos',
					'edit_permisos',
					'delete_permisos',
			],
			'6' => [
					'view_parametros',
					'add_parametros',
					'edit_parametros',
					'delete_parametros',
			],
			'7' => [
					'view_departamentos',
					'add_departamentos',
					'edit_departamentos',
					'delete_departamentos',
			],
			'8' => [
					'view_ciudades',
					'add_ciudades',
					'edit_ciudades',
					'delete_ciudades',
			],
			'9' => [
					'view_tipo_documento',
					'add_tipo_documento',
					'edit_tipo_documento',
					'delete_tipo_documento',
			]
    	];
    }
}
