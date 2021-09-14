<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LogUserLogin;
use App\Models\User;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Equivalencias;

class LoginController extends Controller
{

    protected $rutaProduccion;

    public function __construct(){
        $this->rutaProduccion = Equivalencias::urlProduccion();
    }

    
    public function showLoginForm(){
     
        return view('auth.login');
    }

    public function showLoginFormRedirect(Request $request,$error){
        Auth::logout();
        $request->session()->invalidate();
        return redirect("/")->with('error_token',$error);
    }
    
    public function logout(Request $request){ //cerrar sesion 
        Auth::logout();
        $request->session()->invalidate();
        // JWTAuth::setToken($token)->invalidate();
        return Redirect::to($this->rutaProduccion);

    }

    public function logUserLogin(Request $request){

        $new = new LogUserLogin();
        $new->id_usuario    = Auth::user()->id;
        $new->fecha_login   = Carbon::now()->toDateTimeString();
        $new->ip_login      = $request->getClientIp();
        $new->save();
    }

}
