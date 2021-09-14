@extends('layouts.principal_uriel')
@section('content')
    <nueva-sesion>
    </nueva-sesion>
    {{-- <script src="{{ asset('js/sessions.js') }}"></script>
    <script>
        $('#departamento').change(function() {
            var form_data = new FormData();
            form_data.append('id', $(this).val());
            form_data.append('_token', "{{ csrf_token() }}");
            $.ajax({
                type: 'POST',
                url: '/dep_municipio',
                dataType: "json",
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#municipio *').remove();
                    let options = '<option value="">Seleccione ...</option>';
                    $.each(data.municipios, function(key, val) {
                        options += `<option value="${val.id}">${val.nombre}</option>`;
                    })
                    $('#municipio').append(options);
                }
            })
        });
    </script> --}}
@endsection
