<template>
  <div id="sesion">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
      crossorigin="anonymous"
    />

    <div class="container mt-5">
      <label for="" class="p-2"
        >Cabildos/Listado de cabildos/Nueva sesión
      </label>
      <div class="row p-2 text-center border shadow">
        <div class="row">
          <h1 class="text-blue"><b>NUEVA SESIÓN</b></h1>
        </div>
      </div>

      <form @submit.prevent="save">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-5">
            <div class="row">
              <div class="mb-3">
                <label for="" class="form-label"><b>Tema *</b></label>
                <input
                  type="text"
                  class="form-control"
                  v-model="sesion.theme"
                  maxlength="250"
                  name="theme"
                />
              </div>
            </div>
            <div class="row">
              <div class="mb-3">
                <label for="" class="form-label"><b>Descripción *</b></label>
                <textarea
                  class="form-control"
                  maxlength="1000"
                  v-model="sesion.description"
                  name="description"
                  style="height: 150px"
                ></textarea>
              </div>
            </div>

            <div class="row">
              <div class="mb-3">
                <label for="" class="form-label"><b>Departamento *</b></label>
                <select
                  class="form-select"
                  name="department"
                  v-model="sesion.department"
                  id="departamento_id"
                  v-on:change="changeCity()"
                >
                  <option disabled>Seleccione ...</option>
                  <option
                    v-for="(i, index) in departament"
                    :key="index"
                    :value="i.id"
                  >
                    {{ i.nombre }}
                  </option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="mb-3">
                <label for="" class="form-label"><b>Ciudad *</b></label>
                <select
                  class="form-select"
                  v-model="sesion.municipality"
                  name="municipality"
                  id="municipio"
                >
                  <option disabled>Seleccione ...</option>
                  <option
                    v-for="(i, index) in ciudades"
                    :key="index"
                    :value="i.id"
                  >
                    {{ i.nombre }}
                  </option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="mb-3">
                <label for="" class="form-label"
                  ><b>Fecha de agendamiento *</b>
                </label>
                <div class="input-group">
                  <input
                    v-model="sesion.date"
                    type="date"
                    class="form-control"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-5">
            <div class="row">
              <label for="" class="form-label"
                ><b>Tipo de documento *</b></label
              >
              <select
                class="form-select"
                name="type_file"
                v-model="sesion.type_file"
              >
                <option
                  v-for="(i, index) in type_file"
                  :key="index"
                  :value="i.id"
                  v-text="i.nombre"
                ></option>
              </select>
            </div>
            <div class="row mt-3">
              <label for="" class="form-label"><b>Radicado CNE *</b></label>
              <input
                type="text"
                class="form-control"
                maxlength="30"
                name="cne"
                v-model="sesion.radicado_CNE"
              />
            </div>
            <div class="row mt-5">
              <div
                class="form-group files border"
                role="button"
                id="box_file"
                @click="openModalFile()"
              >
                <div class="row mt-5">
                  <img
                    class="img_file mx-auto d-block"
                    alt=""
                    style="width: 100px"
                    src="https://img.icons8.com/ios/452/google-docs.png"
                  />
                </div>
                <div class="row mt-1 mb-5">
                  <p class="text_file text-center">
                    Ingresa aquí tus documentos .pdf .png .jpg
                  </p>
                </div>
              </div>
            </div>
            <div class="row mt-5">
              <button type="submit" class="btn btn-primary">Crear sesión</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div
      class="modal fade"
      id="modal_file"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
              Agregar Documentos
            </h5>
            <button
              type="button"
              class="btn-close"
              @click="closeAddFile()"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row mb-2">
              <div class="col-11">
                <label class="form-label">Agrege todos los documentos</label>
              </div>
              <div class="col-1">
                <button
                  class="btn-more btn"
                  id="add_file"
                  @click="add_file()"
                  type="button"
                >
                  <!-- <i class="fas fa-plus"></i> -->
                  <i class="typcn typcn-document-add" style="color: green"></i>
                </button>
              </div>
            </div>
            <div class="col-12" id="box_files"></div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              @click="closeAddFile()"
            >
              Aceptar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
// $('body').on('click', '.delete_file', function() {
//   $(this).parent().parent().remove();
// });
export default {
  data() {
    return {
      type_file: [],
      ciudades: [],
      departament: [],
      sesion: {},
      // department = 0,
      //   municipality =0,
      //   radicado_CNE = '',
      //   theme = '',
      //   description = '',
      //   date = '',
    };
  },
  created() {
    const url = "/data-new-sesion";
    axios.get(url).then((r) => {
      this.type_file = r.data.tipo;
      this.ciudades = r.data.municipios;
      this.departament = r.data.departament;
    });
  },
  methods: {
    openModalFile() {
      $("#modal_file").modal("show");
    },
    add_file() {
      var file = `<div class="row">
              <div class="col-11">
                  <input v-model="sesion.file"  type="file" class="form-control mb-3" />
              </div>
              <div class="col-1">
                  <button class="btn-delete-file btn delete_file  @click="delete_file()" "><i class="typcn typcn-delete" style="color:red; backgroud:red;"></i></button>
              </div>
          </div>`;
      $("#box_files").append(file);
      $("body").on("click", ".delete_file", function () {
        $(this).parent().parent().remove();
      });
    },
    delete_file() {
      $(this).parent().parent().remove();
    },
    closeAddFile() {
      $("#modal_file").modal("hide");
    },
    changeCity() {
      var id = $("#departamento_id").val();
      axios.post("/changeCity", { id: id }).then((r) => {
        this.ciudades = r.data;
      });
    },
    save() {
      let datos = this.sesion;
      let url = "saveSesion";
      axios.post(url, datos).then((r) => {
        if (r.data.status == 406) {
          Swal.fire("Error", r.data.msg, "error");
        } else if (r.data.code == 200) {
          Swal.fire({
            icon: "success",
            title: "¡Perfercto!",
            text: "Datos guardados exitosamente",
          }).then(function () {
            window.location = "/main#/listarSesiones";
          });
        }
      });
    },
  },
};
</script>




