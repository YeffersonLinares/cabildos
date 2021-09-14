require('./bootstrap');
import router from './routes'

window.Vue = require('vue');

Vue.component('crud-cabildo', require('./components/Crud_cabildos.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.directive('permiso', function(el, binding, vnode) {
    if (superAdmin == '1') {
        return vnode.elm.hidden = false;
    } else {
        if (Permissions.indexOf(binding.value) !== -1) {
            return vnode.elm.hidden = false;
        } else {
            return vnode.elm.hidden = true;
        }
    }
})

const app = new Vue({
    router,
    el: '#app',
    props: {},
    components: {},
    data: {
        menu: 0,
    },
    methods: {},
});
