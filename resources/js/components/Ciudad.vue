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
                        <i class="fa fa-align-justify"></i> Ciudades
                        <button type="button" @click="abrirModal('ciudad','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                    </select> -->
                                    <div type="text"  class="form-control">Nombre</div>
                                    <input type="text" v-model="buscar" @keyup.enter="listarCiudad(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCiudad(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 140px;">Opciones</th>
                                    <th>Nombre</th>
                                    <th>Departamento</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ciudad in arrayCiudad" :key="ciudad.id">
                                    <td>
                                        <button type="button" @click="abrirModal('ciudad','actualizar',ciudad)" class="btn btn-warning btn-sm">
                                          <i class="typcn typcn-edit" style="color:white"></i>
                                        </button> &nbsp;
                                        <template v-if="ciudad.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCiudad(ciudad.id)">
                                                <i class="typcn typcn-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCiudad(ciudad.id)">
                                                <i class="typcn typcn-tick-outline"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="ciudad.nombre"></td>
                                    <td v-text="ciudad.departamento"></td>
                                    <td>
                                        <div v-if="ciudad.estado">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">inactivo</span>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Departamento</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="iddepartamento">
                                            <option value="0" disabled>Seleccione...</option>
                                            <option v-for="departamento in arrayDepartamento" :key="departamento.id" :value="departamento.id" v-text="departamento.nombre"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la Ciudad">                                        
                                    </div>
                                </div>
                                <div v-show="errorCiudad" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCiudad" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCiudad()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCiudad()">Actualizar</button>
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
                ciudad_id: 0,
                iddepartamento : 0,
                departamento : '',
                nombre : '',
                arrayCiudad : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCiudad : 0,
                errorMostrarMsjCiudad : [],
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
                arrayDepartamento :[],
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
            listarCiudad (page,buscar,criterio){
                var url = this.ruta + '/ciudad?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then( (response) =>  {
                    var respuesta = response.data;
                    this.arrayCiudad = respuesta.ciudades.data;
                    this.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectDepartamento(){
                var url = this.ruta + '/departamento/selectDepartamento';
                axios.get(url).then((response)  => {
                    var respuesta= response.data;
                    this.arrayDepartamento = respuesta.departamentos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                //Actualiza la página actual
                this.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                this.listarCiudad(page,buscar,criterio);
            },
            registrarCiudad(){
                if (this.validarCiudad()){
                    return;
                }
                
                axios.post(this.ruta + '/ciudad/store',{
                    'iddepartamento': this.iddepartamento,
                    'nombre': this.nombre
                }).then( (response) => {
                    this.cerrarModal();
                     swal.fire(
                        'Agregado!',
                        'El registro ha sido agregado con éxito.',
                        'success'
                        )
                    this.listarCiudad(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarCiudad(){
               if (this.validarCiudad()){
                    return;
                }
                
                axios.put(this.ruta + '/ciudad/update',{
                    'iddepartamento': this.iddepartamento,
                    'nombre': this.nombre,
                    'id': this.ciudad_id
                }).then((response) => {
                    this.cerrarModal();
                    swal.fire(
                        'Actualizado!',
                        'El registro ha sido actualziado con éxito.',
                        'success'
                        )
                    this.listarCiudad(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarCiudad(id){
               swal.fire({
                title: 'Esta seguro de desactivar esta Ciudad?',
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

                    axios.put(this.ruta + '/ciudad/inactivar',{
                        'id': id
                    }).then( (response) => {
                        this.listarCiudad(1,'','nombre');
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
            activarCiudad(id){
               swal.fire({
                title: 'Esta seguro de activar esta Ciudad?',
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
                    axios.put(this.ruta + '/ciudad/activar',{
                        'id': id
                    }).then((response) => {
                        this.listarCiudad(1,'','nombre');
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
            validarCiudad(){
                this.errorCiudad = 0;
                this.errorMostrarMsjCiudad = [];

                if (this.iddepartamento == 0) this.errorMostrarMsjCiudad.push("Seleccione un Departamento.");
                if (!this.nombre) this.errorMostrarMsjCiudad.push("El nombre de la Ciudad no puede estar vacío.");
                
                if (this.errorMostrarMsjCiudad.length) this.errorCiudad = 1;

                return this.errorCiudad;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.iddepartamento = 0;
                this.nombre = '';
                this.errorCiudad = 0;
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "ciudad":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Ciudad';
                                this.iddepartamento = 0;
                                this.nombre = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                console.log(data);
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Ciudad';
                                this.tipoAccion = 2;
                                this.ciudad_id = data['id'];
                                this.iddepartamento = data['id_departamento'];
                                this.nombre = data['nombre'];
                                break;
                            }
                        }
                    }
                }
                this.selectDepartamento();
            }
        },
        mounted() {
            this.listarCiudad(1,this.buscar,this.criterio);
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
