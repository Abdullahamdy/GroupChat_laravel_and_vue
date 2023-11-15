import Vue from 'vue'  //
  import VueRouter from 'vue-router';
  import PrivateChat from './components/PrivateChat.vue';
  import GroupChat from './components/GroupChat.vue';

  Vue.use(VueRouter)
  const routes = [
    {
      path: '/PrivateChat',
      component: PrivateChat,
      name: 'private-chat', // Assign a unique name to the route
    },
    {
      path: '/groupChat',
      component: GroupChat,
      name: 'group-chat', // Assign a unique name to the route
    },
  ];

  const router = new VueRouter({
    routes,
    mode: 'history',
  });

  export default router;
