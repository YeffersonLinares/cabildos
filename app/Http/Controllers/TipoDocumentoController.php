<?php

namespace App\Http\Controllers;

use App\Models\Tipo_documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;

class TipoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('tipoDocumento_J.index');
    }

    public function index_tipo_d()
    {
        $tipoDocumento = TipoDocumento::where('estado', 1)->get();
        return $tipoDocumento;
    }

    public function buscar_tipoDocumento($nombre)
    {
        // return response()->json(['status' => 200, 'msg' => $nombre]);
        // $post = $request;
        $tipoDocumento = TipoDocumento::where('estado', 1)
        ->where(function ($query) use ($nombre) {
            if (isset($nombre)) {
                if (!empty($nombre))
                    $query->orwhere('tipo_documento.nombre', 'like', "%" . $nombre . "%");
            }
        })->get();
        return response()->json(['status' => 200, 'tipoDocumento' => $tipoDocumento]);
        // $post = $request;
        // $tipoDocumento = TipoDocumento::where('estado', 1)
        // ->where(function ($query) use ($post) {
        //     if (isset($post['nombre_buscar'])) {
        //         if (!empty($post['nombre_buscar']))
        //             $query->orwhere('tipo_documento.nombre', 'like', "%" . $post['nombre_buscar'] . "%");
        //     }
        // })->get();
        // return response()->json(['status' => 200, 'tipoDocumento' => $tipoDocumento]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json(['status' => 200, 'msg' => $request->all()]);
        $tipoDocumento = new TipoDocumento();
        $tipoDocumento->nombre = $request->nombre;
        $tipoDocumento->usuario_creador = 1;
        if ($tipoDocumento->save()) :
            return response()->json(['status' => 200, 'msg' => 'Tipo de archivo creado con éxito', 'tipo_documento' => $tipoDocumento]);
        else :
            return response()->json(['status' => 500, 'msg' => 'Algo salió mal']);
        endif;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoDocumento  $tipoDocumento
     * @return \Illuminate\Http\Response
     */
    public function show(TipoDocumento $tipoDocumento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoDocumento  $tipoDocumento
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $tipoDocumento = TipoDocumento::find($request->id);
        return response()->json(['status' => 200, 'tipoDocumento' => $tipoDocumento]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoDocumento  $tipoDocumento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return response()->json(['msg' => $request->all()]);
        $tipoDocumento = TipoDocumento::find($request->id);
        $tipoDocumento->nombre = $request->nombre;
        if($tipoDocumento->save()):
            return response()->json(['status' => 200, 'msg' => 'editado correctamente', 'tipoDocumento' => $tipoDocumento]);
        else:
            return response()->json(['status' => 500, 'msg' => 'Algo salió mal']);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoDocumento  $tipoDocumento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // return response()->json(['status' => 200, 'msg' => $id]);
        $tipoDocumento = TipoDocumento::find($id);
        $tipoDocumento->estado = 3;
        if($tipoDocumento->save()):
            $tipoDocumento = TipoDocumento::where('estado',1)->get();
            return response()->json([
            'status' => 200,
            'msg' => 'Tipo de archivo eliminado con éxito',
            'tipoDocumento' => $tipoDocumento
            ]);
        else:
            return response()->json(['status' => 500, 'msg' => 'Algo salió mal']);
        endif;
    }

    public function modal_eliminar_tipoDocumento(Request $request){
        return response()->json(['id' => $request->id]);
    }
}
