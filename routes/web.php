<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParametrizarMenusController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\CentralizadoController;
use App\Http\Controllers\TipoDocumento2Controller;
use App\Http\Controllers\DepartamentoCentralizadoController;
use App\Http\Middleware\ChangeDb;
use App\Http\Middleware\CheckSesion;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\ValidateSesion;


// ----------INICIO IMPORTACIÓN CONTROLADORES CABILDOS-----------
use App\Http\Controllers\CabildosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TipoDocumentoController;
use App\Models\TipoDocumento;

// ----------FIN IMPORTACIÓN CONTROLADORES CABILDOS-----------

Route::get('/', [LoginController::class, 'logout'])->name('showlogin')->middleware('CheckSesion');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/token/{error}', [LoginController::class, 'showLoginFormRedirect']);
Route::get('autoLoginRedirect/{conexion}', [CentralizadoController::class, 'loginRedirect'])->name('autoLoginRedirect');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('autologin/{userId}/{conexion}/{token}', [CentralizadoController::class, 'change']);
});

Route::middleware('auth')->group(function () {
    Route::get('centralizadoRedirect', [CentralizadoController::class, 'getViewCentralizado'])->middleware('validateSesion');
    Route::get('centralizado/{conexion}', [CentralizadoController::class, 'change'])->name('changeDb');
    Route::get('centralizado', [CentralizadoController::class, 'index'])->name('centralizado')->middleware('validateSesion');
    Route::get('redirectCentralizado', [CentralizadoController::class, 'redirectCentralizado'])->name('redirectCentralizado');

    Route::group(["middleware" => ["CheckDepto", "ChangeDb", "validateSesion"]], function () {

        Route::get('/main', [MainController::class, 'index'])->name('main');
        Route::get('/rol', [RolController::class, 'index']);
        Route::post('/rol/create', [RolController::class, 'store']);
        Route::put('/rol/update', [RolController::class, 'update']);
        Route::put('/rol/updatePermisos', [RolController::class, 'updatePermisos']);
        Route::put('/rol/inactivar', [RolController::class, 'inactivar']);
        Route::put('/rol/activar', [RolController::class, 'activar']);
        Route::get('/rol/getrol', [RolController::class, 'getRoles']);
        Route::get('/rol/obtenerRolPermisos', [RolController::class, 'obtenerRolPermisos']);
        Route::get('/permission', [PermisosController::class, 'index']);
        Route::post('/permission/create', [PermisosController::class, 'store']);
        Route::put('/permission/update', [PermisosController::class, 'update']);
        Route::put('/permission/cambiarEstado', [PermisosController::class, 'cambiarEstado']);
        Route::get('/permission/obtenerPermisos', [PermisosController::class, 'obtenerPermisos']);
        Route::get('/tipoDoc/gettipodoc', [UserController::class, 'getTipoDoc']);

        Route::get('/usuarios', [UserController::class, 'index']);
        Route::post('/usuarios/create', [UserController::class, 'store']);
        Route::put('/usuarios/update', [UserController::class, 'update']);
        Route::put('/usuarios/inactivar', [UserController::class, 'inactivar']);
        Route::put('/usuarios/activar', [UserController::class, 'activar']);
        Route::get('/usuarios/usuarioActivo', [UserController::class, 'getUsuarioActivo']);
        Route::get('/usuarios/cambiarEstadoNoti', [UserController::class, 'cambiarEstadoNoti']);
        Route::put('/usuarios/cambiarcontraseña', [UserController::class, 'cambiarContraseña']);
        Route::get('/usuarios/getDeptoUser/{id}', [UserController::class, 'getDeptoUser']);
        Route::get('/usuarios/getUserCentralizado', [UserController::class, 'getUserCentralizado']);

        Route::get('/menus', [ParametrizarMenusController::class, 'index']);
        Route::post('/menus/create', [ParametrizarMenusController::class, 'store']);
        Route::put('/menus/update', [ParametrizarMenusController::class, 'update']);
        Route::put('/menus/cambiarEstado', [ParametrizarMenusController::class, 'cambiarEstado']);
        Route::get('/menus/consultarMenuPadre/{id}', [ParametrizarMenusController::class, 'consultarMenuPadre']);
        Route::get('/menus/consultarPermisosPadre',  [ParametrizarMenusController::class, 'consultarPermisosPadre']);
        Route::get('/menus/getMenus', [ParametrizarMenusController::class, 'getMenus']);
        Route::get('/menus/verificaUltimoHijo',  [ParametrizarMenusController::class, 'verificaUltimoHijo']);
        Route::get('/menus/getRutaRelativa', [ParametrizarMenusController::class, 'getRutaRelativa']);
        Route::get('/menus/getRutasVue', [ParametrizarMenusController::class, 'getRutasVue']);
        Route::get('/menus/getRutasVueMenu', [ParametrizarMenusController::class, 'getRutasVueMenu']);

        Route::put('/permisos/asignarPermisos',  [PermisoController::class, 'asignarPermisos']);

        Route::get('/departamentos/selectDepartamento', [DepartamentoController::class, 'selectDepartamento']);
        Route::get('/ciudades/selectCiudad/{id}', [CiudadController::class, 'selectCiudad']);
        Route::get('/departamentosCentralizado/getDepartamentos', [DepartamentoCentralizadoController::class, 'getDepartamentos']);

        Route::get('/ciudad', [CiudadController::class, 'index']);
        Route::post('/ciudad/store', [CiudadController::class, 'store']);
        Route::put('/ciudad/update', [CiudadController::class, 'update']);
        Route::put('/ciudad/inactivar', [CiudadController::class, 'inactivar']);
        Route::put('/ciudad/activar', [CiudadController::class, 'activar']);

        Route::get('/departamento', [DepartamentoController::class, 'index']);
        Route::post('/departamento/store', [DepartamentoController::class, 'store']);
        Route::put('/departamento/update', [DepartamentoController::class, 'update']);
        Route::put('/departamento/inactivar', [DepartamentoController::class, 'inactivar']);
        Route::put('/departamento/activar', [DepartamentoController::class, 'activar']);
        Route::get('/departamento/selectDepartamento', [DepartamentoController::class, 'seleccioneDepartamento']);

        Route::get('/tipoDocumento', [TipoDocumento2Controller::class, 'index']);
        Route::post('/tipoDocumento/create', [TipoDocumento2Controller::class, 'store']);
        Route::put('/tipoDocumento/update', [TipoDocumento2Controller::class, 'update']);
        Route::put('/tipoDocumento/inactivar', [TipoDocumento2Controller::class, 'inactivar']);
        Route::put('/tipoDocumento/activar', [TipoDocumento2Controller::class, 'activar']);


        //------------------INICIO RUTAS CALBILDOS----------------------------------------------------------------------------------





        //--------------------------------------------------------------------------------------
        // Rutas para reportes
        //--------------------------------------------------------------------------------------

        Route::get('/excel-cabildos', [ReportController::class, 'excelCabildos']);
        Route::post('/excel-cabildos', [ReportController::class, 'excelCabildos']);







        //------------------FIN RUTAS CALBILDOS----------------------------------------------------------------------------------

    });
});

        //--------------------------------------------------------------------------------------
        // Rutas para tipos de documento
        //--------------------------------------------------------------------------------------

        Route::get('/new-sesion', [CabildosController::class, 'getIndex']);
        Route::get('/data-new-sesion', [CabildosController::class, 'dataGetIndex']);
        Route::post('/saveSesion', [CabildosController::class, 'save']);
        Route::post('/edit-sesion', [CabildosController::class, 'edit']);
        Route::post('/edit-sesion-document', [CabildosController::class, 'editDocument']);
        Route::post('/editSesion', [CabildosController::class, 'editSesion']);
        Route::put('/delete-session/{id}', [CabildosController::class, 'destroy']);
        Route::post('/view-documents', [CabildosController::class, 'viewDocuments']);
        Route::get('/uploads/{file}', [CabildosController::class, 'downloadFile']);
        Route::get('/report-cabildos', [CabildosController::class, 'reportSessions']);
        Route::post('/report-cabildos', [CabildosController::class, 'reportSessions']);
        Route::get('/list-cabildos', [CabildosController::class, 'list']);
        Route::post('/filter-list-cabildos', [CabildosController::class, 'filter']);
        Route::post('/data-list-cabildos', [CabildosController::class, 'index']);
        Route::post('/changeCity', [CabildosController::class, 'changeCity']);



        Route::get('/tipos-de-archivo', [TipoDocumentoController::class, 'index']);
        Route::get('/index_tipo_d',[TipoDocumentoController::class, 'index_tipo_d']);
        Route::post('modal_eliminar_tipoDocumento', [TipoDocumentoController::class, 'modal_eliminar_tipoDocumento'])->name('modal_eliminar_tipoDocumento');
        Route::post('eliminar_tipoDocumento/{id}', [TipoDocumentoController::class, 'destroy'])->name('tipoDocumento.destroy');
        Route::post('/modal_creartipoDocumento', [TipoDocumentoController::class, 'modal_crear_municipio'])->name('modal_crear_tipoDocumento');
        Route::post('/crear_tipoDocumento', [TipoDocumentoController::class, 'store'])->name('tipoDocumento.store');
        Route::post('/editar_tipoDocumento', [TipoDocumentoController::class, 'edit'])->name('tipoDocumento.edit');
        Route::post('/update_tipoDocumento', [TipoDocumentoController::class, 'update'])->name('tipoDocumento.update');
        Route::post('/buscar-tipoDocumento/{nombre}', [TipoDocumentoController::class, 'buscar_tipoDocumento'])->name('buscar_tipoDocumento');

