<template>
    <div class="bg-white main col-12">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
            crossorigin="anonymous" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
            integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
            crossorigin="anonymous" />
        <template v-if="pantalla == 'listado'">
            <div class="container mt-5">
                <label for="" class="p-2">Cabildos/Listado de cabildos </label>
                <div class="row p-2 text-center border shadow">
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-10 col-xl-10 p-2">
                            <h1 class="text-blue"><b>LISTADO DE CABILDOS</b></h1>
                        </div>
                        <div class="col-12 col-md-12 col-lg-2 col-xl-2 p-2">
                            <button type="button" class="btn btn-warning text-white w-100 mt-2" @click="crud('crear',{})">
                                Nueva sesión
                            </button>
                        </div>
                    </div>
                </div>
                <form>
                    <div class="row mt-5">
                        <div class="mb-3 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                            <label for="" class="form-label"><b>Tema</b></label>
                            <input type="text" class="form-control" v-model="filtros.nombre_tema" maxlength="100" />
                        </div>
                        <div class="mb-3 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                            <label for="" class="form-label"><b>Departamento</b></label>
                            <select v-model="filtros.dep_id" class="form-select">
                                <option value="">Selecciona</option>
                                <option v-for="(i, index) in departament" :key="index" v-text="i.nombre" :value="i.id">
                                </option>
                            </select>
                        </div>
                        <div class="mb-3 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                            <label for="" class="form-label"><b>Fecha inicio</b></label>
                            <input type="date" class="form-control" v-model="filtros.fecha_inicio" />
                        </div>
                        <div class="mb-3 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3">
                            <label for="" class="form-label"><b>Fecha final</b></label>
                            <input type="date" class="form-control" v-model="filtros.fecha_end" />
                        </div>

                        <div class="row justify-content-center">
                            <div class="mb-3 col-3">
                                <button type="button" @click="getResults" class="btn-primary btn w-80 btn_search w-100">
                                    Buscar
                                </button>
                            </div>
                            <div class="mb-3 col-12 col-sm-8"></div>
                            <div class="mb-3 col-1">
                                <button type="button" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="container table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <th class="d-flex">Opciones</th>
                        <th>Tema</th>
                        <th>Descripción</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Fecha</th>
                    </thead>
                    <tbody>
                        <tr v-for="(i, index) in cabildos.data" :key="index">
                            <td>
                                <button type="button" @click="crud('editar', i)"
                                    class="btn btn-primary btn-sm text-white">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button" class="btn btn-danger btn-sm" @click="deleteSesion(i)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                            <td>{{ i . nombre_tema }}</td>
                            <td>{{ i . description }}</td>
                            <td>{{ i . municipio . departamento . nombre }}</td>
                            <td>{{ i . municipio . nombre }}</td>
                            <td>{{ i . fecha_realizacion }}</td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="cabildos" @pagination-change-page="getResults"></pagination>
            </div>
        </template>

        <template v-if="pantalla == 'crud'">
            <div>
                <crud-cabildo :datos="datos" @pantalla="pantalla=$event" />
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                departament: [],
                cabildos: {},
                filtros: {
                    dep_id: "",
                },
                pantalla: "listado",
                datos: {
                    cabildo: {},
                    accion: ""
                },
            };
        },
        created() {
            this.getResults();
        },
        methods: {
            getResults(page = 1) {
                axios
                    .post("/data-list-cabildos?page=" + page, this.filtros)
                    .then((res) => {
                        this.cabildos = res.data.cabildos;
                        this.departament = res.data.departments;
                    });
            },
            crud(accion, i) {
                this.datos.accion = accion;
                this.datos.cabildo = i;
                this.pantalla = "crud";
            },
            deleteSesion(i) {
                Swal.fire({
                    title: "¿Eliminar registro?",
                    text: "Esta acción no se puede revertir",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#757575",
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.value) {
                        axios.get("/destroy_sesion/"+i.id).then((res) => {
                            Swal.fire("¡Perfecto!",res.data.msg,"success");
                            this.getResults();
                        });
                    }
                });
            },
        },
    };
</script>
