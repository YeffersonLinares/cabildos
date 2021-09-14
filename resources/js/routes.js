import Vue from 'vue'
import Router from 'vue-router'
import axios from 'axios'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

Vue.use(Router);
Vue.component('v-select', vSelect);

function obtenerUrl() {
    //Obtiene la url del documento actual desde el directorio padre
    var rutaAbsoluta = window.location.pathname;
    //Convierte en un array que contiene los string separados por el slash "/"
    var vector = rutaAbsoluta.split('/');

    //Concatena la informacion para construir la url
    var url = window.location.protocol + '//' + window.location.host ;

    //si es laragon o apache
    if(vector[1] != 'main'){
      url = url + '/' + vector[1];
      if(vector[2] == 'public'){
        url =  url + '/public';
      }
    }
    return url;
}

  const router = new Router({
    // mode: 'history',
    // base: process.env.APP_URL,
    // base: process.env.BASE_URL,
    routes: [
        {
          path: '*',
          component: require('./components/404').default
        },
        {
          path: '/',
          name: 'home',
          component: require('./components/Home').default
        },
        // {
        //   path: '/new-sesion',
        //   name: 'new-session',
        //   component: require('./components/sessions/new-sesion.vue').default
        // },
        {
          path: '/cambiarContraseña',
          name: 'cambiar_pass',
          component: require('./components/CambioContraseña').default,
          props: {ruta : obtenerUrl()}
        },
        {
            path: '/tipo-documento',
            name: 'tipo-documento',
            component: require('./components/tipoDocumento_J/index.vue').default
        }
    ]
  });

  axios.get(obtenerUrl()+'/menus/getRutasVue').then( r => r.data ).then(routes => {
    routes.forEach( (e) => {
      try {
        var storyToRead = require('./components/'+e.component+'.vue')
        router.addRoutes([
          {
              path: `${e.path}`,
              name: `${e.name}`,
              alias: `${'/'+e.path}`,
              component:require('./components/'+e.component).default,
              props: {ruta : obtenerUrl()},
              beforeEnter: (to, from, next) => {
                if(Permissions == '' && superAdmin != 1) {
                  next('/');
                }
                next();
              }
          },
        ]);
      } catch (b) {
        router.addRoutes([
          {
              path: `${e.path}`,
              name: `${e.name}`,
              alias: `${'/'+e.path}`,
              component:require('./components/404').default,
              props: {ruta : obtenerUrl()},
              beforeEnter: (to, from, next) => {
                if(Permissions == '' && superAdmin != 1) {
                  next('/');
                }
                next();
              }
          },
        ]);
      }

    });
  });

export default router;
