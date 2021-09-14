<template>
    <main class="main col-12">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"><router-link :to="{ name: 'cambiar_pass'}"><span>Cambiar Contraseña</span></router-link></li>
            </ol>

        <div class="col-12">
            <div><h1 v-text="nombreForm"></h1></div>
            <template v-if="listado==1">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">

                        <form class="form-sample">

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Contraseña Actual</label>
                                <div class="col-sm-9">
                                    <div v-show="errorActual"> <span style="color:red">* Campo obligatorio</span></div>
                                    <div class="input-group row">
                                        <input :type="fieldActual" v-model="actual" class="form-control" @keyup="verficarCampo(actual,'actual')"  :placeholder="'Contraseña Actual'" >
                                        <div class="input-group-append">
                                            <button class="btn btn-light" type="button" @click="visibilidad(actual,'actual')"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Contraseña Nueva</label>
                                <div class="col-sm-9">
                                    <div v-show="errorpass"> <span style="color:red">* Campo obligatorio</span></div>
                                    <div class="input-group row">
                                        <input :type="fieldPass" v-model="pass" class="form-control" @keyup="validaContrasena"  :placeholder="'Contraseña Nueva'">
                                        <div class="input-group-append">
                                            <button class="btn btn-light" type="button" @click="visibilidad(pass,'pass')"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    <div>
                                        <small style="padding-top: 5px;">Tu contraseña debe tener: </small>
                                        <br>
                                        <small v-if="valCarac==1" style="color: green;"><i class="fa fa-check" aria-hidden="true"></i>Al menos 8 caracteres</small>
                                        <small v-else style="color: red;">- Al menos 8 caracteres</small>
                                        <br>
                                        <small v-if="valMayus==1" style="color: green;"><i class="fa fa-check" aria-hidden="true"></i>Al menos una mayúscula</small>
                                        <small v-else style="color: red;">- Al menos una mayúscula</small>
                                        <br>
                                        <small v-if="valMinus==1" style="color: green;"><i class="fa fa-check" aria-hidden="true"></i>Al menos una minúscula</small>
                                        <small v-else style="color: red;">- Al menos una minúscula</small>
                                        <br>
                                        <small v-if="valNumer==1" style="color: green;"><i class="fa fa-check" aria-hidden="true"></i>Al menos un Número</small>
                                        <small v-else style="color: red;">- Al menos un Número</small>
                                        <br>
                                        <small v-if="valEspec==1" style="color: green;"><i class="fa fa-check" aria-hidden="true"></i>Al menos un Caracter Especial</small>
                                        <small v-else style="color: red;">- Al menos un Caracter Especial</small>
                                        <br>
                                        <small v-if="blanco==1" style="color: red;">- Los espacios no son validos!</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Confirmar Contraseña Nueva</label>
                                <div class="col-sm-9">
                                    <div v-show="errorpassconf"> <span style="color:red">* Las contraseñas no coinciden</span></div>
                                    <div class="input-group row">
                                        <input :type="fieldPassConf" v-model="passConf" class="form-control" @keyup="validaContrasena"  :placeholder="'Contraseña Nueva'">
                                        <div class="input-group-append">
                                            <button class="btn btn-light" type="button" @click="visibilidad(passConf,'passConf')" ><i class="fa fa-eye" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-show="errorFormulario" class="form-group text-center">
                                <div class="text-center">
                                    <div class="alert alert-info" role="alert" v-for="error in errorMensajesFormularios" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div>
                                    <button type="button" @click="actualizarElemento()" class="btn btn-primary">Actualizar</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </main>
</template>

<script>
    export default {
        props : ['ruta'],
        data (){
            return {
                // atributos
                actual: '',      errorActual: 0,
                pass: '',        errorpass: 0,
                passConf: '',    errorpassconf: 0,
                valCarac: 0,
                valMayus: 0,
                valMinus: 0,
                valNumer: 0,
                valEspec: 0,
                blanco: 0,

                fieldActual: 'password',
                fieldPass: 'password',
                fieldPassConf: 'password',
                // fin atributos

                nombreForm: '',                         // Titulo del formulario en todas las vistas
                nombreFormSing: 'cambiar contraseña',   // Titulo en singular del formulario
                permiso: '',                            // Nombre del permiso para el formulario ej: 'add_' + [[ permiso ]]
                arrayRuta: [],                          // ruta del formulario para la Breadcrumb
                listado: 1,                             // template que carga por cada vista usada
                tituloFormulario : '',                  // titulo del formulario usado ej: Crear Nuevo elemento, Actualizar elemento
                tipoAccion : 0,                         // accion que ejecuta el formulario, 1 = crear, 2 = Actualizar
                errorMensajesFormularios : [],          // array para los errores de validacion del formulario
                errorFormulario : 0,                    // variable para mostrar la vista con los errores de validacion, 0 = oculto, 1 = mostrar

                loading: 0,
            }
        },
        components:{
        },
        computed:{
        },
        methods : {
            actualizarElemento(tipoAccion){ // OK Método encargado de actualizar los registros
                if(this.validacionFormulario(tipoAccion)){
                    return;
                }
                var url= this.ruta + '/usuarios/cambiarcontraseña';
                axios.put(url,{
                    'contrasenaActual': this.actual,
                    'contrasenaNueva': this.pass,
                }).then((response) => {
                    if(response.data.exito){
                        this.alertaExito("La contraseña ha sido actualizada!");
                        this.$router.push('/');
                    }
                    else{
                        var msj = "";
                        var json = response.data.errores;
                        var valor = response.data.valor;
                        for (var i in json) { msj += json[i]+" ," }

                        if(valor == 1){ this.errorActual = 1; }
                        if(valor == 2){ this.errorpass = 1; }

                        this.alertaError(msj);
                        return;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                })
                .finally(() => (this.loading = 0));
            },
            validaContrasena(){ // OK Método encargado de validar que se cumplan los parametros de la contraseña
                this.valCarac = this.valMayus = this.valMinus = this.valNumer = this.valEspec = this.blanco = 0;
                this.errorpass = 1;
                if(this.pass.length >= 8){ this.valCarac = 1; }
                for(var i = 0;i<this.pass.length;i++){
                    if(this.pass.charCodeAt(i) >= 65 && this.pass.charCodeAt(i) <= 90){
                        this.valMayus = 1;
                    }
                    else if(this.pass.charCodeAt(i) >= 97 && this.pass.charCodeAt(i) <= 122){
                        this.valMinus = 1;
                    }
                    else if(this.pass.charCodeAt(i) >= 48 && this.pass.charCodeAt(i) <= 57){
                        this.valNumer = 1;
                    }
                    else if(this.pass.charCodeAt(i) == 32){
                        this.blanco = 1;
                    }
                    else{
                        this.valEspec = 1;
                    }
                }
                if(this.valCarac == 1 && this.valMayus == 1 && this.valMinus == 1 && this.valEspec == 1 && this.valNumer == 1 && this.blanco == 0){
                    this.errorpass = 0;
                    return true;
                }
                return false;
            },
            validaContrasenaIgual(){ // OK Método encargado de comparar las contraseñas
                if(this.pass != this.passConf){
                    this.errorpassconf = 1;
                }
                else{
                    this.errorpassconf = 0;
                }

            },
            visibilidad(campo,txtError){ // OK Método encargado de mostrar u ocultar contraseña
                switch (txtError) {
                    case 'actual': (this.fieldActual === 'password') ? this.fieldActual = 'text' : this.fieldActual ='password'; break;
                    case 'pass': (this.fieldPass === 'password') ? this.fieldPass = 'text' : this.fieldPass ='password'; break;
                    case 'passConf': (this.fieldPassConf === 'password') ? this.fieldPassConf = 'text' : this.fieldPassConf ='password'; break;
                    default: break;
                }
            },
            verficarCampo(campo,txtError){ // OK
                switch (txtError) {
                    case 'actual': if(!campo.trim()) this.errorActual = 1; else this.errorActual = 0; break;
                    case 'pass': if(!campo.trim()) this.errorpass = 1; else this.errorpass = 0; break;
                    case 'passConf': if(!campo.trim()) this.errorpassconf = 1; else this.errorpassconf = 0; break;
                    default: break;
                }
            },
            validacionFormulario(tipoAccion) { // OK Metodo encargado de la validacion de los campos de los formularios
                this.errorFormulario = 0;
                var errors = 0;
                this.errorMensajesFormularios = [];
                if(this.actual.trim()){ this.errorActual = 0; }else{ this.errorActual = 1; errors +=1; }
                if(this.pass.trim()){ this.errorpass = 0; }else{ this.errorpass = 1; errors +=1; }
                if(this.passConf.trim() && (this.pass == this.passConf)){ this.errorpassconf = 0; }else{ this.errorpassconf = 1; errors +=1; }
                if(this.validaContrasena()){ this.errorpass = 0; }else{ this.errorpass = 1; errors +=1; }

                if(errors > 0) this.errorMensajesFormularios.push("Completar todos los requerimientos.");
                if(this.errorMensajesFormularios.length) this.errorFormulario = 1;

                return this.errorFormulario;
            },
            alertaExito(msj){
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito!',
                    html: msj,
                    showConfirmButton: false,
                    timer: 1600
                })
            },
            alertaError(msj){
                swal.fire({
                    icon: 'error',
                    title: 'Error al actualizar Contraseña.',
                    text: msj,
                    showConfirmButton: true,
                    // timer: 2350
                });
            }
        },
        mounted() {
        }
    }

</script>
