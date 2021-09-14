  
  <div class="horizontal-menu">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row " style="width: 100%;">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center fondo_azul_header" style="width: 20%;">
        <button class="color_blanco_li navbar-toggler navbar-toggler align-self-center d-none d-lg-flex" type="button" data-toggle="minimize" style="margin-left: 88%;
        padding-right: 5px;
        position: relative;">
          <span class="typcn typcn-th-menu"></span>
        </button>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end fondo_azul_header" style="width: 80%;">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item  d-none d-lg-flex">
            <a class="nav-link color_blanco_li_titulo" href="#">
              {{ session('nombre_largo')}}
            </a>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">    
      
          <li class="nav-item dropdown ">
            <a class="nav-link pl-0 pr-0 color_blanco_li" href="{{ route('redirectCentralizado') }}">  
              <i class="fa fa-home fa-2x" ></i>&nbsp;
              <span class="nav-profile-name menu-title1">Inicio</span>&nbsp;
            </a>
          </li>   
          {!! $menuSuperior !!}

          <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle color_blanco_li pl-0 pr-0" href="#" data-toggle="dropdown" id="profileDropdown">  
                  <i class="fa fa-user-circle-o fa-2x" ></i>&nbsp;
                  <span class="nav-profile-name" >{{Auth::user()->usuario}}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                  <router-link class="dropdown-item " :to="{name:'cambiar_pass'}">
                    <i class="fa fa-key fa-1x" style="font-size: 1.5em;" aria-hidden="true"></i> Cambiar Contraseña
                  </router-link>
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out" style="font-size: 1.5em;" aria-hidden="true"></i>
                      Salir
                  </a>
                  <a class="dropdown-item" target="_blank" href="https://www.turrisystem.com/web/" >
                    <i class="fa fa-globe  fa-1x"  style="font-size: 1.5em;"  aria-hidden="true"></i>
                    Acerca de...
                </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                  {{ csrf_field() }}
                  </form>
              </div>

            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="typcn typcn-th-menu"></span>
        </button>
      </div>
    </nav>
  </div>
    <div class="container-fluid page-body-wrapper">
   
      <div class="siderarId">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <div class="d-flex sidebar-profile">
            <div class="text-center">
              <img id="logo_cne3" src="../../images/departamentos/Logo_CNE.png" alt="image">
            </div>
          </div>
          <div class="nav-search">
            <div class="input-group" id="caja_hr">
              <hr id="hr_sidebar">
              {{-- <input type="text" class="form-control" placeholder="Buscar..." aria-label="search" aria-describedby="search">
              <div class="input-group-append">
                <span class="input-group-text" id="search">
                  <i class="typcn typcn-zoom"></i>
                </span>
              </div> --}}
            </div>
          </div>
  
          <div id="accordion">

            <ul class="nav "> 
              <li class="nav-item">
                <a  data-toggle=""></a>
              </li>                         
              {!! $menuInferior !!}        
           
             
            </ul>
           
          </div>
          <footer class="footer">
            <div class="d-flex justify-content-center">
                <span class="text-center">&copy; Copyright - {{date("Y")}} - Consejo Nacional Electoral.</span>
            </div>
          </footer>
          
        </nav>

      </div>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <router-view>  
          </router-view>              
        </div>
      </div>
      <!-- main-panel ends -->
    </div>

