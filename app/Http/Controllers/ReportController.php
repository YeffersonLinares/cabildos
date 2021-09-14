<?php

namespace App\Http\Controllers;

use App\Models\CabildoAbierto;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function excelCabildos(Request $r)
    {
        $post = $r;
        $cabildo = CabildoAbierto::where('estado', 1)
        ->where(function ($query) use ($post) {
            if (isset($post['nombre_tema'])) {
                if (!empty($post['nombre_tema']))
                    $query->orwhere('cabildo_abierto.nombre_tema', 'like', "%" . $post['nombre_tema'] . "%");
            }
        })
        ->where(function ($query) use ($post) {
            if (isset($post['dep_id'])) {
                if (!empty($post['dep_id']))
                    $query->orwhere('cabildo_abierto.dep_id', 'like', "%" . $post['dep_id'] . "%");
            }
        })
        ->where(function ($query) use ($post) {
            if (isset($post['fecha_realizacion'])) {
                if (!empty($post['fecha_realizacion']))
                $query->where('cabildo_abierto.fecha_realizacion', '>=', $post['fecha_realizacion']);
            }
        })
        ->where(function ($query) use ($post) {
            if (isset($post['fecha_end'])) {
                if (!empty($post['fecha_end']))
                $query->where('cabildo_abierto.fecha_realizacion', '<=', $post['fecha_end']);
            }
        })
        ->get();
        return view('report.cabildos')
            ->with('cabildos', $cabildo);
    }
}
