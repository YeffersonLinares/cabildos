<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME')}}</title>
      <link rel="stylesheet" href="../css/template.css">
      <link rel="shortcut icon" href="../../../../images/sin_escudo.png" />
      <script>
        @auth
          @if(Auth::user()->super_administrador == '0')
            window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
          @elseif(Auth::user()->super_administrador == '1'  && session('conexion') != 'centralizado')
            window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
          @else
            window.Permissions = [];
          @endif
          window.Username = {!! "'". Auth::user()->usuario . "'" !!};
          window.superAdmin = {!! Auth::user()->super_administrador !!};
        @else
          window.Permissions = [];
        @endauth
      </script> 
      <style>
        /* .dropdown-submenu {
  position: relative;
}

.dropdown-submenu a::after {
  transform: rotate(-90deg);
  position: absolute;
  right: 6px;
  top: .8em;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-left: .1rem;
  margin-right: .1rem;
} */
      </style>
  </head>
  <body class="sidebar-light">
    <div id="app">
      <div class="container-scroller">
        @include('menu.sidebar')
      </div>
    </div>   
    <script src="../js/app.js"></script>
    <script src="../js/template.js"></script>
  </body>
</html>