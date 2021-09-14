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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <div v-permiso="'add_usuarios'">
                            <button type="button" @click="ingresarUser('user','create')" class="btn btn-secondary">
                                <i class="icon-plus"></i> Nuevo
                            </button>
                        </div>
                    </div>

                    <div  v-permiso="'view_usuarios'">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                            <option value="nombre">Nombre</option>
                                            <option value="numero_documento">Documento</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup="listarUser(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarUser(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                            <th>Usuario</th>
                                            <th  v-if="!super_administrador">Rol</th>
                                            <th>Correo</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <tr v-for="user in arrayUser" :key="user.id">  
                                            <td>
                                                <div v-permiso="'add_usuarios'">
                                                    <button type="button" @click="ingresarUser('user','update',user)" class="btn btn-inverse-warning btn-sm">
                                                        <i class="typcn typcn-edit"></i>
                                                    </button> &nbsp;
                                                </div>
                                                <div v-permiso="'delete_usuarios'">
                                                    <template v-if="super_administrador && user.estado" class="preview">
                                                        <button type="button" class="btn btn-inverse-danger btn-sm" @click="inactivarUserGlobal(user.id)">
                                                            <i class="typcn typcn-trash"></i>
                                                        </button>
                                                    </template>
                                                    <template v-if="!super_administrador && user.estado" class="preview">
                                                        <button type="button" class="btn btn-inverse-danger btn-sm" @click="inactivarUser(user.id)">
                                                            <i class="typcn typcn-trash"></i>
                                                        </button>
                                                    </template>
                                                    <template v-if="!super_administrador && !user.estado">
                                                        <button type="button" class="btn btn-inverse-info btn-sm" @click="activarUser(user.id)">
                                                            <i class="typcn typcn-tick-outline"></i>
                                                        </button>
                                                    </template>
                                                     <template v-if="super_administrador && !user.estado">
                                                        <button type="button" class="btn btn-inverse-info btn-sm" @click="activarUserGlobal(user.id)">
                                                            <i class="typcn typcn-tick-outline"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                            </td>
                                            <td>{{ user.nombre + " " + user.apellidos}}</td>
                                            <td  v-text="user.usuario"></td>
                                            <td  v-if="!super_administrador" v-text="user.rol_nom"></td>
                                            <td v-text="user.email"></td>
                                            <td>
                                                <div v-if="user.estado">
                                                    <span class="badge badge-outline-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-outline-danger">Inactivo</span>
                                                </div>
                                            </td>
                                        </tr>                            
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
                </div>
            </template>
            <!-- Fin Tabla Listado -->
            <!-- Formulario de Ingreso y Actualizacion--> 
            <template v-if="listado==2">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                        <h4 class="card-title" v-text="tituloFormulario"></h4>
                        <form class="form-sample">
                            <div class="col-md-10 offset-1" v-if="showSelectUser">
                                    <div class="form-group">
                                        <label for="">Usuarios Centralizado</label>
                                           <v-select
                                            v-model="IdUserCentralizado"
                                            :options="arrayUserCentralizado"
                                            label="dato_com"
                                            @input="changeSelectUser" 
                                            :value="IdUserCentralizado"
                                            >
                                        </v-select>
                                    </div>
                            </div>
                            <p class="card-description">
                                Datos Basicos
                            </p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Nombre</label>
                                        <div class="col-sm-8">
                                            <input v-model="nombre" type="text" class="form-control" :disabled="disbaledNombres"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Apellidos</label>
                                    <div class="col-sm-8">
                                        <input v-model="apellidos" type="text" class="form-control" :disabled="disbaledApellidos"/>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Tipo Documento</label>
                                    <div class="col-sm-8">
                                        <v-select 
                                            v-model="tipo_documento"
                                            :options="arrayTipoDoc"
                                            label="nombre"
                                            :value="tipo_documento"
                                            :disabled="disbaledTipoDocumento"
                                        ></v-select>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">No. de Documento</label>
                                    <div class="col-sm-8">
                                        <input v-model="numero_documento" type="text" class="form-control" :disabled="disbaledNDocumento"/>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Nombre de Usuario</label>
                                    <div class="col-sm-8">
                                        <input v-model="usuario" type="text" class="form-control" :disabled="user_disabled" />
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Rol</label>
                                    <div class="col-sm-8">
                                        <v-select 
                                            v-model="idRol"
                                            :options="arrayRoles"
                                            label="name"
                                            :value="idRol"
                                            :disabled="disabled_rol"
                                            @input="changeDeptos"
                                        ></v-select>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">E-Mail</label>
                                    <div class="col-sm-10">
                                        <input v-model="email" type="email" class="form-control" :disabled="disbaledEmail"/>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div v-if="tipoAccion==1 || cambiarPass" class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Contraseña</label>
                                        <div class="col-sm-8" v-if="!disbaledEmail">
                                            <input type="password" class="form-control" placeholder="Password" v-model="password" @blur="validarPassword()">
                                            <small id="passwordHelp" class="form-text text-muted">La contraseña debe contener:
                                                <span :class="has_minimum_lenth ? 'has_required' : ''">mínimo 8 caracteres</span>, 
                                                <span :class="has_lowercase ? 'has_required' : ''">una letra minúscula</span>, 
                                                <span :class="has_uppercase ? 'has_required' : ''">una letra mayúscula</span>, 
                                                <span :class="has_number ? 'has_required' : ''">un número</span>, y 
                                                <span :class="has_special ? 'has_required' : ''">un caracter especial.</span>
                                            </small>
                                        </div>
                                        <div class="col-sm-8" v-else>
                                            <span style="color:#0845d0"> Se toma la contraseña ya registrada.</span>
                                        </div>
                                    </div>
                                </div>        
                                <div v-if="tipoAccion==1 || cambiarPass && !disbaledEmail" class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Confirmar Contraseña</label>
                                        <div class="col-sm-8">
                                            <input type="password" class="form-control" placeholder="Password" v-model="confirm_password" @keyup="confirmarPass()">
                                            <small class="form-text text-muted" v-if="mostrar_coinciden">
                                                <span class="no_coinciden" v-if="!no_coinciden">No Coinciden</span>
                                                <span class="coinciden" v-if="no_coinciden">Coinciden</span>  
                                            </small>
                                        </div>
                                    </div>
                                </div>                              
                            </div>
                            <div class="col-md-12" v-if="showInputPass">
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                    <label class="col-form-label">Modificar Contraseña
                                        <input v-model="modificar_pass" type="checkbox"  v-on:change="showPass()"/></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" v-if="quitar_rol">
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                    <label class="col-form-label">Quitar Rol Super Administrador
                                        <input v-model="checkbox_rol" type="checkbox"  v-on:change="disabledRol()"/></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Observaciones</label>
                                    <div class="col-sm-10">
                                        <textarea v-model="observaciones" class="form-control" maxlength="100" rows="3" placeholder="Maximo 250 Caracteres." :disabled="disbaledObservaciones"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" v-show="ocultar_deptos">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Aplicativos</label>
                                    <div class="col-sm-3" v-for="(deptos,index) in arrayTipoDeptos" :key="deptos.id" :index="index">
                                        <label class="col-form-label" >
                                            <span v-text="deptos.nombre"></span>
                                            <input :class="'depto_'+deptos.id" name="deptos_user[]"  :id="'depto_'+deptos.id" :value="deptos.id" type="checkbox" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div v-show="errorFormulario" class="row justify-content-center">       
                                <div class="alert alert-danger col-md-10  " role="alert">
                                <p class="mb-0"  v-for="error in errorMensajesFormularios" :key="error" v-text="error"></p>
                                </div>
                            </div>
                            <div class="row">
                            <div v-permiso="'add_usuarios'">
                                <button v-if="tipoAccion==1" @click="createUser()" type="button" class="btn btn-primary mr-2">Guardar</button>
                            </div>
                            <div v-permiso="'edit_usuarios'">
                                <button v-if="tipoAccion==2" @click="updateUser()" type="button" class="btn btn-primary mr-2">Actualizar</button>
                            </div>
                            <button class="btn btn-light" @click="cancelarIngreso()">Cancelar</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </template>
            <!-- Fin Formulario de Ingreso -->
        </div>
    </main>
</template>

<script>
    export default {
        props : ['ruta'],
        data (){ // Resive los Datos que llegan
            return {
                // Inicialización de Variables
                idrol : '',
                user_usuario : '',
                rol_nom : '',
                email : '',
                estado : '',
                arrayUser : [],
                arrayRoles : [],
                arrayDeclarante : [],
                tipo_documento :{
                    id : '',
                    nombre : ''
                },
                selDeclarante : {
                    codigo : '',
                    nombre_legal : ''
                },
                idRol : {
                    id : '',
                    name : ''
                },
                listado : 1,
                tituloFormulario : '',
                tipoAccion : 0,
                errorFormulario : 0,
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
                criterio : 'nombre',
                buscar : '',
                super_administrador: false,
                user_disabled: false,
                quitar_rol: false,
                checkbox_rol:false,
                disabled_rol: false,
                arrayRuta: [], 
                formCentralizado: false,
                arrayTipoDeptos: [],
                arrayDeptosUser: [],
                ocultar_deptos: false,
                password: '',
                has_minimum_lenth: false,
                has_number: false,
                has_lowercase: false,
                has_uppercase: false,
                has_special: false,
                confirm_password: '',
                no_coinciden: false,
                mostrar_coinciden: false,
                arrayUserCentralizado: [],
                IdUserCentralizado: '',
                disbaledApellidos: false,
                disbaledTipoDocumento: false,
                disbaledNombres: false,
                disbaledNDocumento: false,
                disbaledEmail: false,
                disbaledObservaciones: false,
                showSelectUser: false,
                cambiarPass: false,
                modificar_pass: '',
                showInputPass: false
            }
        },
        watch:{
            password(){
                this.has_minimum_lenth = (this.password.length > 7);
                this.has_number    = /\d/.test(this.password);
                this.has_lowercase = /[a-z]/.test(this.password);
                this.has_uppercase = /[A-Z]/.test(this.password);
                this.has_special   = /[!@#\$%\^\&*\)\(+=._-]/.test(this.password);
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

            }
        },
        methods : {
            cambiarPagina(page,buscar,criterio){ // Metodo encargado de realizar el cambio de pagina
                this.pagination.current_page = page; //Actualiza la página actual
                //Envia la petición para visualizar la data de esa página
                this.listarUser(page,buscar,criterio);
            },
            listarUser (page,buscar,criterio){ // Metodo encargado de enviar los datos al listado
                var url= this.ruta + '/usuarios?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then((response)=> {
                    var respuesta= response.data;
                    this.arrayUser = respuesta.usuarios.data;
                    this.pagination= respuesta.pagination;
                    this.super_administrador = respuesta.super_administrador;
                    this.selectRol();
                    if(respuesta.showSelectUser && !this.super_administrador){
                        this.showSelectUser = true;
                        // this.cambiarPass = true;
                    }
                    if(respuesta.showSelectUser){
                        this.showInputPass = true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cancelarIngreso(){ // Metodo encargado de cancelar las acciones en los formularios
                this.listado = 1;
                this.tituloFormulario = '';
                this.nombre = '';
                this.descripcion = ''; 
                this.errorFormulario = ''; 
            },
            selectTipoDoc(){
                var url= this.ruta + '/tipoDoc/gettipodoc';
                axios.get(url).then( (response) => {
                    var respuesta= response.data;
                    this.arrayTipoDoc = respuesta.tipos_doc;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRol(){
                var url=   this.ruta + '/rol/getrol';
                if(!this.super_administrador){
                    axios.get(url).then((response)=> {
                        var respuesta= response.data;   
                        this.arrayRoles = respuesta.roles;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }else{
                    this.arrayRoles =  [{id: 1, name: "Super administrador"},{id: 2, name: "Administrador"}];
                    this.formCentralizado = true;
                }
            },
            selectDeclarante(){
                if(!this.super_administrador){
                    axios.get( this.ruta + '/declarante').then((response) => {
                        this.arrayDeclarante = response.data.declarante;
                    }).catch(function(e){
                        console.log(e);
                    });
                }
            },
            ingresarUser (modelo, accion, data = []){ // Metodo encargado de cargar los formularios de crear y actualizar
                this.listado=2;
                switch(modelo){
                    case "user":
                    {
                        switch(accion){
                            case 'create':
                            {   
                                if(!this.super_administrador){
                                    this.showSelectUser = true;
                                }                               
                                this.IdUserCentralizado =  '';                    
                                this.disbaledApellidos      = false;
                                this.disbaledApellidos      = false;
                                this.disbaledTipoDocumento  = false;
                                this.disbaledNombres        = false;
                                this.disbaledNDocumento     = false;
                                this.disbaledEmail          = false
                                this.user_disabled          = false;
                                this.disbaledObservaciones  = false;
                                this.nombre                 = '';
                                this.apellidos              = '';
                                this.usuario                = '';
                                this.tipo_documento         = '';
                                this.email                  = '';
                                this.numero_documento       = '';
                                this.getUserCentralizado();
                                this.mostrar_coinciden  = false;
                                this.tituloFormulario   = "Registrar Usuario";
                                this.nombre             = '';
                                this.apellidos          = '';
                                this.tipo_documento     = 0;
                                this.numero_documento   = '';
                                this.usuario            = '';
                                this.password           = '';
                                this.email              = '';
                                this.observaciones      = '';
                                this.selDeclarante      = '';
                                this.idRol              = 0;
                                this.tipoAccion         = 1;
                                this.quitar_rol         = false;
                                this.disabled_rol       = false;
                                this.user_disabled      = false;
                                this.confirm_password   = '';
                                this.showInputPass      = false;
                                break;
                            }
                            case 'update':
                            {
                                if(!this.super_administrador){
                                    this.showSelectUser = false;
                                }

                                this.mostrar_coinciden  = false;
                                this.disabled_rol       = false;
                                this.ocultar_deptos     = false;
                                this.getDeptoUser(data['id']);
                                this.user_disabled      = true;
                                this.tituloFormulario   = "Actualizar Usuario";
                                this.nombre             = data['nombre'];
                                this.apellidos          = data['apellidos'];
                                this.tipo_documento     = {
                                                            id : data['tipo_documento'],
                                                            nombre: data['n_tipo_documento']
                                                          };
                                this.numero_documento   = data['numero_documento'];
                                this.usuario            = data['usuario'];
                                this.password           = '';
                                this.email              = data['email'];
                                this.observaciones      = data['observaciones'];
                                if(data['super_administrador'] == 1){
                                    this.idRol          =   {
                                                                id : '1',
                                                                name : 'Super administrador'
                                                            }
                                    this.quitar_rol     = true;
                                    this.ocultar_deptos = false;
                                }else{
                                    this.idRol          =  {
                                                                id : data['idrol'],
                                                                name : data['rol_nom']
                                                           }
                                    this.quitar_rol     = false;
                                    // this.ocultar_deptos = true;
                                }
                                this.persona_id         = data['persona_id'];
                                this.idUser             = data['id'];
                                this.tipoAccion         = 2;
                                this.confirm_password   = '';
                                this.cambiarPass        = false;
                                this.modificar_pass     = false;
                                break;
                            }
                        }
                    }
                }
            },
            createUser(tipoAccion) { // Metodo encargado de realizar el envio de datos para la creacion
                if(this.validacionFormulario(tipoAccion)){
                    return;
                }
                $('.spinner').css('display', 'block');

                var deptos_user = $('input[name="deptos_user[]"]:checked').map(function(){
                    return this.value; 
                }).get();

                var url=  this.ruta + '/usuarios/create';
                axios.post(url,{
                    'nombre'                : this.nombre,
                    'apellidos'             : this.apellidos,
                    'tipo_documento'        : this.tipo_documento.id,
                    'numero_documento'      : this.numero_documento,
                    'usuario'               : this.usuario,
                    'password'              : this.password,
                    'email'                 : this.email,
                    'idRol'                 : this.idRol.id,
                    'observaciones'         : this.observaciones,
                    'confirmado'            : false,
                    'super_administrador'   : (this.super_administrador) ? true : false,
                    'deptos_user'           : deptos_user,
                    'formCentralizado'      : this.formCentralizado,
                    'IdUserCentralizado'    : this.IdUserCentralizado.id
                }).then((result) => {
                    $('.spinner').css('display', 'none');
                    switch(result.data.status){
                        case 400:
                                Swal.fire({
                                    title: 'Usuario Existente!',
                                    text: 'Ya existe un usuario registrado con ese Numero de documento pero con diferente nombre de usuario, desea agregarle este departamento?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: 'Confirmar',
                                }).then((result) => {
                                    if (result.value) {
                                        var url=  this.ruta + '/usuarios/create';

                                        axios.post(url,{
                                            'nombre'            : this.nombre,
                                            'apellidos'         : this.apellidos,
                                            'tipo_documento'    : this.tipo_documento.id,
                                            'numero_documento'  : this.numero_documento,
                                            'usuario'           : this.usuario,
                                            'password'          : this.password,
                                            'email'             : this.email,
                                            'idRol'             : this.idRol.id,
                                            'idDeclarante'      : this.selDeclarante.codigo, 
                                            'observaciones'     : this.observaciones,
                                            'confirmado'        : true,
                                            'existente'         : true
                                        }).then((response) => {
                                                Swal.fire('Exisoto!', 'Usuario creado!', 'success');
                                                this.cancelarIngreso();
                                                this.listarUser(1,'','nombre');
                                        })
                                        .catch(function (error) {
                                            console.log(error);
                                        });
                                    } else {
                                        Swal.fire('Tienes que poner un documento diferente', '', 'info')
                                    }
                                })
                        break;
                        case 401:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'Ya existe este nombre de usuario, Intenta con otro!',
                                    timer: 2200
                                })
                        break;
                        case 402:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'Usuario ya registrado en este departamento!',
                                    timer: 1800
                                })
                        break;
                        case 403:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'Documento ya registrado!',
                                    timer: 1800
                                })
                        break;
                        case 405:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'Usuario ya existe en el centralizado!',
                                    timer: 1800
                                })
                        break;
                        case 406:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'Número de documento ya registrado en el Centralizado.',
                                    timer: 1800
                                })
                        break;
                        case 407:
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'Ya existe este correo electronico, Intenta con otro!',
                                    timer: 2200
                                })
                        break;
                        case 200:
                                Swal.fire('Exitoso!', 'Usuario creado!', 'success');
                                this.cancelarIngreso();
                                this.listarUser(1,'','nombre');
                        break;
                    }
               
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            updateUser(tipoAccion) { // Metodo encargado de realizar el envio de datos para la actualizacion
                if(this.validacionFormulario(tipoAccion)){
                    return;
                }
                var deptos_user = $('input[name="deptos_user[]"]:checked').map(function(){
                    return this.value; 
                }).get();

                if(this.idRol.id == 2){
                    // this.checkbox_rol = true;
                }

                var url=  this.ruta +  '/usuarios/update';
                axios.put(url,{
                    'nombre'                : this.nombre,
                    'apellidos'             : this.apellidos,
                    'tipo_documento'        : this.tipo_documento.id,
                    'numero_documento'      : this.numero_documento,
                    'usuario'               : this.usuario,
                    'password'              : this.password,
                    'email'                 : this.email,
                    'idRol'                 : this.idRol.id,
                    'observaciones'         : this.observaciones,
                    'id'                    : this.idUser,
                    'persona_id'            : this.persona_id,
                    'check_rol'             : this.checkbox_rol,
                    'formCentralizado'      : this.formCentralizado,
                    'deptos_activos'        : this.ocultar_deptos,
                    'deptos_user'           : deptos_user
                }).then( (response) => {
                    this.cancelarIngreso();
                    this.listarUser(1,'','nombre');
                    if(response.data.status){
                        this.getDeptoUser(this.idUser);
                        swal.fire({
                            icon: 'success',
                            title: 'Usuario actualizado Exitosamente!',
                            showConfirmButton: false,
                            timer: 1750
                        });
                    }else{
                        if(!response.data.email){
                            swal.fire({
                                icon: 'error',
                                title: 'Error al actualizar contraseña, verifica Email!',
                                showConfirmButton: false,
                                timer: 2150
                            });
                        }else{
                            swal.fire({
                                icon: 'error',
                                title: 'Error al actualizar usuario!',
                                showConfirmButton: false,
                                timer: 1750
                            });
                        }                      
                    }
                })
                .catch((error)=> {
                    if(error.response.status == 422){

                        let mensaje = '';
                        let respuesta = error.response.data.errors;

                        if(respuesta.numero_documento != undefined){
                            for(let err in respuesta.numero_documento){
                                mensaje += respuesta.numero_documento[err]+', ';
                            }
                        }
                        if(respuesta.email != undefined){
                            for(let err in respuesta.email){
                                mensaje += respuesta.email[err]+', ';
                            }
                        }
                  
                        swal.fire({
                            icon: 'error',
                            title: 'Error al actualizar usuario!',
                            text: mensaje 
                        });
                    }
                });
            },
            validacionFormulario(tipoAccion) { // Metodo encargado de la validacion de los campos de los formularios
                this.errorFormulario = 0;
                this.errorMensajesFormularios = [];
                if(!this.nombre) this.errorMensajesFormularios.push("El campo Nombre es Obligatorio.");
                if(!this.tipo_documento) this.errorMensajesFormularios.push("El campo Tipo de Documento es Obligatorio.");
                if(!this.numero_documento) this.errorMensajesFormularios.push("El campo Numero de Documento es Obligatorio.");
                if(!this.usuario) this.errorMensajesFormularios.push("El campo Nombre de Usuario es Obligatorio.");
                if(this.idRol==0) this.errorMensajesFormularios.push("El campo Rol es Obligatorio.");
                if(this.tipoAccion==1 || this.modificar_pass){
                    if(this.IdUserCentralizado == '' || this.IdUserCentralizado.id < 1 || this.modificar_pass){
                        if(!this.password) this.errorMensajesFormularios.push("El campo Contraseña es Obligatorio.");
                        if(!this.has_number || !this.has_lowercase || !this.has_uppercase || !this.has_special){
                            this.errorMensajesFormularios.push("No cumple todos los requerimientos de la contraseña.");
                        }
                        if(this.confirm_password != this.password){
                            this.errorMensajesFormularios.push("Las Contraseñas no coinciden");
                        }
                    }
                }
                if(!this.email) this.errorMensajesFormularios.push("El campo E-mail es Obligatorio.");
                if(this.idRol.id == 2 && this.super_administrador) {
                    if(this.deptos_user == '') this.errorMensajesFormularios.push("Debe Seleccionar un departamento.");
                }
                if(this.errorMensajesFormularios.length) this.errorFormulario = 1;
                
                return this.errorFormulario;
            },
            inactivarUser(id) { // Metodo encargado de desactivar los registros 
               Swal.fire({
                    title: 'Esta seguro de Inactivar este Usuario para este departamento?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                    if (result.value) {
                            var url=   this.ruta  + '/usuarios/inactivar';
                            axios.put(url,{
                                'id': id,
                                'tipo':'1'
                            }).then(function (response) {
                                this.listarUser(1,'','nombre');
                                Swal.fire(
                                'Desactivado!',
                                'El Registro ha sido Desactivado con éxito.',
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
            inactivarUserGlobal(id) { // Metodo encargado de desactivar los registros 
               Swal.fire({
                    title: 'Esta seguro de Inactivar este Usuario para TODOS los departamentos?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                    if (result.value) {
                            var url=   this.ruta  + '/usuarios/inactivar';
                            axios.put(url,{
                                'id': id,
                                'tipo':'2'
                            }).then( (response) =>{
                                this.listarUser(1,'','nombre');
                                Swal.fire(
                                'Desactivado!',
                                'El Usuario ha sido Desactivado con éxito.',
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
            activarUser(id) { // Metodo encargado de activar los registros
                Swal.fire({
                    title: 'Esta seguro de Activar este Rol?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        var url=  this.ruta + '/usuarios/activar';
                        axios.put(url,{
                            'id': id
                        }).then( (response) => {
                            this.listarUser(1,'','nombre');
                            Swal.fire(
                            'Activado!',
                            'El Registro ha sido Activado con éxito.',
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
            activarUserGlobal(id) { // Metodo encargado de activar los registros
               Swal.fire({
                    title: 'Esta seguro de Activar este Usuario para todos los Departamentos?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                    if (result.value) {
                    
                    var url=  this.ruta + '/usuarios/activar';
                    axios.put(url,{
                        'id': id,
                        'tipo':'2'
                    }).then( (response) => {
                        this.listarUser(1,'','nombre');
                        Swal.fire(
                        'Activado!',
                        'El Usuario ha sido Activado con éxito.',
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
            disabledRol(){
                if(this.checkbox_rol){
                    this.disabled_rol = true;
                }else{
                    this.disabled_rol = false;
                }
            },
            getDepartamentos(){
                var url= this.ruta + '/departamentosCentralizado/getDepartamentos';
                axios.get(url).then( (response) => {
                    this.arrayTipoDeptos=  response.data;
                    console.log(this.arrayTipoDeptos);
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDeptoUser(id){
                var url= this.ruta + '/usuarios/getDeptoUser/'+id;
                axios.get(url).then( (response) => {
                    this.arrayDeptosUser = response.data;
                    this.arrayDeptosUser.forEach(function(data, index){
                        if(data.id_rol == '1')
                        {
                            $('#depto_'+data.id_departamento).prop('checked', true);  
                        } 
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changeDeptos(){
                if(this.idRol.id == 2 && this.super_administrador){
                    this.ocultar_deptos = true;
                  
                }else{
                     this.ocultar_deptos = false;
                }
            },
            confirmarPass(){
                this.mostrar_coinciden = true;
                if(this.confirm_password == this.password){
                    this.no_coinciden = true;
                }else{
                    this.no_coinciden = false;
                }
            },
            getUserCentralizado(){
                var url= this.ruta + '/usuarios/getUserCentralizado/';
                axios.get(url).then( (response) => {
                    this.arrayUserCentralizado = response.data;
                    let newDad = {
                        apellido: '',
                        dato_com: 'Nuevo Usuario',
                        email: '',
                        id: '',
                        nombre: '',
                        npersona: '',
                        numero_documento: '',
                        usuario: ''
                    };
                    this.arrayUserCentralizado.unshift(newDad); //inserta el elemento 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changeSelectUser(){
                if(this.IdUserCentralizado.id > 0)
                {
                    this.disbaledApellidos      = true;
                    this.disbaledApellidos      = true;
                    this.disbaledTipoDocumento  = true;
                    this.disbaledNombres        = true;
                    this.disbaledNDocumento     = true;
                    this.disbaledEmail          = true
                    this.user_disabled          = true;
                    this.disbaledObservaciones  = true;
                    this.nombre                 = this.IdUserCentralizado.npersona;
                    this.apellidos              = this.IdUserCentralizado.apellido;
                    this.usuario                = this.IdUserCentralizado.usuario;
                    this.tipo_documento         = this.IdUserCentralizado.nombre;
                    this.email                  = this.IdUserCentralizado.email;
                    this.numero_documento       = this.IdUserCentralizado.numero_documento;
                }else{
                    this.disbaledApellidos      = false;
                    this.disbaledApellidos      = false;
                    this.disbaledTipoDocumento  = false;
                    this.disbaledNombres        = false;
                    this.disbaledNDocumento     = false;
                    this.disbaledEmail          = false
                    this.user_disabled          = false;
                    this.disbaledObservaciones  = false;
                    this.nombre                 = '';
                    this.apellidos              = '';
                    this.usuario                = '';
                    this.tipo_documento         = '';
                    this.email                  = '';
                    this.numero_documento       = '';
                }
            },
            showPass(){
                if(this.modificar_pass){
                    this.cambiarPass = true;
                }else{
                    this.cambiarPass = false;
                }
            }
        },
        mounted() { // Se llama el Metodo de listar para que se renderice cada vez que se cargue el componente
            this.listarUser(1,this.buscar,this.criterio);
            this.selectTipoDoc();
            this.getDepartamentos();
            axios.get(this.ruta +'/menus/getRutaRelativa?ruta='+this.$route.name).then((response) => { this.arrayRuta = response.data; });         
        }
    }
</script>
<style>
.has_required{
    text-decoration: line-through;
    color:#1fa11f;
}
.no_coinciden{
    color:#fa1414;
}
.coinciden{
    color:#1fa11f;
}
</style>

