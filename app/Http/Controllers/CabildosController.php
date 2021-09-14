<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use App\Models\Departamento;
use App\Models\CabildoAbierto;
use Illuminate\Support\Facades\Validator;

class CabildosController extends Controller
{

    public function index(Request $request)
    {
        $cabildos = CabildoAbierto::with(['municipio.departamento'])
            ->where(function ($query) use ($request) {
                if (!empty($request->nombre_tema)) {
                    $query->where('nombre_tema', 'like', "%$request->nombre_tema%");
                }
            })
            ->whereHas('municipio', function ($query) use ($request) {
                if (!empty($request->dep_id)) {
                    $query->where('id_departamento', $request->dep_id);
                }
            })
            ->where(function ($query) use ($request) {
                if (!empty($request->fecha_inicio)) {
                    $query->where('fecha_realizacion', '>=', $request->fecha_inicio);
                }
            })
            ->where(function ($query) use ($request) {
                if (!empty($request->fecha_end)) {
                    $query->where('fecha_realizacion', '<=', $request->fecha_end);
                }
            })
            ->where('estado', 1)
            ->paginate(30);

        return response()->json([
            'departments' =>  Departamento::all(),
            'cabildos' =>  $cabildos
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'theme' => 'required',
            'description' => 'required',
            'department' => 'required|numeric',
            'municipality' => 'required|numeric',
            'date' => 'required|after:today',
            'radicado_CNE' => 'required',
            'type_file' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) :
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()->first()
            ]);
        endif;

        if(empty($request->id)):
            $cabildo = new CabildoAbierto();
        else:
            $cabildo = CabildoAbierto::find($request->id);
        endif;

        $cabildo->nombre_tema = $request->theme;
        $cabildo->ciu_id = $request->municipality;
        $cabildo->radicado_CNE = $request->radicado_CNE;
        $cabildo->description = $request->description;
        $cabildo->fecha_realizacion = $request->date;
        $cabildo->save();

        return response()->json([
            'status' => 200,
            'msg' => 'Datos guardados correctamente',
        ]);
    }

    public function edit()
    {
        return response()->json([
            'tipo_documentos' => TipoDocumento::all(),
            'departamentos' => Departamento::with(['ciudades'])->get(),
        ]);
    }

    public function destroy($id)
    {
        $cabildo = CabildoAbierto::find($id);
        $cabildo->estado = 0;
        $cabildo->save();
    }

    public function downloadFile($file)
    {
        return response()->download(storage_path("app/public/uploads/$file"));
    }
}
