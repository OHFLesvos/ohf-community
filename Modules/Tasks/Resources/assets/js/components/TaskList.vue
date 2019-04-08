<template>
    <div>
        <div v-if='errorMessage' class="alert alert-danger">{{ errorMessage }}</div>
        <div v-if='emptyList' class="alert alert-info">There are no tasks yet.</div>
        <ul class="list-group mb-2">
            <!-- List existing tasks -->
            <li class="list-group-item" v-for="task in list" :key="task.id">
                <form v-if="editTask == task" action="#" @submit.prevent="updateTask(task.id)">
                    <div class="form-group">
                        <input v-model="editTask.description" type="text" name="description" class="form-control" autocomplete="off" v-focus>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update task</button> &nbsp; 
                        <a href="#" @click.prevent="editTask = null">Cancel</a>
                    </div>
                </form>
                <div v-else class="d-flex justify-content-between">
                    <span>
                        <a class="mr-2" href="#">
                            <i class="far" 
                                v-on:mouseover="hoveredCircle = task.id" 
                                v-on:mouseout="hoveredCircle = 0"
                                v-bind:class="[hoveredCircle == task.id ? 'fa-check-circle' : 'fa-circle' ]"
                                @click.prevent="doneTask(task.id)"></i>
                        </a>
                        <span @click.prevent="editTask = task; addNewTask = false">{{ task.description }}</span>
                        <!-- <small class="text-muted ml-3">{{ task.created_at }}</small> -->
                    </span>
                    <span>
                        <a href="#" @click.prevent="deleteTask(task.id); addNewTask = null"><i class="fa fa-trash-alt"></i></a>
                    </span>
                </div>
            </li>
            <!-- Add new task -->
            <li class="list-group-item">
                <form v-if="addNewTask" action="#" @submit.prevent="createTask()">
                    <div class="form-group">
                        <input v-model="task.description" 
                            type="text" 
                            name="description" 
                            class="form-control" 
                            v-bind:class="{ 'is-invalid': newTaskInvalidDescription }"
                            autocomplete="off" 
                            ref="description"
                            v-focus>
                        <span v-if="newTaskInvalidDescription" class="invalid-feedback">{{ newTaskInvalidDescription }}</span>
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
            <a href="#" @click.prevent="refresh()"><i class="fa fa-refresh" v-bind:class="{ 'fa-spin': fetchRunning}"></i></a>
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
                fetchRunning: false,
                errorMessage: null,
                newTaskInvalidDescription: null,
            };
        },
        
        created() {
            this.fetchTaskList();
        },
        
        methods: {
            refresh() {
                this.addNewTask = false,
                this.editTask =null,
                this.fetchTaskList();
            },

            fetchTaskList() {
                this.fetchRunning = true;
                axios.get('api/tasks')
                    .then((res) => {
                        this.fetchRunning = false;
                        this.list = res.data.data;
                        this.emptyList = this.list.length === 0
                    });
            },
 
            createTask() {
                this.errorMessage = null;
                axios.post('api/tasks', this.task)
                    .then((res) => {
                        this.task.description = '';
                        this.fetchTaskList();
                    })
                    .catch((err) => {
                        this.errorMessage = err.response.data.message;
                        this.newTaskInvalidDescription = err.response.data.errors.description.join(' ');
                        this.$nextTick(() => this.$refs.description.focus());
                    });
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
                axios.patch('api/tasks/' + id + '/done')
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
