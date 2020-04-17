import Vue from'vue'

import TaskList from './components/collaboration/TaskList.vue'
Vue.component('task-list', TaskList)

new Vue({
    el: '#tasks-app'
});
