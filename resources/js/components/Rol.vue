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
                        <i class="fa fa-align-justify"></i> Roles
                        <div v-permiso="'add_roles'">
                            <template>
                                <button type="button" @click="ingresarRol('rol','create')" class="btn btn-secondary">
                                    <i class="icon-plus"></i> Nuevo
                                </button>
                            </template>
                         </div>
                    </div>
                    <div v-permiso="'view_roles'">
                        <template>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-control col-md-3" v-model="criterio">
                                                <option value="name">Nombre</option>
                                                <option value="description">Descripción</option>
                                            </select>
                                            <input type="text" v-model="buscar" @keyup="listarRol(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="submit" @click="listarRol(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
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
                                                <th>Descripción</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-if="arrayRol.length != 0">
                                                    <tr v-for="rol in arrayRol" :key="rol.id">  
                                                    <td>
                                                        <template>
                                                            <button type="button" title="Asignar Permisos" @click="obtenerPermisosRol(rol.name, rol.id)" class="btn btn-inverse-info btn-sm">
                                                            <i class="typcn typcn-arrow-sync"></i>
                                                            </button> &nbsp;
                                                        </template>
                                                        <template>
                                                            <button type="button" title="Editar" @click="ingresarRol('rol','update',rol)" class="btn btn-inverse-warning btn-sm">
                                                            <i class="typcn typcn-edit"></i>
                                                            </button> &nbsp;
                                                        </template>
                                                        <template>
                                                            <template v-if="rol.status" class="preview">
                                                                <button type="button" title="Inactivar" class="btn btn-inverse-danger btn-sm" @click="inactivarRol(rol.id)">
                                                                    <i class="typcn typcn-trash"></i>
                                                                </button>
                                                            </template>
                                                            <template v-else>
                                                                <button type="button" title="Activar" class="btn btn-inverse-success btn-sm" @click="activarRol(rol.id)">
                                                                    <i class="typcn typcn-tick-outline"></i>
                                                                </button>
                                                            </template>
                                                        </template>
                                                    </td>
                                                    <td v-text="rol.name"></td>
                                                    <td v-text="rol.description"></td>
                                                    <td>
                                                        <div v-if="rol.status">
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
                        </template>
                    </div>
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
                                <div class="form-group">
                                <label>Descripcion</label>
                                    <textarea v-model="descripcion" class="form-control" maxlength="100" rows="3" placeholder="Maximo 250 Caracteres."></textarea>
                                </div>
                                <div v-show="errorFormulario" class="form-group text-center">
                                    <div class="badge badge-warning col-md-10">
                                        <div v-for="error in errorMensajesFormularios" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            <template>
                                <button v-if="tipoAccion==1" @click="createRol()" type="button" class="btn btn-primary mr-2">Guardar</button>
                            </template>
                            <template>
                                <button v-if="tipoAccion==2" @click="updateRol()" type="button" class="btn btn-primary mr-2">Actualizar</button>
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
                        <div class="spinner-card" style="display: none">
                            <div class="sk-chase">
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                                <div class="sk-chase-dot"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title text-center" v-text="roleName"></h4>
                            <br>
                            <form>
                                <div>
                                    <p class="card-description text-center" v-text="tituloFormulario"></p>
                                </div>
                                <br>
                                <div class="row">  
                                    <div class="col-md-3 col-lg-3" v-for="(arrayPermiso, index) in arrayPermisos" :index="index">
                                        <div class="accordion" id="accordionExample">
                                                <div class="card">
                                                    <div class="card-header" style="margin-bottom: -45px;margin-top: -23px;" :id="'headingOne'+index" >
                                                        <h2>
                                                            <div class="form-group form-check">
                                                                <input :class="'form-check-input padre_'+index" :value="index" @input="checkear_inputs($event.target.checked,$event.target.value)" style="margin-top: 13px;"  type="checkbox" />
                                                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" :data-target="'#collapse_'+index"  aria-expanded="false" :aria-controls="'collapse_'+index" >
                                                                    <span  v-text="arrayPermiso.nombre"></span>
                                                                </button>                                                       
                                                                <small style="font-size:12px">{{arrayPermiso.ruta}}</small>
                                                            </div>                                               
                                                        </h2>
                                                    </div>
                                                    <div  :id="'collapse_'+index" class="collapse"  :aria-labelledby="'headingOne'+index"  data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <div v-for="arrayPermisoSub in arrayPermiso.permisos">
                                                                <label>
                                                                    <input name="permissions[]"  :id="'id_permisos_'+arrayPermisoSub.id_permiso" :class="'checkbox-permisos-id check_'+index" type="checkbox" :value="arrayPermisoSub.id_permiso">
                                                                    <span class="" v-text="arrayPermisoSub.name_permiso"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                     
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <button v-if="listado==3" @click="actualizarPermisos()" type="button" class="btn btn-primary mr-2">Guardar</button>
                                <button class="btn btn-light" @click="cancelarIngreso()">Cancelar</button>
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
                arrayRuta: [], 
                idRol : 0,
                idRuta : 0,
                nombre : '',
                descripcion : '',
                arrayRol : [],
                arraygrupoRutas : [],
                arrayRutas : [],
                arrayrolRutas : [],
                arrayItems : [],
                arrayRutasExist : [],
                arrayPermisosRol: [],
                listado : 1,
                tituloFormulario : '',
                tipoAccion : 0,
                bandera : 0,
                errorFormulario : 0,
                arrayPermisos: [],
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
                habilidadPanel: '',
                roleName: '',
                roleId: '',
                index_permisos: 0,
            }
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
                this.listarRol(page,buscar,criterio);
            },
            listarRol (page,buscar,criterio){ // Metodo encargado de enviar los datos al listado
                $('.spinner').css('display', 'block');

                var url= this.ruta +  '/rol?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then((response) =>  {
                    this.arrayRol = response.data.roles.data;
                    this.pagination= response.data.pagination;

                    $('.spinner').css('display', 'none');

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cancelarIngreso(){ // Metodo encargado de cancelar las acciones en los formularios
                this.listado=1;
                this.tituloFormulario = '';
                this.idRol = 0;
                this.idRuta = 0;
                this.nombre = '';
                this.descripcion = '';
                this.arrayRutas = [];
                this.arrayItems = [];
                this.errorFormulario = ''; 
                this.roleName = '';
                this.roleId = '';
            },
            ingresarRol (modelo, accion, data = []){ // Metodo encargado de cargar los formularios de crear y actualizar
                this.listado=2;
                switch(modelo){
                    case "rol":
                    {
                        switch(accion){
                            case 'create':
                            {   
                                this.tituloFormulario = "Registrar Rol";
                                this.nombre = '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'update':
                            {
                                this.tituloFormulario = "Actualizar el Rol";
                                this.nombre = data['name'];
                                this.descripcion = data['description'];
                                this.idRol = data['id'];
                                this.tipoAccion = 2;
                                break;
                            }
                        }
                    }
                }
            },
            createRol() { // Metodo encargado de realizar el envio de datos para la creacion
                if(this.validacionFormulario()){
                    return;
                }
                var url= this.ruta +  '/rol/create';
                axios.post(url,{
                    'nombre' : this.nombre,
                    'descripcion' : this.descripcion
                }).then((response) => {
                    this.cancelarIngreso();
                    this.listarRol(1,'','nombre');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            updateRol() { // Metodo encargado de realizar el envio de datos para la actualizacion
                if(this.validacionFormulario()){
                    return;
                }

                var url= this.ruta + '/rol/update';
                axios.put(url,{
                    'nombre' : this.nombre,
                    'descripcion' : this.descripcion,
                    'id' : this.idRol
                }).then((response) => {
                    this.cancelarIngreso();
                    this.listarRol(1,'','nombre');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validacionFormulario() { // Metodo encargado de la validacion de los campos de los formularios
                this.errorFormulario = 0;
                this.errorMensajesFormularios = [];
                if(!this.nombre) this.errorMensajesFormularios.push("El campo Nombre del Rol es Obligatorio.");
                if(this.errorMensajesFormularios.length) this.errorFormulario = 1;
                
                return this.errorFormulario;
            },
            actualizarPermisos()
            {
                var url= this.ruta +  '/rol/updatePermisos';

                var permission = $('input[name="permissions[]"]:checked').map(function(){
                    return this.value; 
                }).get();

                axios.put(url,{
                    'permissions' : permission,
                    'role': this.roleId
                }).then((response) => {
                    swal.fire({
                        icon: response.data.status,
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1750
                    });

                    this.cancelarIngreso();
                    this.listarRol(1,'','nombre');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            obtenerPermisosRol(rolName, rolId)
            {
                $('.spinner-card').css('display', 'block');//mirar mas tarde

                this.roleName = 'Permisos del rol ' + rolName;
                this.roleId = rolId;

                $('.checkbox-permisos-id').prop('checked', false);      

                var url=  this.ruta + '/rol/obtenerRolPermisos?name='+rolName;

                axios.get(url).then((response) => {
                    this.arrayPermisosRol = response.data.roles_permission;

                    this.arrayPermisosRol.forEach(function(data, index)
                    {
                        $('#id_permisos_'+data.id).prop('checked', true);      
                    });
                    this.checkear_inputs_padre(this.arrayPermisosRol);
                    //$('.spinner-card').css('display', 'none');
                })
                .catch(function (error) {
                    console.log(error);
                });
                
                this.listado = 3;
            },
            inactivarRol(id) { // Metodo encargado de desactivar los registros 
               Swal.fire({
                    title: 'Esta seguro de Inactivar este Rol?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            var url= this.ruta + '/rol/inactivar';
                            axios.put(url,{
                                'id': id
                            }).then((response) => {
                                this.listarRol(1,'','nombre');
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
            activarRol(id) { // Metodo encargado de activar los registros
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
                        var url=  this.ruta + '/rol/activar';
                        axios.put(url,{
                            'id': id
                        }).then((response) => {
                            this.listarRol(1,'','nombre');
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
            consultarPermisos(idRol) {
                this.tituloFormulario = "Asignacion de Permisos";
                var url= this.ruta +  '/permission/obtenerPermisos';
                axios.get(url).then((response) => {
                    this.arrayPermisos = response.data.permisos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            checkear_inputs(check,value){
                $('.check_'+value).prop('checked',check);
            },
            checkear_inputs_padre(id_permiso){
                var validarExistente;
                var ho = Object.keys(this.arrayPermisos);
    
                Object.values(this.arrayPermisos).forEach(function(element,index,array){
                    Object.values(element.permisos).forEach(element2 => {                             
                        validarExistente = id_permiso.some(buscar => buscar.id === element2.id_permiso);
                        if(validarExistente){
                            $('.padre_'+ho[index]).prop('checked',true);
                        }
                    });  
                });
            }
        },
        mounted() { 
            axios.get('/menus/getRutaRelativa?ruta='+this.$route.name).then((response) => { this.arrayRuta = response.data; });   
            this.listarRol(1,this.buscar,this.criterio);
            this.consultarPermisos();
        }
    }
</script>
