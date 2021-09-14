<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartamentoCentralizado;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\DepartamentoUsuario;

class DepartamentoCentralizadoController extends Controller
{
   
    
    public function getDepartamentos(Request $request){
        $deptos = DepartamentoCentralizado::where('estado','1')->get();
        return $deptos;
    }

   
}
