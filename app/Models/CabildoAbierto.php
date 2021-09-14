<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CabildoAbierto extends Model
{
    use HasFactory;
    protected $connection = 'dinamico';
    protected $table = 'cabildo_abierto';

    public function departamento()
    {
        return $this->belongsTo(Departamento::class,'dep_id');
    }
    public function municipio()
    {
        return $this->belongsTo(Ciudad::class,'ciu_id');
    }
}
