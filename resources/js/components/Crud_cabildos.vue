<template>
    <div>
        <div class="container mt-5">
            <label for="" class="p-2">Cabildos/Listado de cabildos/Editar sesión</label>
            <div class="row p-2 text-center border shadow">
                <div class="col-10">
                    <h1 class="text-blue">
                        <b v-if="datos.accion=='editar'">EDITAR SESIÓN</b>
                        <b v-else>CREAR SESIÓN</b>
                        </h1>
                </div>
                <div class="col-2 mt-2">
                    <button type="button" class="btn btn-block btn-warning text-white" @click="regresar">
                        Regresar
                    </button>
                </div>
            </div>
            <form>
                <!-- @include('modals.edit-file') -->
                <input type="hidden" name="id_record" />
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-5">
                        <div class="row">
                            <div class="mb-3">
                                <label for="" class="form-label"><b>Tema *</b></label>
                                <input v-model="cabildo.nombre_tema" type="text" class="form-control" maxlength="250" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="" class="form-label"><b>Descripción *</b></label>
                                <textarea class="form-control" style="height: 150px" maxlength="1000" v-model="cabildo.description">
                </textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="" class="form-label"><b>Departamento *</b></label>
                                <select class="form-select" aria-label="Default select example"
                                    v-model="cabildo.departamento" id="departamento_id">
                                    <option v-for="(i, index) in departamentos" :key="index" :value="i.id"
                                        v-text="i.nombre"></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="" class="form-label"><b>Ciudad *</b></label>
                                <select class="form-select" name="municipality" id="municipio"
                                    v-model="cabildo.ciu_id">
                                    <option>Seleccione ...</option>
                                    <option v-for="(i,index) in departamento.ciudades" :key="index"></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="" class="form-label"><b>Fecha de agendamiento *</b>
                                </label>
                                <div class="input-group">
                                    <input type="date" class="form-control" v-model="cabildo.fecha_realizacion" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-5">
                        <div class="row">
                            <label for="" class="form-label"><b>Tipo de documento *</b>
                            </label>
                            <select class="form-select">
                                <option v-for="(i, index) in tipo_documentos" :key="index" :value="i.id"
                                    v-text="i.nombre"></option>
                            </select>
                        </div>
                        <div class="row mt-3">
                            <label for="" class="form-label"><b>Radicado CNE *</b></label>
                            <input type="text" class="form-control" maxlength="30" v-model="cabildo.radicado_CNE" />
                        </div>
                        <div class="row mt-5">
                            <div class="form-group files border" role="button" id="box_file" @click="box_file">
                                <div class="row mt-5">
                                    <img class="img_file mx-auto d-block" src="" alt="" />
                                </div>
                                <div class="row mt-1 mb-5">
                                    <p class="text_file text-center">Edita tus documentos aquí</p>
                                </div>
                            </div>
                            <input id="file" type="file" class="form-control d-none file" />
                        </div>
                        <div class="row mt-5">
                            <button type="button" class="btn-primary btn" @click="store">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["datos"],
        data() {
            return {
                departamentos: [],
                tipo_documentos: [],
                cabildo: {},
            };
        },
        created() {
            this.getResults();
        },
        methods: {
            getResults() {
                axios.post("/edit-sesion").then((res) => {
                    this.departamentos = res.data.departamentos;
                    this.tipo_documentos = res.data.tipo_documentos;
                    this.cabildo = this.datos.cabildo
                });
            },
            regresar() {
                this.$emit("pantalla", "listado");
            },
            store(){
                alert('llego')
            },
            box_file(){
                $('#file').trigger('click')
            },
        },
    };
</script>
