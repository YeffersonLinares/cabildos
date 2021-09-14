<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Equivalencias;
use App\Models\Persona;
use App\Models\UserDepto;
use App\Models\User;
use App\Models\Rol;
use App\Models\PersonaCentralizado;
use App\Models\DepartamentoUsuario;
use App\Models\DepartamentoCentralizado;
use App\Models\ModelHasRol;
use App\Models\UserDeclarante;
use App\Models\Declarante;
use App\Models\TipoDocumento;
use App\Models\Password;
use Hash;
use Mail;

class UserController extends Controller
{

    protected $nameDbCent;

    public function __construct()
    {       
        $this->middleware(function ($request, $next){
            if(Session('conexion') == 'centralizado'){
                $this->middleware('permission:view_usuarios', ['only' => ['index','show']]);
                $this->middleware('permission:add_usuarios', ['only' => ['create','store']]);
                $this->middleware('permission:edit_usuarios', ['only' => ['edit','update']]);
                $this->middleware('permission:delete_usuarios', ['only' => ['destroy']]);
            }
            return $next($request);
        });
        $this->nameDbCent = config('database.connections.centralizado.database');
        // $this->nameDbCent = Equivalencias::nameDbCentralizado();
    }

    public function index(Request $request)
    {
       
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if(Session('conexion') == 'centralizado')
        {
            $usuarios = User::join('personas','usuarios.id_persona','=', 'personas.id')
                            ->join('tipos_documentos','personas.tipo_documento','=','tipos_documentos.id')
                            ->select('usuarios.id',
                                    'usuarios.email as email',
                                    'usuarios.estado as estado',
                                    'usuarios.usuario as usuario',
                                    'usuarios.id_persona as persona_id',
                                    'usuarios.super_administrador as super_administrador',
                                    'tipos_documentos.id AS tipo_documento',
                                    'tipos_documentos.nombre AS n_tipo_documento',
                                    'personas.numero_documento as numero_documento',
                                    'personas.nombre as nombre',
                                    'personas.apellido as apellidos',
                                    )
                            ->orderBy('usuarios.id', 'desc');

        }else{
            $usuarios = ModelHasRol::join($this->nameDbCent.'.usuarios as users','model_has_roles.model_id','=','users.id')
                                    ->join($this->nameDbCent.'.departamentos_usuario as depto_user','depto_user.id_usuario','=','users.id')
                                    ->join('roles','roles.id','=','model_has_roles.role_id')
                                    ->join($this->nameDbCent.'.personas as pers','users.id_persona','=', 'pers.id')
                                    ->join($this->nameDbCent.'.tipos_documentos as tip_doc','pers.tipo_documento','=','tip_doc.id')
                                    ->select('users.id',
                                                'roles.id as idrol',
                                                'roles.name as rol_nom',
                                                'users.email as email',
                                                'depto_user.estado as estado',
                                                'users.usuario as usuario',
                                                'users.id_persona as persona_id',
                                                'tip_doc.id AS tipo_documento',
                                                'tip_doc.nombre AS n_tipo_documento',
                                                'pers.numero_documento as numero_documento',
                                                'pers.nombre as nombre',
                                                'pers.apellido as apellidos')
                                    ->orderBy('users.id', 'desc')
                                    ->where('depto_user.id_departamento',session('conexion'));

                                    if ($buscar!='')
                                    {
                                        $usuarios->where('pers.'.$criterio, 'like', '%'.$buscar.'%');                                                   
                                    }
        }       

        return [
            'pagination' => [
                'total'        => $usuarios->paginate(6)->total(),
                'current_page' => $usuarios->paginate(6)->currentPage(),
                'per_page'     => $usuarios->paginate(6)->perPage(),
                'last_page'    => $usuarios->paginate(6)->lastPage(),
                'from'         => $usuarios->paginate(6)->firstItem(),
                'to'           => $usuarios->paginate(6)->lastItem(),
            ],
            'usuarios' => $usuarios->paginate(6),
            'super_administrador' => (Session('conexion') == 'centralizado' and Auth::user()->super_administrador == '1') ? true : false,
            'showSelectUser' => (Auth::user()->super_administrador == '1' or Auth::user()->hasRole('Administrador')) ? true : false,
        ];

    }

    public function store(Request $request)
    {
        // dd($request->all());
        if (!$request->ajax()) return redirect('/');

        if($request->IdUserCentralizado != null)
        {
            $id_depto = DepartamentoCentralizado::where('id',$request->session()->get('conexion'))->first();
                 
            $validateUserDepto =  DepartamentoUsuario::where('id_usuario', $request->IdUserCentralizado)->where('id_departamento',$id_depto->id)->first();

            if(!is_object($validateUserDepto))
            {
                try{
                    DB::beginTransaction();

                        $depto = new DepartamentoUsuario();
                        $depto->id_departamento = $id_depto->id;
                        $depto->id_usuario      = $request->IdUserCentralizado;
                        $depto->save();
        
                        $user = User::findOrFail($request->IdUserCentralizado);      
                        $user->assignRole($request->idRol);

                    DB::commit();
                    return ['status'  => 200];

                } catch (\Exception $e){
                    DB::rollBack();
                }             

            }         
        }

        if(!$request->confirmado)
        {
            
            $validacion = $this->validarUserCentralizado($request->usuario,$request->numero_documento,$request->session()->get('conexion'),$request->email,$request->super_administrador);
    
            if(!$validacion->status)
            {
                switch($validacion->code)
                {
                    case '400':
                        return [
                                'status'  => $validacion->code,
                                'message' => $validacion->message
                        ];
                    break;
                    default:
                        return ['status'  => $validacion->code];
                    break;
                }
            }
        }

        if($request->existente or $validacion->code == 201){

            $user = PersonaCentralizado::where('numero_documento',$request->numero_documento)->first();

            $id_depto = DepartamentoCentralizado::where('id',$request->session()->get('conexion'))->first();

            $depto = new DepartamentoUsuario();
            $depto->id_departamento = $id_depto->id;
            $depto->id_usuario      = $user->user->id;
            $depto->save();

            $user->user->assignRole($request->idRol);

        }else{
            try{
                DB::beginTransaction();
    
                $person = new PersonaCentralizado();
                $person->nombre           = $request->nombre;
                $person->apellido         = $request->apellidos;
                $person->tipo_documento   = $request->tipo_documento;
                $person->numero_documento = $request->numero_documento;
                $person->save();
    
                $user = new User();
                $user->id_persona           = $person->id;
                $user->usuario              = $request->usuario;
                $user->email                = $request->email;
                $user->password             = bcrypt( $request->password);
                $user->observaciones        = $request->observaciones;  
                if($request->super_administrador and $request->idRol == 1)
                {
                    $user->super_administrador  = 1;       
                } 
                $user->estado               = '1';            
                $user->save();

                $password = new Password();
                $password->usuario_id   = $user->id;
                $password->password     = $user->password;
                $password->estado       = 1;
                $password->save();

                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
            }

            try{
                DB::beginTransaction();

                if($request->super_administrador)
                {
                    if($request->formCentralizado and $request->idRol == 2)
                    {
                        foreach($request->deptos_user as $key => $depto){

                            $dataDepto = DepartamentoCentralizado::where('id', $depto)->first();
                            //se cambia la conexin dinamicamente
                            $this->changeDbDinamica(false,$dataDepto->nombre_db);

                            $validateUser =  ModelHasRol::where('model_id',$user->id)->first();
                        
                            if(is_object($validateUser))
                            {
                                ModelHasRol::where('model_id', $user->id)
                                        ->update(['role_id' => 1]);
                                
                            }else{
                                $new = new ModelHasRol();
                                $new->role_id    = '1';
                                $new->model_type = 'App\Models\User';
                                $new->model_id   = $user->id;
                                $new->save();
                                
                                $validateUserDepto =  DepartamentoUsuario::where('id_usuario',$user->id)->where('id_departamento',$dataDepto->id)->first();
                                if(!is_object($validateUserDepto))
                                {
                                    $newUserDepto = new DepartamentoUsuario();
                                    $newUserDepto->id_departamento = $depto;
                                    $newUserDepto->id_usuario      = $user->id;
                                    $newUserDepto->save();
                                }                     
                            }
                        }


                    }else{
                        $deptos = DepartamentoCentralizado::where('estado', 1)->get();
    
                        foreach($deptos as $depto)
                        {
                            $newUserDepto = new DepartamentoUsuario();
                            $newUserDepto->id_departamento = $depto['id'];
                            $newUserDepto->id_usuario      = $user->id;
                            $newUserDepto->save();
                        }
                    }
                }else{
                    $id_depto = DepartamentoCentralizado::where('id',$request->session()->get('conexion'))->first();

                    $depto = new DepartamentoUsuario();
                    $depto->id_departamento = $id_depto->id;
                    $depto->id_usuario      = $user->id;
                    $depto->save();
    
                    $user->assignRole($request->idRol);
                }
                
                // $this->changeDbDinamica(true);

                DB::commit();
            } catch (\Exception $e){
                DB::rollBack();
            }
        }

        return ['status'  => 200];
    }

    public function update(Request $request)
    {
        // dd($request->all());

        if (!$request->ajax()) return redirect('/');

        $messages = [
            'numero_documento.required' => 'Número de documento es requerido.',
            'numero_documento.unique' =>'El Número de documento debe ser unico.',
            'email.required' => 'El Email es requerido.',
            'email.unique' => 'El Email debe ser único',
        ];

        $validated = $request->validate([
            'numero_documento' => 'required|unique:centralizado.personas,numero_documento,'.$request->persona_id,
            'email' => 'required|unique:centralizado.usuarios,email,'.$request->id,
        ],$messages);

        try{
            DB::beginTransaction();

            $persona = PersonaCentralizado::findOrFail($request->persona_id);
            $persona->nombre = $request->nombre;
            $persona->apellido = $request->apellidos;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->numero_documento = $request->numero_documento;
            $persona->save();

            $user = User::findOrFail($request->id);
            $user->email = $request->email;

            if(isset($request->password))
            {
                $user->password = bcrypt($request->password);
            }

            if(!$request->check_rol)
            {
                if($request->idRol != null and $request->idRol == 1 and $request->formCentralizado)
                {
                    $user->super_administrador = 1;
    
                    $deptos = DepartamentoCentralizado::where('estado', 1)->get();
    
                    foreach($deptos as $depto)
                    {
                        $comprobarDepto = DepartamentoUsuario::where('id_usuario','=',$user->id)
                                                            ->where('id_departamento',$depto['id'])
                                                            ->first();
                        if(!is_object($comprobarDepto))
                        {
                            $newUserDepto = new DepartamentoUsuario();
                            $newUserDepto->id_departamento = $depto['id'];
                            $newUserDepto->id_usuario      = $user->id;
                            $newUserDepto->save();
                        }
                    }
                }
            }else{
                $user->super_administrador = 0;
            }

            $user->observaciones = $request->observaciones;            
            $user->save();

            if(isset($request->password))
            {

                $inactiva = Password::where('usuario_id', $user->id)->where('estado','1')->first();
                $inactiva->estado = 0;
                $inactiva->save();

                $password = new Password();
                $password->usuario_id   = $request->id;
                $password->password     = $user->password;
                $password->estado       = 1;
                $password->save();

                $dataUser = [
                    'usuario'  => $user->usuario,
                    'password' => $request->password
                ];

                $dataUser = array('dataUser' => $dataUser);

                try{
                    Mail::send('correo.change_password', $dataUser, function($message) use ($user) {
                        $message->to($user->email);
                        $message->subject('Cambio de contraseña');
                    });

                }catch(\Exception $e){
                    return response()->json(['status' => false, 'email' => false]);
                    dd($e->getMessage());
                }

            }


            if(!$request->formCentralizado and ($request->idRol != null and $request->idRol))
            {
                $user->roles()->detach(); 
                $user->assignRole($request->idRol);
            }
       
            if($request->formCentralizado and $request->idRol == 2)
            {
                foreach($request->deptos_user as $key => $depto){

                    $dataDepto = DepartamentoCentralizado::where('id', $depto)->first();
                    //se cambia la conexin dinamicamente
                    $this->changeDbDinamica(false,$dataDepto->nombre_db);

                    $validateUser =  ModelHasRol::where('model_id',$request->id)->first();
                
                    if(is_object($validateUser))
                    {
                        ModelHasRol::where('model_id', $request->id)
                                   ->update(['role_id' => 1]);
                        
                    }else{
                        $validateUserDepto =  DepartamentoUsuario::where('id_usuario',$request->id)->where('id_departamento',$dataDepto->id)->first();

                        if(!is_object($validateUserDepto))
                        {
                            $newUserDepto = new DepartamentoUsuario();
                            $newUserDepto->id_departamento = $dataDepto->id;
                            $newUserDepto->id_usuario      = $request->id;
                            $newUserDepto->save();
                        }

                        $new = new ModelHasRol();
                        $new->role_id    = '1';
                        $new->model_type = 'App\Models\User';
                        $new->model_id   = $request->id;
                        $new->save();
                    }
                }

                $this->changeDbDinamica(true);
            }
            
            DB::commit();
            return response()->json(['status' => true]);
        } catch (\Exception $e){
            DB::rollBack();
            // dd($e);
        }
    }

    public function inactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        if($request->tipo == '1'){
            $user = DepartamentoUsuario::where('id_usuario',$request->id)
                                        ->where('id_departamento',$request->session()->get('conexion'))
                                        ->first();
            $user->estado = '0';
            $user->save();
        }else if($request->tipo == '2'){

            $user = User::findOrFail($request->id);
            $user->estado = '0';
            $user->save();

            $deptos = DepartamentoUsuario::where('id_usuario',$request->id)
                                        ->get();
    
            foreach($deptos as $depto){
                $user = DepartamentoUsuario::where('id_usuario',$request->id)
                                            ->where('id_departamento',$depto->id_departamento)
                                            ->first();
                $user->estado = '0';
                $user->save();

            }
        }
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        if($request->tipo == '1'){
            $user = DepartamentoUsuario::where('id_usuario',$request->id)
                                        ->where('id_departamento',$request->session()->get('conexion'))
                                        ->first();
            $user->estado = '1';
            $user->save();
        }else if($request->tipo == '2'){

            $user = User::findOrFail($request->id);
            $user->estado = '1';
            $user->save();

            $deptos = DepartamentoUsuario::where('id_usuario',$request->id)
                                        ->get();
    
            foreach($deptos as $depto){
                $user = DepartamentoUsuario::where('id_usuario',$request->id)
                                            ->where('id_departamento',$depto->id_departamento)
                                            ->first();
                $user->estado = '1';
                $user->save();

            }
        }
    }

    public function getTipoDoc()
    {
        return Auth::user();
        $tipos_doc = TipoDocumento::get();
        return ['tipos_doc' => $tipos_doc];
    }

    public function getUsuarioActivo()
    {
        $usuarioActivo = Auth::user();
        return ['usuarioActivo' => $usuarioActivo];
    }

    public function cambiarContraseña(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $respuesta = [ 'exito' => false, 'errores' => [], 'valor' => [] ];
        $user = Auth::user();
        // valida la contraseña actual con la que hay en el sistema   
        $validaIgual = Hash::check($request->contrasenaActual, $user->password);   
        if(!$validaIgual){
            $respuesta['errores'][] = 'La contraseña actual no corresponde!';
            $respuesta['valor'][] = 1;
            return $respuesta;
        }

        // valida las 10 ultimas contraseñas
        $ultimosPass = Password::where('usuario_id', $user->id)->orderBy('id','desc')->limit(10)->get();
        foreach ($ultimosPass as $pass) {
            $validaAnteriores = Hash::check($request->contrasenaNueva, $pass->password);          
            if($validaAnteriores == 1){
                $respuesta['errores'][] = 'La contraseña Nueva fue utilizada anteriormente!';
                $respuesta['valor'][] = 2;
                return $respuesta;
            }
        }

        // cambia la contraseña y la agrega al password_log
        try {
            DB::beginTransaction();
                $usuario = User::where('id', $user->id)->first();
                $usuario->password = bcrypt( $request->contrasenaNueva );
                $usuario->estado   = 1;
                $usuario->save();

                $inactiva = Password::where('usuario_id', $user->id)->where('estado','1')->first();
                $inactiva->estado = 0;
                $inactiva->save();
        
                $password = new Password();
                $password->usuario_id   = $user->id;
                $password->password     = $usuario->password;
                $password->estado       = 1;
                $password->save();
                
                $dataUser = [
                    'usuario'  => $user->usuario,
                    'password' => $request->contrasenaNueva
                ];

                $dataUser = array('dataUser' => $dataUser);
                try{
                    Mail::send('correo.change_password', $dataUser, function($message) use ($user) {
                        $message->to($user->email);
                        $message->subject('Cambio de contraseña');
                    });

                }catch(\Exception $e){
                    $respuesta['errores'][] = 'Por favor verifique los campos y/o su conexión e intentélo de nuevo.';
                    return $respuesta;
                }
                
                $respuesta['exito'] = true;
            DB::commit();
        } catch (\Exception $e){
            DB::rollBack();
            $respuesta['errores'][] = 'Por favor verifique los campos y/o su conexión e intentélo de nuevo.';
        }
        return $respuesta;

    }

    public function validarUserCentralizado($usuario,$documento,$depto,$email,$super_administrador){

        $response = (object)[
            'status'  => false,
            'code'    => 400,
            'message' => ''
        ];

        $validarSql = (object)[
            'status' => false,
            'type'   => ''
        ];

        //se busca al usuario por campo usuario
        $validacion = User::where('usuario',$usuario)->first();

        if(is_object($validacion)){
            $validarSql->status = true;
            $validarSql->type   = 'usuario';
        }else{
             //se busca al usuario por campo email
            $validacion = User::where('email',$email)->first();
            if(is_object($validacion)){
                $validarSql->status = true;
                $validarSql->type   = 'email';
            }
        }

         //en caso de que el usuario ya exista
        if($validarSql->status)
        {
            if($super_administrador){
                //se le retorna diciendo que usuario ya existe en el centralizado
                $response->code = 405;
                return $response;
            }
            // se valida que el documento sea igual que el ingresado
            if($validacion->persona->numero_documento == $documento)
            {
                
                //se trae el id del departamento actual
                $id_depto = DepartamentoCentralizado::where('id',$depto)->first();
                $responseDepto = false;
                

                foreach($validacion->departamentos as $deptos){
                    if($deptos->id_departamento == $id_depto->id)
                    {
                        //en caso de que el usuario ya este asociado a ese depto
                        $responseDepto = true;
                    }
                }    

                if($responseDepto)
                {
                    //se retorna y se le dice que ya se encuentra registrado
                    $response->status  = false;
                    $response->code    = 402;
                }else{
                    //se retorna y se le agrega este departamento
                    $response->status  = true;
                    $response->code    = 201;
                }

            }else{
                //en caso de que no exista se retorna
                if($validarSql->type == 'usuario'){
                    //se le dice que el usaurio ya existe
                    $response->code    = 401;
                }else if($validarSql->type == 'email'){
                    //se le dice que el email ya existe
                    $response->code    = 407;
                }
            }

            $response->message = $validacion->departamentos;

        }else{
            //se valida si existe por numero de documento
            $validacion = PersonaCentralizado::where('numero_documento',$documento)->first();

            //si existe ese numero de documento
            if(is_object($validacion))
            {   
                if($super_administrador){
                    //se le dice que Número de documento ya registrado en el Centralizado.
                    $response->code = 406;
                    return $response;
                }

                $id_depto = DepartamentoCentralizado::where('id',$depto)->first();
                $responseDepto = false;

                foreach($validacion->user->departamentos as $deptos){
                    if($deptos->id_departamento == $id_depto->id)
                    {
                        //se valida si ya existe en ese departamento
                        $responseDepto = true;
                    }
                }    

                if($responseDepto)
                {
                    // se le dice que documento registrado ya
                    $response->code    = 403;
                }else{
                    // se le vuelve a preguntar que ya existe un usuario con ese documento pero difernete usuario
                    // que si desea agregarle este departamento a ese usuario
                    $response->code    = 400;
                }

            }else{
                //si no existe se procede a guardarlo
                $response->status  = true;
                $response->code    = 200;
            }
        }
        return $response;

    }

    public function changeDbDinamica($estado, $nameDb = ''){
       
        if($estado){
            \Config::set('database.default','centralizado');
            DB::purge('dinamico');        
            DB::reconnect('dinamico');      
        }else{
            DB::purge('dinamico');        
            \Config::set('database.connections.dinamico.database',$nameDb);
            \Config::set('database.default','dinamico');
            DB::purge('centralizado');        
            DB::reconnect('centralizado');   
        }
       
    }

    public function getDeptoUser(Request $request){
        $deptos_user = DepartamentoUsuario::where('id_usuario',$request->id)->get();
        if(is_object($deptos_user))
        {
            foreach($deptos_user as $depto){
    
                $dataDepto = DepartamentoCentralizado::where('id', $depto->id_departamento)->where('estado','1')->first();
                //se cambia la conexin dinamicamente
                if(is_object($dataDepto))
                {
                    $this->changeDbDinamica(false,$dataDepto->nombre_db);
                }
    
                $validateUser =  ModelHasRol::where('model_id',$request->id)->first();
                if(is_object($validateUser))
                {
                    $depto['id_rol'] = $validateUser->role_id;
                }
            }    
        }
        return $deptos_user;
    }

    public function getUserCentralizado(Request $request){

        $user = User::join('departamentos_usuario','departamentos_usuario.id_usuario','usuarios.id')
                    ->leftJoin('departamentos_usuario as departamento_actual',function($query) use($request){
                        return $query->on('departamento_actual.id_usuario','usuarios.id')
                                     ->where('departamento_actual.id_departamento',$request->session()->get('conexion'));
                    })
                    ->join('personas','personas.id','usuarios.id_persona')
                    ->join('tipos_documentos','tipos_documentos.id','personas.tipo_documento')
                    ->select('usuarios.id','personas.nombre as npersona','personas.apellido','tipos_documentos.nombre','personas.numero_documento',
                            'usuarios.usuario','usuarios.email',
                            DB::raw("CONCAT(personas.nombre,' ( ',tipos_documentos.nombre,' - ',personas.numero_documento,')') as dato_com")
                            )
                    ->where('usuarios.super_administrador','!=',1)
                    ->whereNull('departamento_actual.id')
                    ->groupBy('departamentos_usuario.id_usuario','usuarios.id','tipos_documentos.nombre','personas.nombre','personas.numero_documento','dato_com','personas.apellido',
                            'usuarios.usuario','usuarios.email')
                    ->get();
        
        return $user;
    }
}
