<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\MyEvent;
use App\Models\Menu;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Artisan;
use App\Models\RutasVue;
use App\Helpers\Equivalencias;

class MainController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->nameDbCent = config('database.connections.centralizado.database');
    }

    public function index()
    {
        $extraerMenus  = $this->extraerMenus();
        $menuSuperior  = $extraerMenus['menu_superior'];
        $menuInferior  = $extraerMenus['menu_inferior'];
        $data_depto    = Session('data_depto'); 
    	return view('principal', compact('menuSuperior','menuInferior','data_depto'));
    }

    public function extraerMenus()
    {
        $menus = [
                    'menu_superior' => '',
                    'menu_inferior' => ''
                 ];

        $conexion = (Session('conexion') == 'centralizado') ? true : false;

        $itemPadres = Menu::leftJoin($this->nameDbCent.'.rutas_vue as tc','tc.id','menus.component')
                            ->where('ubicacion', '!=',null)
                            ->where('status', 1)
                            ->select('menus.id as id','menus.icon','menus.name as name','menus.ubicacion','tc.name as component','tc.path')
                            ->get()
                            ->toArray();
                            // dd($itemPadres);
    	foreach ($itemPadres as $padre) 
        {       
            if($padre['ubicacion'] == 1)
            {                    
                $menus['menu_superior'] .= $this->armaMenuSuperior($padre, 'superior',$conexion);               
            }else{
                $menus['menu_inferior'] .= $this->armaMenuInferior($padre, 'inferior',$conexion);        
            }
    	}
        return $menus;
    }

    public function armaMenuSuperior($raiz, $lugar, $centralizado){
        if(!$centralizado)
        {
            $permisoRaiz = Menu::join('permissions', 'permissions.id_componente', 'menus.id')
                                ->select('permissions.name as permiso')
                                ->where('menus.status', 1)
                                ->where('menus.id', $raiz['id'])
                                ->where('permissions.name', 'LIKE','view_'.'%' )
                                ->first();                                         
        }

        if(Session('conexion') != 'centralizado' && Auth::user()->super_administrador == 1 or $centralizado)
        {
            $condicion = true;
        }else{
            $condicion = Auth::user()->can($permisoRaiz['permiso']);
        }

        $html = '';
        // Si tiene los permisos muestra la raiz
        if($condicion){ 
            $html .= '<li class="nav-item mega-menu dropdown">';
            $html .= $this->getEtiquetaA($raiz['component'], $raiz['name'], $raiz['icon'], $lugar);
            $html .= '</li">';
            $html .= $this->buscarHijos($raiz['id'], $centralizado,'superior');
        }
        return $html;
    } 


    public function armaMenuInferior($raiz, $lugar, $centralizado){
        // dd($raiz);
        if(!$centralizado)
        {
            $permisoRaiz = Menu::join('permissions', 'permissions.id_componente', 'menus.id')
                                ->select('permissions.name as permiso')
                                ->where('menus.status', 1)
                                ->where('menus.id', $raiz['id'])
                                ->where('permissions.name', 'LIKE','view_'.'%' )
                                ->first();                                         
        }

        if(Session('conexion') != 'centralizado' && Auth::user()->super_administrador == 1 or $centralizado)
        {
            $condicion = true;
        }else{
            $condicion = Auth::user()->can($permisoRaiz['permiso']);
        }

        $html = '';
        // Si tiene los permisos muestra la raiz
        if($condicion){ 

            if($raiz['component'] == null){
                $raiz['component'] = 'br'.$raiz['id'];
            }

            $html .= '<li class="nav-item hover">';
            $html .= $this->getEtiquetaA($raiz['component'], $raiz['name'], $raiz['icon'], $lugar,$raiz['id'],$centralizado);
            $html .= '</li">';
            $html .= '<div class="collapse" id="'.$raiz['component'].'">';
            $html .= $this->buscarHijos($raiz['id'], $centralizado,'inferior');
            $html .= '</div>';
        }
        return $html;
    } 

    public function buscarHijos($id, $centralizado,$lugar){

        if($lugar == 'superior'){
            $class_ul = 'dropdown-menu submenu-item';
            $class_li = 'dropdown-submenu';
        }else{
            $class_ul = 'nav flex-column sub-menu';
            $class_li = 'nav-item';
        }
        $html = '';
        // busca si el padre tiene hijos
        $tieneHijos = $this->consultaItemsNivel($id, $centralizado);
        if(count($tieneHijos) > 0){
            $html .= '<ul class="'.$class_ul.'">';
            foreach($tieneHijos as $hijo){
                if(Session('conexion') != 'centralizado' && Auth::user()->super_administrador == 1 or $centralizado)
                {
                    $condicion = true;
                }else{
                    $condicion = Auth::user()->can($hijo['permiso']);
                }

                if($condicion){ 
                    // pregunta si tiene hijos en el siguiente nivel
                    $tieneNietos = $this->consultaItemsNivel($hijo['id'], $centralizado);
                    if(count($tieneNietos) > 0){
                        $html .= '<li class="'.$class_li.'">'.$this->getRuta($hijo['component'], $hijo['name'], true,$lugar,true).'</li">';
                        if($lugar != 'superior'){
                            $html .= '<div class="collapse" id="'.$hijo['component'].'">';
                        }
                        $html .= $this->buscarHijos($hijo['id'], $centralizado,$lugar);
                        if($lugar != 'superior'){
                            $html .= '</div>';     
                        }
                    }
                    else{
                        $html .= '<li class="'.$class_li.'">'.$this->getRuta($hijo['component'], $hijo['name'], false,$lugar,false).'</li">';
                    }
                }
            }
            $html .= '</ul>';
        }
        return $html;
    } 
  
    public function getEtiquetaA($componente, $nombre, $icono, $lugar, $id_hijo = '',$centralizado = ''){
       
        $estilo = '';
        $tamano = 'fa-lg';
        $clase = '';
        $extra = '';
        $id_dinamico = '';
        $extra_inferior = '';
        $class_inferior = '';
        $class_superior= '';
        $arrow = '';
        $clase_title = "";

        if($lugar== "superior"){
            $estilo = 'style=""';
            $tamano = 'fa-2x'; 
            $extra = 'data-toggle="dropdown"';
            $class_superior = 'color_blanco_li';
        }else{
            $clase = 'menu-icon color-icon';
            $id_dinamico = 'href="'.$nombre.'"';
            $extra_inferior = 'id="color_icocno" data-toggle="collapse" aria-expanded="false" aria-controls="'.$componente.'"';
            $class_inferior = 'collapse color_fondo_nav';
            $clase_title = "menu-title1";

            if($this->consultarCantidadHijos($id_hijo,$centralizado)){
                $arrow = '<i class="menu-arrow"></i>';
            }
        }
        $icono = ($icono == null) ? 'fa fa-circle-o-notch' : $icono;

        return '<router-link  data-target="#'.$componente.'" :to="{ name: \''.$componente.'\'}" 
                    class=" nav-link '. $class_superior.' '.$class_inferior.'" '.$extra.' '. $extra_inferior.' >
                    
                    <i class="'.$icono.' '.$tamano.' ' .$clase.'" '.$estilo.'></i>&nbsp;
                         <span class="'.$clase_title.'" '.$estilo.'>'.$nombre.'</span>
                    '.$arrow.'
                </router-link>';
    }


    public function getRuta($componente, $nombre, $despliegue,$lugar,$arrow){
        if($lugar == 'superior')
        {
            ($despliegue)? $dropdownToggle = 'dropdown-toggle' : $dropdownToggle = '';
            return '<router-link :to="{ name: \''.$componente.'\'}" class="dropdown-item '.$dropdownToggle.'" data-toggle="dropdown">
                            <i class="fa fa-ellipsis-h" style="color:#aac6ec"></i>&nbsp;&nbsp;'.$nombre.'
                    </router-link>';
        }else{
            $arrow2 = '';

            if($arrow){
                $arrow2 = '<i class="menu-arrow"></i>';
            }

            return '<router-link id="'.$componente.'"  data-target="#'.$componente.'" :to="{ name: \''.$componente.'\'}" class="color_fondo_nav nav-link collapse" data-toggle="collapse" aria-expanded="false" aria-controls="'.$componente.'">
                                    <span class="menu-title">'.$nombre.'</span>
                            '.$arrow2.'
                    </router-link>';
        }
    }

  
    public function consultaItemsNivel($id, $centralizado){
        if($centralizado)
        {
            $hijos = Menu::leftJoin($this->nameDbCent.'.rutas_vue as tc','tc.id','menus.component')
                            ->where('menus.menu_id','=', $id)
                            ->where('menus.status', 1)
                            ->select('menus.id','menus.name','tc.name as component','menus.menu_id')
                            ->get()
                            ->toArray();
        }else{
            $hijos = Menu::join($this->nameDbCent.'.rutas_vue as tc','tc.id','menus.component')
                            ->where('menus.menu_id','=', $id)
                            ->where('menus.status', 1)
                            ->join('permissions', 'permissions.id_componente', 'menus.id')
                            ->select('menus.id','menus.name','tc.name as component','permissions.name as permiso', 'menus.menu_id')
                            ->where('permissions.name', 'LIKE','view_'.'%' )
                            ->get()
                            ->toArray();
        }      
      
        return $hijos;
    }

    public function consultarCantidadHijos($id, $centralizado){
        $response = false;
        $tieneHijos = $this->consultaItemsNivel($id, $centralizado);
        if(count($tieneHijos) > 0){
           $response = true;
        }
        return $response;
    }
  
}
