<template>
    <div>
        <div v-if='emptyList' class="alert alert-info">There are no tasks yet.</div>
        <ul class="list-group mb-2">
            <!-- List existing tasks -->
            <li class="list-group-item" v-for="(task, index) in list">
                <form v-if="editTask == task" action="#" @submit.prevent="updateTask(task.id)">
                    <div class="form-group">
                        <input v-model="editTask.description" type="text" name="description" class="form-control" autocomplete="off" v-focus>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update task</button> &nbsp; 
                        <a href="#" @click.prevent="editTask = null">Cancel</a>
                    </div>
                </form>
                <div v-else>
                    <a class="mr-2" href="#">
                        <i v-if="hoveredCircle == task.id" v-on:mouseout="hoveredCircle = 0" @click.prevent="doneTask(task.id)" class="fa fa-check-circle-o"></i>
                        <i v-else v-on:mouseover="hoveredCircle = task.id" class="fa fa-circle-thin"></i>
                    </a>
                    <span @click.prevent="editTask = task; addNewTask = false">{{ task.description }}</span>
                    <a href="#" @click.prevent="deleteTask(task.id); addNewTask = null" class="pull-right"><i class="fa fa-trash"></i></a>
                </div>
            </li>
            <!-- Add new task -->
            <li class="list-group-item">
                <form v-if="addNewTask" action="#" @submit.prevent="createTask()">
                    <div class="form-group">
                        <input v-model="task.description" type="text" name="description" class="form-control" autocomplete="off" v-focus>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Add task</button> &nbsp; 
                        <a href="#" @click.prevent="addNewTask = false">Cancel</a>
                    </div>
                </form>
                <a v-else href="#" @click.prevent="addNewTask = true; editTask = null"><i class="fa fa-plus mr-2"></i>Add task</a>
            </li>
        </ul>
        <div class="text-right mb-3 pr-1">
            <a href="#" @click.prevent="refresh()"><i class="fa fa-refresh"></i></a>
        </div>
    </div>
</template>

<script>
    const focus = {
        inserted(el) {
            el.focus()
        },
    }

    export default {
        data() {
            return {
                list: [],
                task: {
                    description: '',
                },
                addNewTask: false,
                editTask: null,
                hoveredCircle: null,
                emptyList: false,
            };
        },
        
        created() {
            this.fetchTaskList();
        },
        
        methods: {
            refresh() {
                this.list = [];
                this.fetchTaskList();
            },

            fetchTaskList() {
                axios.get('api/tasks').then((res) => {
                    this.list = res.data;
                    this.emptyList = this.list.length === 0
                });
            },
 
            createTask() {
                axios.post('api/tasks', this.task)
                    .then((res) => {
                        this.task.description = '';
                        this.fetchTaskList();
                    })
                    .catch((err) => console.error(err));
            },
 
            updateTask(id) {
                axios.put('api/tasks/' + id, this.editTask)
                    .then((res) => {
                        this.task.description = '';
                        this.fetchTaskList();
                    })
                    .catch((err) => console.error(err));
            },

            deleteTask(id) {
                axios.delete('api/tasks/' + id)
                    .then((res) => {
                        this.fetchTaskList()
                    })
                    .catch((err) => console.error(err));
            },

            doneTask(id) {
                axios.put('api/tasks/' + id + '/done')
                    .then((res) => {
                        this.fetchTaskList()
                    })
                    .catch((err) => console.error(err));
            },

        },

        directives: { 
            focus 
        },
    }
</script>
