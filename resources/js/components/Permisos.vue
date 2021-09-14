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
                        <i class="fa fa-align-justify"></i> Permisos
                        <div v-permiso="'add_permisos'">
                            <template>
                                <button type="button" @click="ingresarPermiso('permiso','create')" class="btn btn-secondary">
                                    <i class="icon-plus"></i> Nuevo
                                </button>
                            </template>
                        </div>
                    </div>
                    <template>
                        <div v-permiso="'view_permisos'">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control col-md-3" v-model="criterio">
                                                <option value="name">Nombre</option>
                                            </select>
                                            <input type="text" v-model="buscar" @keyup="listarPermisos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarPermisos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                                <th>Menú</th>
                                                <th>Fecha Creación</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="arrayPermisos.length != 0">
                                                    <tr v-for="permiso in arrayPermisos" :key="permiso.id">  
                                                    <td>
                                                        <template>
                                                        <!-- <template v-if="$can('edit_permisos')"> -->
                                                            <button type="button" title="Editar" @click="ingresarPermiso('permiso','update',permiso)" class="btn btn-inverse-warning btn-sm">
                                                            <i class="typcn typcn-edit"></i>
                                                            </button> &nbsp;
                                                        </template>
                                                        <template>
                                                        <!-- <template v-if="$can('delete_permisos')"> -->
                                                            <template v-if="permiso.status" class="preview">
                                                                <button type="button" title="Inactivar" class="btn btn-inverse-danger btn-sm" @click="cambiarEstado(permiso.id, permiso.status)">
                                                                    <i class="typcn typcn-trash"></i>
                                                                </button>
                                                            </template>
                                                            <template v-else>
                                                                <button type="button" title="Activar" class="btn btn-inverse-success btn-sm" @click="cambiarEstado(permiso.id, permiso.status)">
                                                                    <i class="typcn typcn-tick-outline"></i>
                                                                </button>
                                                            </template>
                                                        </template>
                                                    </td>
                                                    <td v-text="permiso.name"></td>
                                                    <td v-text="permiso.nombre_menu"></td>
                                                    <td v-text="permiso.created_at"></td>
                                                    <td>
                                                        <div v-if="permiso.status">
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
                                                    <td colspan="10"><span>No se encontraron resultados</span></td>
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
                        <label >Nombre</label>
                            <input type="text" v-model="nombre" class="form-control" placeholder="Nombre">
                        </div>
                        <div  class="form-group">
                            <label for="">Seleccionar Componente</label>
                            <v-select
                                v-model="id_componente"
                                :options="arrayComponentes"
                                label="name"
                                :value="id_componente">
                            </v-select>
                        </div>
                        <div v-show="errorFormulario" class="form-group text-center">
                            <div class="badge badge-warning col-md-10">
                                <div v-for="error in errorMensajesFormularios" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                        <template>
                            <div v-permiso="'add_permisos'">
                                <button v-if="tipoAccion==1" @click="createPermiso()" type="button" class="btn btn-primary mr-2">Guardar</button>
                            </div>
                        </template>
                        <template>
                            <div v-permiso="'edit_permisos'">
                                <button v-if="tipoAccion==2" @click="updatePermiso()" type="button" class="btn btn-primary mr-2">Actualizar</button>
                            </div>
                        </template>
                        <button class="btn btn-light" @click="cancelarIngreso()">Cancelar</button>
                    </form>
                    </div>
                </div>
            </div>
            </template>
            <!-- Fin Formulario de Ingreso -->
            <!-- Formulario de Asignacion de Permisos--> 
            <template v-if="listado==3">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" v-text="tituloFormulario"></h4>
                        <p class="card-description">Seleccione el Permiso que Desee Asignar</p>
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div>
                                        <h1 class="card-title" v-text="tituloFormulario"></h1>
                                    </div>
                                    <div class="form-group">
                                        <div v-for="(ruta, indice) in arrayRutas" :key="ruta.id" class="form-check">
                                            <label class="form-check-label">
                                                <input :value="ruta.id" type="checkbox" v-model="arrayRutasExist[indice]">
                                                <i v-text="ruta.nombre" class="input-helper"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <button v-if="tipoAccion==1" @click="getPermisos()" type="button" class="btn btn-primary mr-2">Guardar</button>
                                    <button class="btn btn-light" @click="cancelarIngreso()">Cancelar</button>
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
    export default {
        props : ['ruta'],
        data (){ // Resive los Datos que llegan
            return {
                // Inicialización de Variables
                idPermiso : 0,
                idRuta : 0,
                nombre : '',
                descripcion : '',
                arrayPermisos : [],
                arraygrupoRutas : [],
                arrayRutas : [],
                arrayPermisosRutas : [],
                arrayItems : [],
                arrayRutasExist : [],
                listado : 1,
                tituloFormulario : '',
                tipoAccion : 0,
                bandera : 0,
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
                criterio : 'name',
                buscar : '',
                arrayComponentes: [],
                id_componente: {
                    'id': '',
                    'name': ''
                },
                nombre_componente: '',
                arrayRuta: [],  
            }
        },
        components: {
        },
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
                //Actualiza la página actual
                this.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                this.listarPermisos(page,buscar,criterio);
            },
            listarPermisos (page,buscar,criterio){ // Metodo encargado de enviar los datos al listado
                $('.spinner').css('display', 'block');

                var url=  this.ruta + '/permission?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then((response) => {
                    var respuesta= response.data;
                    this.arrayPermisos = respuesta.permission.data;
                    this.pagination= respuesta.pagination;

                    $('.spinner').css('display', 'none');

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cancelarIngreso(){ // Metodo encargado de cancelar las acciones en los formularios
                this.listado=1;
                this.tituloFormulario = '';
                this.idPermiso = 0;
                this.idRuta = 0;
                this.nombre = '';
                this.descripcion = '';
                this.arrayRutas = [];
                this.arrayItems = [];
                this.errorFormulario = ''; 
            },
            ingresarPermiso (modelo, accion, data = []){ // Metodo encargado de cargar los formularios de crear y actualizar
                this.listado=2;
                switch(modelo){
                    case "permiso":
                    {
                        switch(accion){
                            case 'create':
                            {   
                                this.tituloFormulario = "Registrar Permisos";
                                this.nombre = '';
                                this.descripcion = '';
                                this.id_componente= {
                                    'id': '',
                                    'name': ''
                                },
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'update':
                            {
                                this.tituloFormulario = "Actualizar Permiso";
                                this.nombre = data['name'];
                                this.idPermiso = data['id'];
                                this.id_componente = {
                                    'id' : data['id_menu'],
                                    'name' : data['nombre_menu']
                                };
                                this.tipoAccion = 2;
                                break;
                            }
                        }
                    }
                }
            },
            createPermiso() { // Metodo encargado de realizar el envio de datos para la creacion
                if(this.validacionFormulario()){
                    return;
                }
                var url= this.ruta + '/permission/create';
                axios.post(url,{
                    'name' : this.nombre,
                    'id_componente' : this.id_componente.id,
                }).then((response) => {
                    this.cancelarIngreso();
                    this.listarPermisos(1,'','nombre');

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
            updatePermiso() {
                if(this.validacionFormulario()){
                    return;
                }
                var url= this.ruta + '/permission/update';
                axios.put(url,{
                    'name' : this.nombre,
                    'id' : this.idPermiso,
                    'id_componente' : this.id_componente.id,
                }).then((response) => {
                    this.cancelarIngreso();
                    this.listarPermisos(1,'','nombre');

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
            validacionFormulario() { // Metodo encargado de la validacion de los campos de los formularios
                this.errorFormulario = 0;
                this.errorMensajesFormularios = [];
                if(!this.nombre) this.errorMensajesFormularios.push("El campo Nombre del Rol es Obligatorio.");
                if(!this.id_componente) this.errorMensajesFormularios.push("Debe selecionar un item del menú.");
                if(this.errorMensajesFormularios.length) this.errorFormulario = 1;
                
                return this.errorFormulario;
            },
            cambiarEstado(id, estado) { // Metodo encargado de desactivar los registros 
                var title = estado == 1 ? 'Inactivar' : 'Activar';
                var title_es = estado == 1 ? 'Desactivado' : 'Activado';
                var estado_enviar = estado == 1 ? 0 : 1;

                Swal.fire({
                    title: 'Esta seguro de '+title+' este Permiso?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.value) {
                        var url= this.ruta +  '/permission/cambiarEstado';
                        axios.put(url,{
                            'id': id,
                            'estado': estado_enviar
                        }).then((response) => {
                            this.listarPermisos(1,'','nombre');
                            Swal.fire(
                            title_es+'!',
                            'El Permiso ha sido '+title_es+' con éxito.',
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
            getComponentes(){
                var url = this.ruta + "/menus/getMenus";
                axios.get(url).then((response) => {
                    this.arrayComponentes = response.data.data;
                }).catch(function(e){
                    console.log(e);
                });
            },
        },
        mounted() { // Se llama el Metodo de listar para que se renderice cada vez que se cargue el componente
            this.listarPermisos(1,this.buscar,this.criterio);
            this.getComponentes();
            axios.get(this.ruta +'/menus/getRutaRelativa?ruta='+this.$route.name).then((response) => { this.arrayRuta = response.data; }); 
        }
    }
</script>
