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
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Departamentos
                        <button type="button" @click="abrirModal('departamento','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarDepartamento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarDepartamento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 140px;">Opciones</th>
                                    <th>Nombre</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="departamento in arrayDepartamento" :key="departamento.id">
                                    <td>
                                        <button type="button" @click="abrirModal('departamento','actualizar',departamento)" class="btn btn-warning btn-sm">
                                          <i class="typcn typcn-edit" style="color:white"></i>
                                        </button> &nbsp;
                                        <template v-if="departamento.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarDepartamento(departamento.id)">
                                                <i class="typcn typcn-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarDepartamento(departamento.id)">
                                                <i class="typcn typcn-tick-outline"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="departamento.nombre"></td>
                                    <td>
                                        <div v-if="departamento.estado">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Inactivo</span>
                                        </div>
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
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
                <!-- Fin ejemplo de tabla Listado -->
                    <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de Departamento">                                        
                                    </div>
                                </div>
                                <div v-show="errorDepartamento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjDepartamento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarDepartamento()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarDepartamento()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </div>
        
    </main>
</template>

<script>   
    export default {
        props : ['ruta'],
        data (){
            return {
                departamento_id: 0,
                nombre : '',
                arrayDepartamento : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorDepartamento : 0,
                errorMostrarMsjDepartamento : [],
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
                arrayPais :[],
                arrayRuta: [], 
            }
        },
        components: {
    },
        computed:{
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
            listarDepartamento (page,buscar,criterio){
                var url= this.ruta + '/departamento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then((response) => {
                    var respuesta= response.data;
                    this.arrayDepartamento = respuesta.departamentos.data;
                    this.pagination        = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                //Actualiza la página actual
                this.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                this.listarDepartamento(page,buscar,criterio);
            },
            registrarDepartamento(){
                if (this.validarDepartamento()){
                    return;
                }
                
                axios.post(this.ruta + '/departamento/store',{
                    'nombre': this.nombre
                }).then( (response) => {
                    this.cerrarModal();
                    Swal.fire(
                                'Agregado!',
                                'Agrego el departamento exitosamente.',
                                'success'
                                )
                    this.listarDepartamento(1,'','nombre');

                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarDepartamento(){
               if (this.validarDepartamento()){
                    return;
                }              

                axios.put(this.ruta + '/departamento/update',{
                    'idpais': this.idpais,
                    'nombre': this.nombre,
                    'id'    : this.departamento_id
                }).then((response) => {
                    this.cerrarModal();
                    this.listarDepartamento(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarDepartamento(id){
               swal.fire({
                title: 'Esta seguro de desactivar este Departamento?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    axios.put(this.ruta + '/departamento/inactivar',{
                        'id': id
                    }).then((response) => {
                        this.listarDepartamento(1,'','nombre');
                        swal.fire(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarDepartamento(id){
               swal.fire({
                title: 'Esta seguro de activar este Departamento?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    axios.put(this.ruta + '/departamento/activar',{
                        'id': id
                    }).then((response) => {
                        this.listarDepartamento(1,'','nombre');
                        swal.fire(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            validarDepartamento(){
                this.errorDepartamento=0;
                this.errorMostrarMsjDepartamento =[];

                if (!this.nombre) this.errorMostrarMsjDepartamento.push("El nombre del Departamento no puede estar vacío.");
                
                if (this.errorMostrarMsjDepartamento.length) this.errorDepartamento = 1;

                return this.errorDepartamento;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre = '';
                this.errorDepartamento=0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "departamento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Departamento';
                                this.nombre= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Departamento';
                                this.tipoAccion=2;
                                this.departamento_id=data['id'];
                                this.nombre = data['nombre'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarDepartamento(1,this.buscar,this.criterio);
            axios.get(this.ruta +'/menus/getRutaRelativa?ruta='+this.$route.name).then((response) => { this.arrayRuta = response.data; });         
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
