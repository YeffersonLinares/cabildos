<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabildoSoporte extends Model
{
    use HasFactory;
    protected $connection = 'dinamico';
    protected $table = 'cabildo_soporte';
}
