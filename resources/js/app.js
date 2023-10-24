require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('group-chat', require('./components/GroupChat.vue').default);
Vue.component('private-chat', require('./components/PrivateChat.vue').default);


const app = new Vue({
    el: '#app',
});
