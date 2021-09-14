<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    // protected $connection = 'tolima';
    protected $connection = 'dinamico';
    protected $table = 'ciudades';
    protected $fillable = [
        'iddepartamento',
        'nombre',
        'condicion',
        'codigo_dane'
    ];


    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }
}
