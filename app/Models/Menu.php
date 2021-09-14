<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    // protected $connection = 'centralizado';

    protected $table = 'menus';

    protected $fillable = ['name', 'component', 'id_permiso', 'menu_id', 'icon'];
}
