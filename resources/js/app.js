require('./bootstrap');

window.Vue = require('vue').default;
import router from './router.js';
import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('group-chat', require('./components/GroupChat.vue').default);
Vue.component('private-chat', require('./components/PrivateChat.vue').default);


const app = new Vue({
    el: '#app',
    router,

});
