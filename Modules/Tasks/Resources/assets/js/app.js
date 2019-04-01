window.Vue = require('vue');

import TaskList from './components/TaskList.vue';
Vue.component('task-list', TaskList);

new Vue({
    el: '#app'
});
