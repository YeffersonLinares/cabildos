<template>
    <main class="main col-12">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item" v-for="ruta in arrayRuta" :key="ruta">
                <router-link :to="{name:ruta.componente}">
                    <span v-if="ruta.nombre != ''">{{ruta.nombre}}</span>
                    <span v-else>{{ruta.componente}}</span>
                </router-link>
            </li>
        </ol>

        <div class="container-fluid">
            <!-- Tabla Listado -->
            <template v-if="listado==1">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Parametrizar Menus
                        <div v-permiso="'add_parametrizar_menus'">
                            <template>                     
                                <button type="button" @click="ingresarMenu('menu','create')" class="btn btn-secondary">
                                    <i class="icon-plus"></i> Nuevo
                                </button>
                            </template>
                        </div>
                    </div>
                    <template>
                        <div  v-permiso="'view_parametrizar_menus'">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control col-md-3" v-model="criterio">
                                                <option value="name">Nombre</option>
                                                <option value="component">Nombre Ruta Componente</option>
                                            </select>
                                            <input type="text" v-model="buscar" @keyup="listarMenu(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarMenu(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group table-responsive">
                                    <div class="spinner" style="display: none">
                                        <div class="sk-chase">
                                            <div class="sk-chase-dot"></div>
                                            <div class="sk-chase-dot"></div>
                                            <div class="sk-chase-dot"></div>
                                            <div class="sk-chase-dot"></div>
                                            <div class="sk-chase-dot"></div>
                                            <div class="sk-chase-dot"></div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Nombre</th>
                                                <th>Nombre Ruta Component</th>
                                                <th>Icono</th>
                                                <th>Menu Padre</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="arrayMenus.length != 0">
                                                    <tr v-for="menu in arrayMenus" :key="menu.id">  
                                                    <td>
                                                        <!-- <template v-if="$can('edit_parametrizarMenus')"> -->
                                                        <template >
                                                            <button type="button" title="Editar" @click="ingresarMenu('menu','update',menu)" class="btn btn-inverse-warning btn-sm">
                                                            <i class="typcn typcn-edit"></i>
                                                            </button> &nbsp;
                                                        </template>
                                                        <!-- <template v-if="$can('delete_parametrizarMenus')"> -->
                                                        <template>
                                                            <template v-if="menu.status" class="preview">
                                                                <button type="button" title="Inactivar" class="btn btn-inverse-danger btn-sm" @click="cambiarEstado(menu.id, menu.status)">
                                                                    <i class="typcn typcn-trash"></i>
                                                                </button>
                                                            </template>
                                                            <template v-else>
                                                                <button type="button" title="Activar" class="btn btn-inverse-success btn-sm" @click="cambiarEstado(menu.id, menu.status)">
                                                                    <i class="typcn typcn-tick-outline"></i>
                                                                </button>
                                                            </template>
                                                        </template>
                                                    </td>
                                                    <td v-text="menu.name"></td>
                                                    <td v-text="menu.component"></td>
                                                    <td>
                                                        <i :class="menu.icon"></i>
                                                    </td>
                                                    <td v-text="menu.padre"></td>
                                                    <td>
                                                        <div v-if="menu.status">
                                                            <span class="badge badge-outline-success">Activo</span>
                                                        </div>
                                                        <div v-else>
                                                            <span class="badge badge-outline-danger">Inactivo</span>
                                                        </div>
                                                    </td>
                                                </tr> 
                                            </template>   
                                            <template v-else>
                                                <tr>
                                                    <td colspan="7"><span>No se encontraron resultados</span></td>
                                                </tr>    
                                            </template>                           
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <nav>
                                        <ul class="pagination">
                                            <li class="page-item" v-if="pagination.current_page > 1">
                                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                            </li>
                                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                            </li>
                                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            <!-- Fin Tabla Listado -->
            <!-- Formulario de Ingreso y Actualizacion--> 
            <template v-if="listado==2">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                    <h4 class="card-title" v-text="tituloFormulario"></h4>
                    <p class="card-description">
                        Datos Basicos
                    </p>
                    <form class="forms-sample">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                        </div>
                        <div class="form-group">
                            <label>Nombre de la ruta del Componente</label>
                            <!-- <input type="text" v-model="componente" class="form-control" placeholder="Componente"> -->
                            <v-select 
                                        v-model="componente"
                                        label="dato_com"
                                        :options="arrayRutasVue"
                                        placeholder="Seleccione..."
                                        :value="componente"
                            >
                                  
                            </v-select>
                        </div>     
                        <div class="form-group row" v-show="esUltimo==false">
                                <label class="col-sm-3 col-form-label">Menú Padre</label> 
                                <label class="col-sm-9 col-form-label" v-html="menu_padre.name+' &nbsp; - &nbsp;<small>'+menu_padre.ruta+'<small>'"></label> 
                        </div>                                 
                        <div class="form-group"  v-show="esUltimo==true">
                            <label>Menu Padre</label>
                            <div>
                                <v-select class="v-select" append-to-body :calculate-position="withPopper"
                                        v-model="menu_padre"
                                        label="name"
                                        :options="menuPadre"
                                        placeholder="Seleccione..."
                                        @input="cambiarIcono" 
                                        :value="menu_padre"
                                        :clearable="false"
                                    >
                                    <template v-slot:option="item"> {{item.name}} &nbsp; - &nbsp;<small>{{item.ruta}}</small> </template>
                                    <template v-slot:selected-option="item"> {{item.name}} &nbsp; - &nbsp;<small>{{item.ruta}}</small> </template>
                                    <span slot="no-options"> Oops! No hay Menú Padre para mostrar. </span>
                                    </v-select>
                            </div>
                        </div>
                        <div class="form-group" v-show="mostrarIcono">
                            <label>Icono</label>
                            <v-select class="v-select" append-to-body :calculate-position="withPopper"
                                        v-model="icono"
                                        label="nombre"
                                        :options="arrayIcons"
                                        placeholder="Seleccione..."
                                        :value="icono"
                                    >
                                    <template v-slot:option='item'> <i :class="['mr-2', 'em', item.id]"></i> {{item.nombre}} </template>
                                    <template v-slot:selected-option='item'> <i :class="['mr-2', 'em', item.id]"></i> {{item.nombre}} </template>
                                    <span slot="no-options"> Oops! No hay Iconos para mostrar. </span>
                            </v-select>
                        </div>     
                         <div class="form-group" v-show="mostrarUbicacion">
                            <label>Ubicacion del menú</label>
                            <div>
                                <v-select class="v-select" append-to-body :calculate-position="withPopper"
                                        v-model="ubicacion_menu"
                                        label="nombre"
                                        :options="arrayUbicacion"
                                        placeholder="Seleccione..."
                                        :value="ubicacion_menu"
                                    >
                                <span slot="no-options"> Oops! No hay Menú Padre para mostrar. </span>
                                </v-select> 
                                
                            </div>
                        </div>
                        <div v-show="errorFormulario" class="row justify-content-center">       
                            <div class="alert alert-danger col-md-10  " role="alert">
                            <p class="mb-0"  v-for="error in errorMensajesFormularios" :key="error" v-text="error"></p>
                            </div>
                        </div>
                        <div class="form-group" >
                            <div  class="col-md-6" v-permiso="'add_parametrizarMenus'">
                                <button v-if="tipoAccion==1" @click="createMenu()" type="button" class="btn btn-primary mr-2">Guardar</button>
                                <button v-if="tipoAccion==1" class="btn btn-light" @click="cancelarIngreso()">Cancelar</button>
                            </div>
                            <div  class="col-md-6" v-permiso="'edit_parametrizarMenus'">
                                <button v-if="tipoAccion==2" @click="updateMenu()" type="button" class="btn btn-primary mr-2">Actualizar</button>
                                <button v-if="tipoAccion==2" class="btn btn-light" @click="cancelarIngreso()">Cancelar</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            </template>
            <!-- Fin Formulario de Asignacion de Permisos -->
        </div>
    </main>
</template>

<script>
    import { createPopper } from '@popperjs/core';

    export default {
        props: ['ruta'],
        data (){ // Resive los Datos que llegan
            return {
                // Inicialización de Variables
                idMenu : 0,
                arrayMenus : [],
                listado : 1,
                tituloFormulario : '',
                tipoAccion : 0,
                bandera : 0,
                errorFormulario : 0,
                arrayPermisos: [],
                menuPadre: [],
                errorMensajesFormularios : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'name',
                buscar : '',
                roleName: '',
                roleId: '',
                nombre: '',
                componente: '',
                icono: '',
                permiso: '',
                menu_padre :'',
               
                ubicacion_menu: '',
                mostrarIcono: 1,
                mostrarUbicacion: 1,
                arrayIcons : [],
                arrayUbicacion : 
                [
                    { id: 1, nombre: 'Superior' },
                    { id: 2, nombre: 'Inferior' }
                ],
                esUltimo: true,
                appendToBody: { type: Boolean, default: false },            // variables para v-select
                placement: 'bottom',                                        // variables para v-select
                arrayRuta: [], 
                arrayRutasVue: []
            }
        },
        components:{},
        computed:{ // Almacenamos en Chache la Paginación
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             
            },   
        },
        methods : {
            withPopper (dropdownList, component, {width}) { // OK metodo complementario para v-select
                dropdownList.style.width = width;
                const popper = createPopper(component.$refs.toggle, dropdownList, {
                placement: this.placement,
                modifiers: [
                    {
                    name: 'offset', options: {
                        offset: [0, -1]
                    }
                    },
                    {
                    name: 'toggleClass',
                    enabled: true,
                    phase: 'write',
                    fn ({state}) {
                        component.$el.classList.toggle('drop-up', state.placement === 'top')
                    },
                    }]
                });
                return () => popper.destroy();
            },
            cambiarPagina(page,buscar,criterio){ // Metodo encargado de realizar el cambio de pagina
                //Actualiza la página actual
                this.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                this.listarMenu(page,buscar,criterio);
            },
            listarMenu (page,buscar,criterio){ // Metodo encargado de enviar los datos al listado
                $('.spinner').css('display', 'block');

                var url= this.ruta + '/menus?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then((response)  => {
                    this.arrayMenus = response.data.menu.data;
                    this.pagination= response.data.pagination;

                    $('.spinner').css('display', 'none');

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cancelarIngreso(){ // Metodo encargado de cancelar las acciones en los formularios
                this.listado=1;
                this.tituloFormulario = "";
                this.componente = '';
                this.nombre = '';     
                this.ruta = '';      
                this.menu_padre = '';     
                this.icono = '';      
                this.ubicacion_menu = '';  
                this.esUltimo = true;
                this.mostrarIcono = 1;
                this.mostrarUbicacion = 1;            

            },
            ingresarMenu (modelo, accion, data = []){ // Metodo encargado de cargar los formularios de crear y actualizar
                this.listado=2;
                switch(modelo){
                    case "menu":
                    {
                        switch(accion){
                            case 'create':
                            {   
                            
                                this.consultarMenusPadre();
                                this.getRutasVue();
                                this.tituloFormulario = "Registrar Menú";
                                this.nombre = '';
                                this.componente = '';
                                this.icono = '';
                                this.permiso = '';
                                this.tipoAccion = 1;
                                this.mostrarIcono = 1;
                                this.mostrarUbicacion = 1;
                                break;
                            }
                            case 'update':
                            {
                                console.log('aa',data);
                                this.consultarMenusPadre(data.id);
                                this.getRutasVue();
                                this.mostrarIcono = 1;
                                this.mostrarUbicacion = 1;
                                this.tipoAccion = 2;
                                this.tituloFormulario = "Actualizar el menú";
                                this.idMenu = data['id'];
                                this.nombre = data['name'];
                                this.componente = data['component'];

                                if(data['component'] != null){
                                    this.componente =  {
                                            id: data['id_ruta'],
                                            name: data['component'],
                                            component: data['component2'],
                                            path: data['path'],            
                                            estado: data['estado2'],
                                            created_at: '',
                                            updated_at: '',
                                            dato_com: data['dato_com']
                                        };
                                }

                                if(data['menu_id'] != null){ // si existe padre
                                    this.menu_padre = {
                                        id: data['menu_id'],
                                        name: data['padre'],
                                        component: '',
                                        ruta: data['rutaPadre'],
                                        icon: '',
                                        permiso: '',
                                        status: '',
                                        menu_id: '',
                                        created_at: '',
                                        updated_at: '',
                                    };
                                    this.mostrarIcono = 0;
                                    this.mostrarUbicacion = 0;
                                }
                                else{   // si es raiz
                                    this.icono = {
                                        id: data['icon'],
                                        nombre: data['icon'].substring(6,data['icon'].length)
                                    };
                                    this.ubicacion_menu = {
                                        id: data['ubicacion'],
                                        nombre: (data['ubicacion'] == 1)? 'Superior':'Inferior'
                                    };
                                    this.menu_padre = {
                                        id: '',
                                        name: 'No tiene',
                                        component: '',
                                        ruta: 'Encabezado Principal',
                                        icon: "",
                                        permiso: '',
                                        status: '',
                                        menu_id: '',
                                        created_at: '',
                                        updated_at: '',
                                    };  
                                                                   
                                }
                                this.verificaUltimoHijo(data['id']);
                                break;
                            }
                          
                        }
                    }
                }
            },
            cambiarEstado(id, estado) { // Metodo encargado de desactivar los registros 
                var title = estado == 1 ? 'Inactivar' : 'Activar';
                var title_es = estado == 1 ? 'Desactivado' : 'Activado';
                var estado_enviar = estado == 1 ? 0 : 1;

                Swal.fire({
                    title: 'Esta seguro de '+title+' este Menu?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            var url= this.ruta  + '/menus/cambiarEstado';
                            axios.put(url,{
                                'id': id,
                                'estado': estado_enviar
                            }).then((response) => {
                                this.listarMenu(1,'','nombre');
                                Swal.fire(
                                title_es+'!',
                                'El Menú ha sido '+title_es+' con éxito.',
                                'success'
                                )
                            }).catch(function (error) {
                                console.log(error);
                            });
                        } else if (
                            // Read more about handling dismissals
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            
                        }
                    }) 
            },
            createMenu() { // Metodo encargado de realizar el envio de datos para la creacion
                if(this.validacionFormulario()){
                    return;
                }
                
                var url=  this.ruta + '/menus/create';
                axios.post(url,{
                    'name' : this.nombre,
                    'component' : this.componente,
                    'icon' : this.icono.id,
                    'ubicacion_menu' : this.ubicacion_menu.id,
                    'id_padre': this.menu_padre.id,
                    'ruta_padre' : this.menu_padre.ruta
                }).then((response) => {
                    this.cancelarIngreso();
                    this.listarMenu(1,'','nombre');
                    this.consultarPermisosPadre();
                    this.consultarMenusPadre();

                    swal.fire({
                        icon: response.data.status,
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1750
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            updateMenu() { // Metodo encargado de realizar el envio de datos para la actualizacion
                if(this.validacionFormulario()){
                    return;
                }
               
                var url= this.ruta  + '/menus/update';
                axios.put(url,{
                    'nombre' : this.nombre,
                    'componente' : this.componente,
                    'icono' : this.icono.id,
                    'ubicacion' : this.ubicacion_menu.id,
                    'menu_padre': this.menu_padre,
                    'id' : this.idMenu
                }).then((response) =>  {
                    if(response.data.exito){  
                        
                        swal.fire({
                            icon: 'success',
                            title: 'Menú Actualizado con exito.',
                            showConfirmButton: false,
                            timer: 1750
                        });
                  
                        this.cancelarIngreso();
                        this.listarMenu(1,'','nombre');
                        this.consultarPermisosPadre();
                        this.consultarMenusPadre();
                    }
                    else{   
                        var msj = "";
                        var json = response.data.errores
                        for (var i in json) { msj += json[i]+"<br>" }
                        swal.fire({
                            icon: 'error',
                            title: 'Error al actualizar Menú.',
                            text: msj,
                            showConfirmButton: false,
                            timer: 1750
                        });
                        return;
                    }


                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validacionFormulario() { // Metodo encargado de la validacion de los campos de los formularios
                this.errorFormulario = 0;
                this.errorMensajesFormularios = [];
                if(this.menu_padre.id == '' && this.ubicacion_menu == '') this.errorMensajesFormularios.push("El campo Ubicacion es obligatorio.");
                if(this.menu_padre.id == '' && this.icono == '') this.errorMensajesFormularios.push("El campo Icono es obligatorio.");
                if(!this.nombre) this.errorMensajesFormularios.push("El campo Nombre es Obligatorio.");
                if(!this.componente) this.errorMensajesFormularios.push("El campo Nombre de la Ruta del Componente es Obligatorio.");
                if(this.errorMensajesFormularios.length) this.errorFormulario = 1;
                
                return this.errorFormulario;
            },
            consultarMenusPadre(id = null)
            {
                var url= this.ruta  + '/menus/consultarMenuPadre/'+id;

                axios.get(url).then((response) => {
                    this.menuPadre = response.data.menus;
                     let newDad = {
                        id: '',
                        name: 'Nuevo Encabezado Principal',
                        component: '',
                        ruta: '',
                        icon: '',
                        id_permiso: '',
                        status: '',
                        menu_id: '',
                        ubicacion: '',
                        modificado_por: '',
                        creado_por: '',
                        created_at: '',
                        updated_at: '',
                    };
                    this.menuPadre.unshift(newDad); //inserta el elemento 

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            consultarPermisosPadre()
            {
                //Consultar Entidades
                var url= this.ruta +  '/menus/consultarPermisosPadre';

                axios.get(url).then((response) => {
                    this.menuPermiso = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarIcono()
            {
                if(this.menu_padre.id > 0){
                    this.mostrarIcono = 0;
                    this.mostrarUbicacion = 0;
                }
                else{
                    this.mostrarIcono = 1;
                    this.mostrarUbicacion = 1;
                }
             
            },
            cargarIcons(){ // OK Método encargado de cargar el arrayIcons
                this.arrayIcons = 
                    [
                        { id: 'fa fa-align-left',  nombre: 'align-left' },
                        { id: 'fa fa-align-right',  nombre: 'align-right' },
                        { id: 'fa fa-amazon',  nombre: 'amazon' },
                        { id: 'fa fa-ambulance',  nombre: 'ambulance' },
                        { id: 'fa fa-anchor',  nombre: 'anchor' },
                        { id: 'fa fa-android',  nombre: 'android' },
                        { id: 'fa fa-angellist',  nombre: 'angellist' },
                        { id: 'fa fa-angle-double-down',  nombre: 'angle-double-down' },
                        { id: 'fa fa-angle-double-left',  nombre: 'angle-double-left' },
                        { id: 'fa fa-angle-double-right',  nombre: 'angle-double-right' },
                        { id: 'fa fa-angle-double-up',  nombre: 'angle-double-up' },
                        { id: 'fa fa-angle-left',  nombre: 'angle-left' },
                        { id: 'fa fa-angle-right',  nombre: 'angle-right' },
                        { id: 'fa fa-angle-up',  nombre: 'angle-up' },
                        { id: 'fa fa-apple',  nombre: 'apple' },
                        { id: 'fa fa-archive',  nombre: 'archive' },
                        { id: 'fa fa-area-chart',  nombre: 'area-chart' },
                        { id: 'fa fa-arrow-circle-down',  nombre: 'arrow-circle-down' },
                        { id: 'fa fa-arrow-circle-left',  nombre: 'arrow-circle-left' },
                        { id: 'fa fa-arrow-circle-o-down',  nombre: 'arrow-circle-o-down' },
                        { id: 'fa fa-arrow-circle-o-left',  nombre: 'arrow-circle-o-left' },
                        { id: 'fa fa-arrow-circle-o-right',  nombre: 'arrow-circle-o-right' },
                        { id: 'fa fa-arrow-circle-o-up',  nombre: 'arrow-circle-o-up' },
                        { id: 'fa fa-arrow-circle-right',  nombre: 'arrow-circle-right' },
                        { id: 'fa fa-arrow-circle-up',  nombre: 'arrow-circle-up' },
                        { id: 'fa fa-arrow-down',  nombre: 'arrow-down' },
                        { id: 'fa fa-arrow-left',  nombre: 'arrow-left' },
                        { id: 'fa fa-arrow-right',  nombre: 'arrow-right' },
                        { id: 'fa fa-arrow-up',  nombre: 'arrow-up' },
                        { id: 'fa fa-arrows',  nombre: 'arrows' },
                        { id: 'fa fa-arrows-alt',  nombre: 'arrows-alt' },
                        { id: 'fa fa-arrows-h',  nombre: 'arrows-h' },
                        { id: 'fa fa-arrows-v',  nombre: 'arrows-v' },
                        { id: 'fa fa-asterisk',  nombre: 'asterisk' },
                        { id: 'fa fa-at',  nombre: 'at' },
                        { id: 'fa fa-automobile',  nombre: 'automobile' },
                        { id: 'fa fa-backward',  nombre: 'backward' },
                        { id: 'fa fa-balance-scale',  nombre: 'balance-scale' },
                        { id: 'fa fa-ban',  nombre: 'ban' },
                        { id: 'fa fa-bank',  nombre: 'bank' },
                        { id: 'fa fa-bar-chart',  nombre: 'bar-chart' },
                        { id: 'fa fa-bar-chart-o',  nombre: 'bar-chart-o' },
                        { id: 'fa fa-battery-full',  nombre: 'battery-full' },
                        { id: 'fa fa-beer',  nombre: 'beer' },
                        { id: 'fa fa-behance',  nombre: 'behance' },
                        { id: 'fa fa-behance-square',  nombre: 'behance-square' },
                        { id: 'fa fa-bell',  nombre: 'bell' },
                        { id: 'fa fa-bell-o',  nombre: 'bell-o' },
                        { id: 'fa fa-bell-slash',  nombre: 'bell-slash' },
                        { id: 'fa fa-bell-slash-o',  nombre: 'bell-slash-o' },
                        { id: 'fa fa-bicycle',  nombre: 'bicycle' },
                        { id: 'fa fa-binoculars',  nombre: 'binoculars' },
                        { id: 'fa fa-birthday-cake',  nombre: 'birthday-cake' },
                        { id: 'fa fa-bitbucket',  nombre: 'bitbucket' },
                        { id: 'fa fa-bitbucket-square',  nombre: 'bitbucket-square' },
                        { id: 'fa fa-bitcoin',  nombre: 'bitcoin' },
                        { id: 'fa fa-black-tie',  nombre: 'black-tie' },
                        { id: 'fa fa-bold',  nombre: 'bold' },
                        { id: 'fa fa-bolt',  nombre: 'bolt' },
                        { id: 'fa fa-bomb',  nombre: 'bomb' },
                        { id: 'fa fa-book',  nombre: 'book' },
                        { id: 'fa fa-bookmark',  nombre: 'bookmark' },
                        { id: 'fa fa-bookmark-o',  nombre: 'bookmark-o' },
                        { id: 'fa fa-briefcase',  nombre: 'briefcase' },
                        { id: 'fa fa-btc',  nombre: 'btc' },
                        { id: 'fa fa-bug',  nombre: 'bug' },
                        { id: 'fa fa-building',  nombre: 'building' },
                        { id: 'fa fa-building-o',  nombre: 'building-o' },
                        { id: 'fa fa-bullhorn',  nombre: 'bullhorn' },
                        { id: 'fa fa-bullseye',  nombre: 'bullseye' },
                        { id: 'fa fa-bus',  nombre: 'bus' },
                        { id: 'fa fa-cab',  nombre: 'cab' },
                        { id: 'fa fa-calendar',  nombre: 'calendar' },
                        { id: 'fa fa-camera',  nombre: 'camera' },
                        { id: 'fa fa-car',  nombre: 'car' },
                        { id: 'fa fa-caret-up',  nombre: 'caret-up' },
                        { id: 'fa fa-cart-plus',  nombre: 'cart-plus' },
                        { id: 'fa fa-cc',  nombre: 'cc' },
                        { id: 'fa fa-cc-amex',  nombre: 'cc-amex' },
                        { id: 'fa fa-cc-jcb',  nombre: 'cc-jcb' },
                        { id: 'fa fa-cc-paypal',  nombre: 'cc-paypal' },
                        { id: 'fa fa-cc-stripe',  nombre: 'cc-stripe' },
                        { id: 'fa fa-cc-visa',  nombre: 'cc-visa' },
                        { id: 'fa fa-chain',  nombre: 'chain' },
                        { id: 'fa fa-check',  nombre: 'check' },
                        { id: 'fa fa-chevron-left',  nombre: 'chevron-left' },
                        { id: 'fa fa-chevron-right',  nombre: 'chevron-right' },
                        { id: 'fa fa-chevron-up',  nombre: 'chevron-up' },
                        { id: 'fa fa-child',  nombre: 'child' },
                        { id: 'fa fa-chrome',  nombre: 'chrome' },
                        { id: 'fa fa-circle',  nombre: 'circle' },
                        { id: 'fa fa-circle-o',  nombre: 'circle-o' },
                        { id: 'fa fa-circle-o-notch',  nombre: 'circle-o-notch' },
                        { id: 'fa fa-circle-thin',  nombre: 'circle-thin' },
                        { id: 'fa fa-clipboard',  nombre: 'clipboard' },
                        { id: 'fa fa-clock-o',  nombre: 'clock-o' },
                        { id: 'fa fa-clone',  nombre: 'clone' },
                        { id: 'fa fa-close',  nombre: 'close' },
                        { id: 'fa fa-cloud',  nombre: 'cloud' },
                        { id: 'fa fa-cloud-download',  nombre: 'cloud-download' },
                        { id: 'fa fa-cloud-upload',  nombre: 'cloud-upload' },
                        { id: 'fa fa-cny',  nombre: 'cny' },
                        { id: 'fa fa-code',  nombre: 'code' },
                        { id: 'fa fa-code-fork',  nombre: 'code-fork' },
                        { id: 'fa fa-codepen',  nombre: 'codepen' },
                        { id: 'fa fa-coffee',  nombre: 'coffee' },
                        { id: 'fa fa-cog',  nombre: 'cog' },
                        { id: 'fa fa-cogs',  nombre: 'cogs' },
                        { id: 'fa fa-columns',  nombre: 'columns' },
                        { id: 'fa fa-comment',  nombre: 'comment' },
                        { id: 'fa fa-comment-o',  nombre: 'comment-o' },
                        { id: 'fa fa-commenting',  nombre: 'commenting' },
                        { id: 'fa fa-commenting-o',  nombre: 'commenting-o' },
                        { id: 'fa fa-comments',  nombre: 'comments' },
                        { id: 'fa fa-comments-o',  nombre: 'comments-o' },
                        { id: 'fa fa-compass',  nombre: 'compass' },
                        { id: 'fa fa-compress',  nombre: 'compress' },
                        { id: 'fa fa-connectdevelop',  nombre: 'connectdevelop' },
                        { id: 'fa fa-contao',  nombre: 'contao' },
                        { id: 'fa fa-copy',  nombre: 'copy' },
                        { id: 'fa fa-copyright',  nombre: 'copyright' },
                        { id: 'fa fa-creative-commons',  nombre: 'creative-commons' },
                        { id: 'fa fa-credit-card',  nombre: 'credit-card' },
                        { id: 'fa fa-crop',  nombre: 'crop' },
                        { id: 'fa fa-crosshairs',  nombre: 'crosshairs' },
                        { id: 'fa fa-css3',  nombre: 'css3' },
                        { id: 'fa fa-cube',  nombre: 'cube' },
                        { id: 'fa fa-cubes',  nombre: 'cubes' },
                        { id: 'fa fa-cut',  nombre: 'cut' },
                        { id: 'fa fa-cutlery',  nombre: 'cutlery' },
                        { id: 'fa fa-dashboard',  nombre: 'dashboard' },
                        { id: 'fa fa-dashcube',  nombre: 'dashcube' },
                        { id: 'fa fa-database',  nombre: 'database' },
                        { id: 'fa fa-dedent',  nombre: 'dedent' },
                        { id: 'fa fa-delicious',  nombre: 'delicious' },
                        { id: 'fa fa-desktop',  nombre: 'desktop' },
                        { id: 'fa fa-deviantart',  nombre: 'deviantart' },
                        { id: 'fa fa-diamond',  nombre: 'diamond' },
                        { id: 'fa fa-digg',  nombre: 'digg' },
                        { id: 'fa fa-dollar',  nombre: 'dollar' },
                        { id: 'fa fa-download',  nombre: 'download' },
                        { id: 'fa fa-dribbble',  nombre: 'dribbble' },
                        { id: 'fa fa-dropbox',  nombre: 'dropbox' },
                        { id: 'fa fa-drupal',  nombre: 'drupal' },
                        { id: 'fa fa-edit',  nombre: 'edit' },
                        { id: 'fa fa-eject',  nombre: 'eject' },
                        { id: 'fa fa-ellipsis-h',  nombre: 'ellipsis-h' },
                        { id: 'fa fa-ellipsis-v',  nombre: 'ellipsis-v' },
                        { id: 'fa fa-empire',  nombre: 'empire' },
                        { id: 'fa fa-envelope',  nombre: 'envelope' },
                        { id: 'fa fa-envelope-o',  nombre: 'envelope-o' },
                        { id: 'fa fa-eur',  nombre: 'eur' },
                        { id: 'fa fa-euro',  nombre: 'euro' },
                        { id: 'fa fa-exchange',  nombre: 'exchange' },
                        { id: 'fa fa-exclamation',  nombre: 'exclamation' },
                        { id: 'fa fa-exclamation-circle',  nombre: 'exclamation-circle' },
                        { id: 'fa fa-exclamation-triangle',  nombre: 'exclamation-triangle' },
                        { id: 'fa fa-expand',  nombre: 'expand' },
                        { id: 'fa fa-expeditedssl',  nombre: 'expeditedssl' },
                        { id: 'fa fa-external-link',  nombre: 'external-link' },
                        { id: 'fa fa-external-link-square',  nombre: 'external-link-square' },
                        { id: 'fa fa-eye',  nombre: 'eye' },
                        { id: 'fa fa-eye-slash',  nombre: 'eye-slash' },
                        { id: 'fa fa-eyedropper',  nombre: 'eyedropper' },
                        { id: 'fa fa-facebook',  nombre: 'facebook' },
                        { id: 'fa fa-facebook-f',  nombre: 'facebook-f' },
                        { id: 'fa fa-facebook-official',  nombre: 'facebook-official' },
                        { id: 'fa fa-facebook-square',  nombre: 'facebook-square' },
                        { id: 'fa fa-fast-backward',  nombre: 'fast-backward' },
                        { id: 'fa fa-fast-forward',  nombre: 'fast-forward' },
                        { id: 'fa fa-fax',  nombre: 'fax' },
                        { id: 'fa fa-feed',  nombre: 'feed' },
                        { id: 'fa fa-female',  nombre: 'female' },
                        { id: 'fa fa-fighter-jet',  nombre: 'fighter-jet' },
                        { id: 'fa fa-file',  nombre: 'file' },
                        { id: 'fa fa-file-archive-o',  nombre: 'file-archive-o' },
                        { id: 'fa fa-file-audio-o',  nombre: 'file-audio-o' },
                        { id: 'fa fa-file-code-o',  nombre: 'file-code-o' },
                        { id: 'fa fa-file-excel-o',  nombre: 'file-excel-o' },
                        { id: 'fa fa-file-image-o',  nombre: 'file-image-o' },
                        { id: 'fa fa-file-movie-o',  nombre: 'file-movie-o' },
                        { id: 'fa fa-file-o',  nombre: 'file-o' },
                        { id: 'fa fa-file-pdf-o',  nombre: 'file-pdf-o' },
                        { id: 'fa fa-file-photo-o',  nombre: 'file-photo-o' },
                        { id: 'fa fa-file-picture-o',  nombre: 'file-picture-o' },
                        { id: 'fa fa-file-powerpoint-o',  nombre: 'file-powerpoint-o' },
                        { id: 'fa fa-file-sound-o',  nombre: 'file-sound-o' },
                        { id: 'fa fa-file-text',  nombre: 'file-text' },
                        { id: 'fa fa-file-text-o',  nombre: 'file-text-o' },
                        { id: 'fa fa-file-video-o',  nombre: 'file-video-o' },
                        { id: 'fa fa-file-word-o',  nombre: 'file-word-o' },
                        { id: 'fa fa-file-zip-o',  nombre: 'file-zip-o' },
                        { id: 'fa fa-files-o',  nombre: 'files-o' },
                        { id: 'fa fa-film',  nombre: 'film' },
                        { id: 'fa fa-filter',  nombre: 'filter' },
                        { id: 'fa fa-fire',  nombre: 'fire' },
                        { id: 'fa fa-fire-extinguisher',  nombre: 'fire-extinguisher' },
                        { id: 'fa fa-firefox',  nombre: 'firefox' },
                        { id: 'fa fa-flag',  nombre: 'flag' },
                        { id: 'fa fa-flag-checkered',  nombre: 'flag-checkered' },
                        { id: 'fa fa-flag-o',  nombre: 'flag-o' },
                        { id: 'fa fa-flash',  nombre: 'flash' },
                        { id: 'fa fa-flask',  nombre: 'flask' },
                        { id: 'fa fa-flickr',  nombre: 'flickr' },
                        { id: 'fa fa-floppy-o',  nombre: 'floppy-o' },
                        { id: 'fa fa-folder',  nombre: 'folder' },
                        { id: 'fa fa-folder-o',  nombre: 'folder-o' },
                        { id: 'fa fa-folder-open',  nombre: 'folder-open' },
                        { id: 'fa fa-folder-open-o',  nombre: 'folder-open-o' },
                        { id: 'fa fa-font',  nombre: 'font' },
                        { id: 'fa fa-fonticons',  nombre: 'fonticons' },
                        { id: 'fa fa-forumbee',  nombre: 'forumbee' },
                        { id: 'fa fa-forward',  nombre: 'forward' },
                        { id: 'fa fa-foursquare',  nombre: 'foursquare' },
                        { id: 'fa fa-frown-o',  nombre: 'frown-o' },
                        { id: 'fa fa-futbol-o',  nombre: 'futbol-o' },
                        { id: 'fa fa-gamepad',  nombre: 'gamepad' },
                        { id: 'fa fa-gavel',  nombre: 'gavel' },
                        { id: 'fa fa-gbp',  nombre: 'gbp' },
                        { id: 'fa fa-ge',  nombre: 'ge' },
                        { id: 'fa fa-gear',  nombre: 'gear' },
                        { id: 'fa fa-gears',  nombre: 'gears' },
                        { id: 'fa fa-genderless',  nombre: 'genderless' },
                        { id: 'fa fa-get-pocket',  nombre: 'get-pocket' },
                        { id: 'fa fa-gg',  nombre: 'gg' },
                        { id: 'fa fa-gg-circle',  nombre: 'gg-circle' },
                        { id: 'fa fa-gift',  nombre: 'gift' },
                        { id: 'fa fa-git',  nombre: 'git' },
                        { id: 'fa fa-git-square',  nombre: 'git-square' },
                        { id: 'fa fa-github',  nombre: 'github' },
                        { id: 'fa fa-github-alt',  nombre: 'github-alt' },
                        { id: 'fa fa-github-square',  nombre: 'github-square' },
                        { id: 'fa fa-gittip',  nombre: 'gittip' },
                        { id: 'fa fa-glass',  nombre: 'glass' },
                        { id: 'fa fa-globe',  nombre: 'globe' },
                        { id: 'fa fa-google',  nombre: 'google' },
                        { id: 'fa fa-google-plus',  nombre: 'google-plus' },
                        { id: 'fa fa-google-plus-square',  nombre: 'google-plus-square' },
                        { id: 'fa fa-google-wallet',  nombre: 'google-wallet' },
                        { id: 'fa fa-graduation-cap',  nombre: 'graduation-cap' },
                        { id: 'fa fa-gratipay',  nombre: 'gratipay' },
                        { id: 'fa fa-group',  nombre: 'group' },
                        { id: 'fa fa-h-square',  nombre: 'h-square' },
                        { id: 'fa fa-hacker-news',  nombre: 'hacker-news' },
                        { id: 'fa fa-hand-grab-o',  nombre: 'hand-grab-o' },
                        { id: 'fa fa-hand-lizard-o',  nombre: 'hand-lizard-o' },
                        { id: 'fa fa-hand-o-down',  nombre: 'hand-o-down' },
                        { id: 'fa fa-hand-o-left',  nombre: 'hand-o-left' },
                        { id: 'fa fa-hand-o-right',  nombre: 'hand-o-right' },
                        { id: 'fa fa-hand-o-up',  nombre: 'hand-o-up' },
                        { id: 'fa fa-hand-paper-o',  nombre: 'hand-paper-o' },
                        { id: 'fa fa-hand-peace-o',  nombre: 'hand-peace-o' },
                        { id: 'fa fa-hand-pointer-o',  nombre: 'hand-pointer-o' },
                        { id: 'fa fa-hand-rock-o',  nombre: 'hand-rock-o' },
                        { id: 'fa fa-hand-scissors-o',  nombre: 'hand-scissors-o' },
                        { id: 'fa fa-hand-spock-o',  nombre: 'hand-spock-o' },
                        { id: 'fa fa-hand-stop-o',  nombre: 'hand-stop-o' },
                        { id: 'fa fa-hdd-o',  nombre: 'hdd-o' },
                        { id: 'fa fa-header',  nombre: 'header' },
                        { id: 'fa fa-headphones',  nombre: 'headphones' },
                        { id: 'fa fa-heart',  nombre: 'heart' },
                        { id: 'fa fa-heart-o',  nombre: 'heart-o' },
                        { id: 'fa fa-heartbeat',  nombre: 'heartbeat' },
                        { id: 'fa fa-history',  nombre: 'history' },
                        { id: 'fa fa-home',  nombre: 'home' },
                        { id: 'fa fa-hospital-o',  nombre: 'hospital-o' },
                        { id: 'fa fa-hotel',  nombre: 'hotel' },
                        { id: 'fa fa-hourglass',  nombre: 'hourglass' },
                        { id: 'fa fa-hourglass-1',  nombre: 'hourglass-1' },
                        { id: 'fa fa-hourglass-2',  nombre: 'hourglass-2' },
                        { id: 'fa fa-hourglass-3',  nombre: 'hourglass-3' },
                        { id: 'fa fa-hourglass-end',  nombre: 'hourglass-end' },
                        { id: 'fa fa-hourglass-half',  nombre: 'hourglass-half' },
                        { id: 'fa fa-hourglass-o',  nombre: 'hourglass-o' },
                        { id: 'fa fa-hourglass-start',  nombre: 'hourglass-start' },
                        { id: 'fa fa-houzz',  nombre: 'houzz' },
                        { id: 'fa fa-html5',  nombre: 'html5' },
                        { id: 'fa fa-i-cursor',  nombre: 'i-cursor' },
                        { id: 'fa fa-ils',  nombre: 'ils' },
                        { id: 'fa fa-image',  nombre: 'image' },
                        { id: 'fa fa-inbox',  nombre: 'inbox' },
                        { id: 'fa fa-indent',  nombre: 'indent' },
                        { id: 'fa fa-industry',  nombre: 'industry' },
                        { id: 'fa fa-info',  nombre: 'info' },
                        { id: 'fa fa-info-circle',  nombre: 'info-circle' },
                        { id: 'fa fa-inr',  nombre: 'inr' },
                        { id: 'fa fa-instagram',  nombre: 'instagram' },
                        { id: 'fa fa-institution',  nombre: 'institution' },
                        { id: 'fa fa-internet-explorer',  nombre: 'internet-explorer' },
                        { id: 'fa fa-intersex',  nombre: 'intersex' },
                        { id: 'fa fa-ioxhost',  nombre: 'ioxhost' },
                        { id: 'fa fa-italic',  nombre: 'italic' },
                        { id: 'fa fa-joomla',  nombre: 'joomla' },
                        { id: 'fa fa-jpy',  nombre: 'jpy' },
                        { id: 'fa fa-jsfiddle',  nombre: 'jsfiddle' },
                        { id: 'fa fa-key',  nombre: 'key' },
                        { id: 'fa fa-keyboard-o',  nombre: 'keyboard-o' },
                        { id: 'fa fa-krw',  nombre: 'krw' },
                        { id: 'fa fa-language',  nombre: 'language' },
                        { id: 'fa fa-laptop',  nombre: 'laptop' },
                        { id: 'fa fa-lastfm',  nombre: 'lastfm' },
                        { id: 'fa fa-lastfm-square',  nombre: 'lastfm-square' },
                        { id: 'fa fa-leaf',  nombre: 'leaf' },
                        { id: 'fa fa-leanpub',  nombre: 'leanpub' },
                        { id: 'fa fa-legal',  nombre: 'legal' },
                        { id: 'fa fa-lemon-o',  nombre: 'lemon-o' },
                        { id: 'fa fa-level-down',  nombre: 'level-down' },
                        { id: 'fa fa-level-up',  nombre: 'level-up' },
                        { id: 'fa fa-life-bouy',  nombre: 'life-bouy' },
                        { id: 'fa fa-life-buoy',  nombre: 'life-buoy' },
                        { id: 'fa fa-life-ring',  nombre: 'life-ring' },
                        { id: 'fa fa-life-saver',  nombre: 'life-saver' },
                        { id: 'fa fa-lightbulb-o',  nombre: 'lightbulb-o' },
                        { id: 'fa fa-line-chart',  nombre: 'line-chart' },
                        { id: 'fa fa-link',  nombre: 'link' },
                        { id: 'fa fa-linkedin',  nombre: 'linkedin' },
                        { id: 'fa fa-linkedin-square',  nombre: 'linkedin-square' },
                        { id: 'fa fa-linux',  nombre: 'linux' },
                        { id: 'fa fa-list',  nombre: 'list' },
                        { id: 'fa fa-list-alt',  nombre: 'list-alt' },
                        { id: 'fa fa-list-ol',  nombre: 'list-ol' },
                        { id: 'fa fa-list-ul',  nombre: 'list-ul' },
                        { id: 'fa fa-location-arrow',  nombre: 'location-arrow' },
                        { id: 'fa fa-lock',  nombre: 'lock' },
                        { id: 'fa fa-long-arrow-down',  nombre: 'long-arrow-down' },
                        { id: 'fa fa-long-arrow-left',  nombre: 'long-arrow-left' },
                        { id: 'fa fa-long-arrow-right',  nombre: 'long-arrow-right' },
                        { id: 'fa fa-long-arrow-up',  nombre: 'long-arrow-up' },
                        { id: 'fa fa-magic',  nombre: 'magic' },
                        { id: 'fa fa-magnet',  nombre: 'magnet' },
                        { id: 'fa fa-mars-stroke-v',  nombre: 'mars-stroke-v' },
                        { id: 'fa fa-maxcdn',  nombre: 'maxcdn' },
                        { id: 'fa fa-meanpath',  nombre: 'meanpath' },
                        { id: 'fa fa-medium',  nombre: 'medium' },
                        { id: 'fa fa-medkit',  nombre: 'medkit' },
                        { id: 'fa fa-meh-o',  nombre: 'meh-o' },
                        { id: 'fa fa-mercury',  nombre: 'mercury' },
                        { id: 'fa fa-microphone',  nombre: 'microphone' },
                        { id: 'fa fa-mobile',  nombre: 'mobile' },
                        { id: 'fa fa-motorcycle',  nombre: 'motorcycle' },
                        { id: 'fa fa-mouse-pointer',  nombre: 'mouse-pointer' },
                        { id: 'fa fa-music',  nombre: 'music' },
                        { id: 'fa fa-navicon',  nombre: 'navicon' },
                        { id: 'fa fa-neuter',  nombre: 'neuter' },
                        { id: 'fa fa-newspaper-o',  nombre: 'newspaper-o' },
                        { id: 'fa fa-opencart',  nombre: 'opencart' },
                        { id: 'fa fa-openid',  nombre: 'openid' },
                        { id: 'fa fa-opera',  nombre: 'opera' },
                        { id: 'fa fa-outdent',  nombre: 'outdent' },
                        { id: 'fa fa-pagelines',  nombre: 'pagelines' },
                        { id: 'fa fa-paper-plane-o',  nombre: 'paper-plane-o' },
                        { id: 'fa fa-paperclip',  nombre: 'paperclip' },
                        { id: 'fa fa-paragraph',  nombre: 'paragraph' },
                        { id: 'fa fa-paste',  nombre: 'paste' },
                        { id: 'fa fa-pause',  nombre: 'pause' },
                        { id: 'fa fa-paw',  nombre: 'paw' },
                        { id: 'fa fa-paypal',  nombre: 'paypal' },
                        { id: 'fa fa-pencil',  nombre: 'pencil' },
                        { id: 'fa fa-pencil-square-o',  nombre: 'pencil-square-o' },
                        { id: 'fa fa-phone',  nombre: 'phone' },
                        { id: 'fa fa-photo',  nombre: 'photo' },
                        { id: 'fa fa-picture-o',  nombre: 'picture-o' },
                        { id: 'fa fa-pie-chart',  nombre: 'pie-chart' },
                        { id: 'fa fa-pied-piper',  nombre: 'pied-piper' },
                        { id: 'fa fa-pied-piper-alt',  nombre: 'pied-piper-alt' },
                        { id: 'fa fa-pinterest',  nombre: 'pinterest' },
                        { id: 'fa fa-pinterest-p',  nombre: 'pinterest-p' },
                        { id: 'fa fa-pinterest-square',  nombre: 'pinterest-square' },
                        { id: 'fa fa-plane',  nombre: 'plane' },
                        { id: 'fa fa-play',  nombre: 'play' },
                        { id: 'fa fa-play-circle',  nombre: 'play-circle' },
                        { id: 'fa fa-play-circle-o',  nombre: 'play-circle-o' },
                        { id: 'fa fa-plug',  nombre: 'plug' },
                        { id: 'fa fa-plus',  nombre: 'plus' },
                        { id: 'fa fa-plus-circle',  nombre: 'plus-circle' },
                        { id: 'fa fa-plus-square',  nombre: 'plus-square' },
                        { id: 'fa fa-plus-square-o',  nombre: 'plus-square-o' },
                        { id: 'fa fa-power-off',  nombre: 'power-off' },
                        { id: 'fa fa-print',  nombre: 'print' },
                        { id: 'fa fa-puzzle-piece',  nombre: 'puzzle-piece' },
                        { id: 'fa fa-qq',  nombre: 'qq' },
                        { id: 'fa fa-qrcode',  nombre: 'qrcode' },
                        { id: 'fa fa-question',  nombre: 'question' },
                        { id: 'fa fa-question-circle',  nombre: 'question-circle' },
                        { id: 'fa fa-quote-left',  nombre: 'quote-left' },
                        { id: 'fa fa-quote-right',  nombre: 'quote-right' },
                        { id: 'fa fa-ra',  nombre: 'ra' },
                        { id: 'fa fa-random',  nombre: 'random' },
                        { id: 'fa fa-rebel',  nombre: 'rebel' },
                        { id: 'fa fa-recycle',  nombre: 'recycle' },
                        { id: 'fa fa-reddit',  nombre: 'reddit' },
                        { id: 'fa fa-reddit-square',  nombre: 'reddit-square' },
                        { id: 'fa fa-refresh',  nombre: 'refresh' },
                        { id: 'fa fa-registered',  nombre: 'registered' },
                        { id: 'fa fa-remove',  nombre: 'remove' },
                        { id: 'fa fa-renren',  nombre: 'renren' },
                        { id: 'fa fa-reorder',  nombre: 'reorder' },
                        { id: 'fa fa-repeat',  nombre: 'repeat' },
                        { id: 'fa fa-reply',  nombre: 'reply' },
                        { id: 'fa fa-reply-all',  nombre: 'reply-all' },
                        { id: 'fa fa-retweet',  nombre: 'retweet' },
                        { id: 'fa fa-rmb',  nombre: 'rmb' },
                        { id: 'fa fa-road',  nombre: 'road' },
                        { id: 'fa fa-rocket',  nombre: 'rocket' },
                        { id: 'fa fa-rotate-left',  nombre: 'rotate-left' },
                        { id: 'fa fa-rotate-right',  nombre: 'rotate-right' },
                        { id: 'fa fa-rouble',  nombre: 'rouble' },
                        { id: 'fa fa-rss',  nombre: 'rss' },
                        { id: 'fa fa-rss-square',  nombre: 'rss-square' },
                        { id: 'fa fa-rub',  nombre: 'rub' },
                        { id: 'fa fa-ruble',  nombre: 'ruble' },
                        { id: 'fa fa-rupee',  nombre: 'rupee' },
                        { id: 'fa fa-safari',  nombre: 'safari' },
                        { id: 'fa fa-sliders',  nombre: 'sliders' },
                        { id: 'fa fa-slideshare',  nombre: 'slideshare' },
                        { id: 'fa fa-smile-o',  nombre: 'smile-o' },
                        { id: 'fa fa-sort-asc',  nombre: 'sort-asc' },
                        { id: 'fa fa-sort-desc',  nombre: 'sort-desc' },
                        { id: 'fa fa-sort-down',  nombre: 'sort-down' },
                        { id: 'fa fa-spinner',  nombre: 'spinner' },
                        { id: 'fa fa-spoon',  nombre: 'spoon' },
                        { id: 'fa fa-spotify',  nombre: 'spotify' },
                        { id: 'fa fa-square',  nombre: 'square' },
                        { id: 'fa fa-square-o',  nombre: 'square-o' },
                        { id: 'fa fa-star',  nombre: 'star' },
                        { id: 'fa fa-star-half',  nombre: 'star-half' },
                        { id: 'fa fa-stop',  nombre: 'stop' },
                        { id: 'fa fa-subscript',  nombre: 'subscript' },
                        { id: 'fa fa-tablet',  nombre: 'tablet' },
                        { id: 'fa fa-tachometer',  nombre: 'tachometer' },
                        { id: 'fa fa-tag',  nombre: 'tag' },
                        { id: 'fa fa-tags',  nombre: 'tags' }
                    ]
            },
            verificaUltimoHijo(id){ // OK método encargado de verificar si el item no tiene hijos
                    var url= this.ruta +  '/menus/verificaUltimoHijo?id='+id;
                    axios.get(url).then((response) => {
                        this.esUltimo = response.data.respuesta;
                        if(this.esUltimo && this.menu_padre.id == ''){ 
                            this.mostrarIcono = 1; 
                            this.mostrarUbicacion = 1; 
                        }else{
                            this.mostrarIcono = 0;
                             this.mostrarUbicacion = 0;
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
            },
            getRutasVue(){
                axios.get(this.ruta +'/menus/getRutasVueMenu').then((response) => {
                    this.arrayRutasVue = response.data;        
                    let newDad = {
                        id: '',
                        name: '',
                        component: '',
                        path: '',            
                        estado: '',
                        created_at: '',
                        updated_at: '',
                        dato_com: 'MENÚ SIN COMPONENTE'
                    };
                    this.arrayRutasVue.unshift(newDad); //inserta el elemento 
                });
            }
        },
         mounted() { // Se llama el Metodo de listar para que se renderice cada vez que se cargue el componente
            this.listarMenu(1,this.buscar,this.criterio);
            this.consultarPermisosPadre();
            this.cargarIcons();
            axios.get('/menus/getRutaRelativa?ruta='+this.$route.name).then((response) => { this.arrayRuta = response.data; });         
        }
    }
</script>
<style>
select {
  font-family: 'FontAwesome', 'sans-serif';
}
</style>
