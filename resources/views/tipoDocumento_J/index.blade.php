@extends('layouts.principal_uriel')
@section('content')

    <tipo-documento></tipo-documento>


    <!-- Inicio de tabla resposive -->

    {{-- @include('tipoDocumento_J.modals.modal_crear')
    @include('tipoDocumento_J.modals.modal_eliminar')
    @include('tipoDocumento_J.modals.modal_edit') --}}

    <!-- Final de tabla responsive -->


    {{-- <script>
        $('body').on('click', '.btn_modal_eliminar', function() {

            $.post(
                "{{ route('modal_eliminar_tipoDocumento') }}", {
                    _token: "{{ csrf_token() }}",
                    id: $(this).data('tipodocumento_id')
                }
            ).done(function(data) {
                $('#id_tipoDocumento').val(data.id)
                $('#modal_eliminar_tipoDocumento').modal('show')
            })
        })

        $('body').on('click', '.modal_crear_tipoDocumento', function() {
            $('#nombre').val('')
            $('#modal_crear_tipoDocumento').modal('show')
        })

        $('body').on('click', '.modal_editar_tipoDocumento', function() {
            $.post(
                "{{ route('tipoDocumento.edit') }}", {
                    _token: "{{ csrf_token() }}",
                    id: $(this).data('tipodocumento_id_edit')
                }
            ).done(function(data) {
                let tipoDocumento = data.tipoDocumento
                $('#nombre_tipoDocumento_edit').val(tipoDocumento.nombre)
                $('#id_tipoDocumento_edit').val(tipoDocumento.id)
                $('#modal_edit_tipoDocumento').modal('show')
            })
        })

        $('body').on('click', '.filtrar', function() {
            $.post(
                "{{ route('buscar_tipoDocumento') }}",
                $('#buscar_t_doc').serialize()
            ).done(function(data) {
                $('#ttipoDocumento * ').remove()
                tabla(data)
                console.log(data.tipoDocumento);
            })
        })


        function tabla(data) {
            var table = $('#tablaDocumentos').DataTable();
            $('#tablaDocumentos').DataTable().clear().draw();
            $.each(data.tipoDocumento, function(key, val) {
                let botones = `
                            <button data-tipodocumento_id_edit="${val.id}" type="button" class="btn update_parameterization modal_editar_tipoDocumento">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-tipodocumento_id="${val.id}" type="button" class="btn delete_parameterization btn_modal_eliminar" seleccion="0" >
                                <i class="fas fa-trash"></i>
                            </button>
                            `;

                table.row.add([
                    botones,
                    val.nombre,
                    val.created_at,
                ]).draw();
            })
        }

        $('body').on('click', '.btn_eliminar_tipoDocumento', function() {

            $.post(
                "{{ route('tipoDocumento.destroy') }}",
                $('#eliminar_tipoDocumento').serialize()
            ).done(function(data) {
                if (data.status == 200) {
                    setTimeout(function(){ $('#modal_eliminar_tipoDocumento').modal('hide');},500);
                    alertas(data.msg, 'success')
                    tabla(data)
                } else {
                    alertas(data.msg, 'error')
                }
            })
        })

    </script> --}}
@endsection
