<template>
  <div>
    <div class="container mt-5">
      <label for="" class="p-2"
        >Veedurías/Parametrización/Tipos de archivo</label
      >
      <div class="row p-2 text-center border shadow">
        <div class="row">
          <div class="col-12 col-md-12 col-lg-10 col-xl-9 p-2">
            <h1 class="text-blue"><b>TIPOS DE ARCHIVOS</b></h1>
          </div>
          <div class="col-12 col-md-12 col-lg-2 col-xl-3 p-2">
            <button
              @click="openModalCrear"
              type="button"
              class="btn btn-warning text-white mt-2 new_document modal_crear_tipoDocumento"
            >
              Nuevo documento
            </button>
          </div>
        </div>
      </div>
    </div>

    <form @submit.prevent="filtrar" id="buscar_t_doc">
      <div class="row mt-5">
        <div class="col-sm-1"></div>
        <div class="col-sm-5 col-md-4 col-xl-3">
          <div class="form-group form_configure">
            <label for="name">Nombre</label>
            <input
              type="text"
              class="form-control"
              id="name"
              name="nombre_buscar"
              v-model="nombre"
            />
          </div>
        </div>
        <div class="col-sm-3 col-md-3 mt-4 col-xl-2">
          <button
            type="submit"
            class="btn btn-block search_parameterization filtrar"
          >
            Buscar
          </button>
        </div>
        <div class="col-7 col-sm-4 col-md-5 col-xl-6"></div>
      </div>
    </form>

    <div class="row mt-5">
      <div class="col-10"></div>
      <div class="col-2"></div>
    </div>

    <div class="container table-responsive mt-1">
      <table class="table table-bordered table_es" id="tablaDocumentos">
        <thead>
          <th>Opciones</th>
          <th>Nombre</th>
          <th>Fecha registro</th>
        </thead>
        <tbody id="ttipoDocumento">
          <!-- @foreach ($tipoDocumento as $row) -->
          <tr v-for="(i, index) in tipos" :key="index">
            <td>
              <button
                @click="openModalEdit(i, index)"
                type="button"
                class="btn update_parameterization modal_editar_tipoDocumento"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button
                @click="openModal(i.id, index)"
                type="button"
                class="btn delete_parameterization btn_modal_eliminar"
              >
                <i class="fas fa-trash"></i>
              </button>
            </td>
            <td>{{ i.nombre }}</td>
            <td>{{ i.created_at }}</td>
          </tr>
          <!-- @endforeach -->
        </tbody>
      </table>
    </div>

    <!-- Inicio de modal eliminar -->
    <div
      class="modal fade"
      id="modal_eliminar_tipoDocumento"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tipos de archivo</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            ¿Estás seguro de eliminar el tipo de archivo?
          </div>
          <form
            @submit.prevent="eliminar_documento"
            id="eliminar_tipoDocumento"
          >
            <input
              id="id_tipoDocumento"
              type="hidden"
              name="tipoDocumento_id"
              v-model="tipo.id"
            />
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
              >
                No
              </button>
              <button
                type="submit"
                class="btn btn-danger btn_eliminar_tipoDocumento"
              >
                Si
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Fin de modal eliminar -->

    <!-- Inicio modal crear -->
    <div
      class="modal fade"
      id="modal_crear_tipoDocumento"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="crear_documento" id="crear_tipoDocumento">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Nuevo tipo de Archivo
              </h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="nombre" class="col-form-label"
                  >Nombre del archivo</label
                >
                <input
                  type="text"
                  class="form-control validar1"
                  id="nombre"
                  name="nombre"
                  v-model="tipo.nombre"
                />
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
              >
                Cerrar
              </button>
              <button type="submit" class="btn btn-primary crear_tipoDocumento">
                Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Fin modal crear -->

    <!-- Inicio modal editar -->
    <div
      class="modal fade"
      id="modal_edit_tipoDocumento"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="editar_documento" id="editar_tipoDocumento">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Editar tipo de archivo
              </h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <input
                id="id_tipoDocumento_edit"
                class="validar"
                type="hidden"
                name="tipoDocumento_id"
                value=""
                v-model="tipo.id"
              />
              <div class="mb-3">
                <label for="nombre_tipoDocumento_edit" class="col-form-label"
                  >Nombre de archivo</label
                >
                <input
                  type="text"
                  class="form-control validar"
                  id="nombre_tipoDocumento_edit"
                  name="nombre_edit"
                  v-model="tipo.nombre"
                />
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
              >
                Cerrar
              </button>
              <button
                type="submit"
                class="btn btn-primary editar_tipoDocumento"
              >
                Guardar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Fin modal editar -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      tipos: [],
      tipo: {
        id: 0,
        index: 0,
        nombre: "",
        estado: "",
        usuario_creador: "",
        created_at: "",
      },
      nombre: "",
    };
  },
  created() {
    const url = "/index_tipo_d";
    axios.get(url).then((res) => {
      this.tipos = res.data;
    });
  },
  methods: {
    filtrar() {
      if (this.nombre != "") {
        let filtros = this.nombre;
        let url = "/buscar-tipoDocumento/" + filtros;
        axios.post(url).then((res) => {
          this.tipos = res.data.tipoDocumento;
        });
      } else {
        const url = "/index_tipo_d";
        axios.get(url).then((res) => {
          this.tipos = res.data;
        });
      }
    },
    openModal(id, index) {
      this.tipo.id = id;
      this.tipo.index = index;
      $("#id_tipoDocumento").val(id);
      $("#modal_eliminar_tipoDocumento").modal("show");
    },
    openModalCrear() {
      $("#modal_crear_tipoDocumento").modal("show");
    },
    openModalEdit(documento, index) {
      this.tipo.index = index;
      this.tipo.id = documento.id;
      this.tipo.nombre = documento.nombre;
      $("#modal_edit_tipoDocumento").modal("show");
    },
    editar_documento() {
      const editDocumento = this.tipo;
      let url = "update_tipoDocumento";
      axios.post(url, editDocumento).then((res) => {
        this.tipos[this.tipo.index].nombre = res.data.tipoDocumento.nombre;
        alert(res.data.msg);
      });
    },
    crear_documento() {
      const nuevoDocumento = this.tipo;
      console.log(nuevoDocumento);
      axios.post("/crear_tipoDocumento", nuevoDocumento).then((res) => {
        const datos = res.data.tipo_documento;
        this.tipos.push(datos);
        alert(res.data.msg);
      });
    },
    eliminar_documento() {
      axios.post(`/eliminar_tipoDocumento/${this.tipo.id}`).then((res) => {
        this.tipos.splice(this.tipo.index, 1);
        alert(res.data.msg);
      });
    },
  },
};
</script>
