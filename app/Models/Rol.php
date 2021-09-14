<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Session;

class Rol extends \Spatie\Permission\Models\Role
{   
    use LogsActivity;
    protected $connection;

    protected static $logAttributes = ['*'];

    protected static $logName       = 'rol';

    protected $guard_name = "web";

    protected $table = 'roles';
    protected $fillable = ['name','description', 'status', 'guard_name'];

    public function __construct(array $attributes = [])
    {
		parent::__construct($attributes);
        $this->connection = Session::get('conexion');
    }

    public static function rolesDefault()
    {
        $roles_array = ['name' => 'Administrador'];

    	return $roles_array;
    }
   
}
