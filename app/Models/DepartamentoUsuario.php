<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentoUsuario extends Model
{
    use HasFactory;
    protected $table ="departamentos_usuario";
    protected $connection = "centralizado";
}
