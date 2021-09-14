<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DepartamentoUsuario;
use App\Models\DepartamentoCentralizado;
use App\Http\Middleware\ChangeDb;
use App\Helpers\Equivalencias;
use Illuminate\Support\Facades\Redirect;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Http;

class CentralizadoController extends Controller
{
    protected $rutaProduccion;

    public function __construct(){
        $this->rutaProduccion = Equivalencias::urlProduccion();
    }

    public function index(){
        return Redirect::to($this->rutaProduccion.'centralizadoRedirect');
    }

    public function getDeptos($id){

        $deptos = DepartamentoUsuario::join('departamentos','departamentos.id','=','departamentos_usuario.id_departamento')
                  ->where('departamentos_usuario.id_usuario',$id)
                  ->where('departamentos.estado','1')
                  ->select('departamentos.id','departamentos.url_logo','departamentos.nombre')          
                  ->get();

        session(['departamentos' => json_decode($deptos)]);
    
        return json_decode($deptos);
    }

    public function change(Request $request,$userId,$conexion,$token){

        if(!$this->validateSesion($token,$userId))
        {
            Auth::logout();
            $request->session()->invalidate();
            return Redirect::to($this->rutaProduccion.'?token=true')->with('sin_permisos_token','Error en el token para Acceder!');
        }

        Auth::loginUsingId($userId, true);

        $depto = DepartamentoCentralizado::where('id',$conexion)->first();

        $activeDepto = DepartamentoUsuario::join('usuarios','usuarios.id','=','departamentos_usuario.id_usuario')
                                        ->where('departamentos_usuario.id_usuario',Auth::id())
                                        ->where('departamentos_usuario.id_departamento',$depto->id)
                                        ->select('departamentos_usuario.estado')          
                                        ->first();

        if($activeDepto->estado == '0'){
            $error = 'Usuario Inactivo en el Aplicativo <b>('.$depto->nombre.')</b>';
            return back()->with('activeDepto',$error);
        }

        session(['conexion'      => $conexion,
                 'nombre_db'     => $depto->nombre_db,
                 'data_depto'    => [
                                     'logo'   => $depto->url_logo,
                                     'nombre' => $depto->nombre
                                    ],
                 'url_produccion' => $this->rutaProduccion]);
        
        if($depto->nombre == 'FNFP'){
            session(['nombre_largo'  => 'FONDO NACIONAL DE FINANCIACIÓN POLÍTICA']);
        }else{
            session(['nombre_largo'  => strtoupper($depto->nombre)]);
        }

        $data =  $this->getDeptos(Auth::id());

        return redirect("/main");

    }


    public function loginRedirect($conexion){

        $depto = DepartamentoCentralizado::where('id',$conexion)->first();
        $userId = Auth::user()->id;
        $token = session('token_user');
     
        return redirect($depto->url_produccion.'autologin/'.$userId.'/'.$conexion.'/'.$token, 302, [
            'Authorization' => "Bearer {$token}"
        ]);
    }

    private function validateSesion($token,$userId)
    {
        $response = (object)[
            'status' => false
        ];

        $tokenParts = explode(".", $token);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        $data = $jwtPayload->myCustomObject;
        // dd($data);
        if($data->id != $userId)
        {
            return $response->status = false;
        }

        $user = User::where('id',$userId)->first();

        if(!is_object($user)){
            return $response->status = false;
        }
      
        return $response;
    }

    public function getViewCentralizado()
    {
        $payload = JWTFactory::sub(Auth::user()->id)
                            ->myCustomObject(Auth::user())
                            ->make();

        $token = JWTAuth::encode($payload);
        session(['token_user'=> $token]);

        $data =  $this->getDeptos(Auth::id());
        return view('lobby/index',['data' => $data]);
    }

    public function redirectCentralizado(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return Redirect::to($this->rutaProduccion.'centralizado');
    }

}
