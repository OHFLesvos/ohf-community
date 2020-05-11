<template>
    <div>
        <!-- Error message -->
        <div
            v-if="errorMessage"
            class="alert alert-danger"
        >
            {{ errorMessage }}
        </div>

        <!-- No items message -->
        <div
            v-if="items && items.length == 0"
            class="alert alert-info"
        >
            There are no tasks yet.
        </div>

        <b-list-group
            v-if="items"
            class="mb-2"
        >
            <!-- List existing tasks -->
            <b-list-group-item
                v-for="task in items"
                :key="task.id"
            >
                <form
                    v-if="editTask == task"
                    action="#"
                    @submit.prevent="updateTask(task.id)"
                >
                    <div class="form-group">
                        <input
                            v-model.trim="editTask.description"
                            type="text"
                            name="description"
                            class="form-control"
                            autocomplete="off"
                            v-focus
                            :disabled="busy"
                        >
                    </div>
                    <div class="mt-3">
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="busy"
                        >
                            <font-awesome-icon icon="check" />
                            Update task
                        </b-button>
                        <b-button
                            variant="link"
                            class="ml-2"
                            :disabled="busy"
                            @click="editTask = null"
                        >
                            Cancel
                        </b-button>
                    </div>
                </form>
                <div
                    v-else
                    class="d-flex justify-content-between align-items-center"
                >
                    <span class="d-flex align-items-center">
                        <b-button
                            variant="link"
                            class="mr-2"
                            size="sm"
                            :disabled="busy"
                            @click="doneTask(task.id)"
                            @mouseover="hoveredCircle = task.id"
                            @mouseout="hoveredCircle = 0"
                        >
                            <font-awesome-icon
                                variant="far"
                                :icon="hoveredCircle == task.id ? 'check-circle' : 'circle'"
                            />
                        </b-button>
                        <span @click.prevent="editTask = task; addNewTask = false">
                            {{ task.description }}
                        </span>
                    </span>
                    <b-button
                        variant="link"
                        size="sm"
                        :disabled="busy"
                        @click="deleteTask(task.id); addNewTask = null"
                    >
                        <font-awesome-icon icon="trash-alt" />
                    </b-button>
                </div>
            </b-list-group-item>

            <!-- Add new task -->
            <b-list-group-item v-if="addNewTask">
                <form
                    @submit.prevent="createTask()"
                >
                    <div class="form-group">
                        <input
                            v-model.trim="task.description"
                            type="text"
                            name="description"
                            class="form-control"
                            autocomplete="off"
                            ref="description"
                            :disabled="busy"
                            v-focus
                        >
                        <!-- v-bind:class="{ 'is-invalid': newTaskInvalidDescription }"
                        <span v-if="newTaskInvalidDescription" class="invalid-feedback">{{ newTaskInvalidDescription }}</span> -->
                    </div>
                    <div class="mt-3">
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="busy"
                        >
                            <font-awesome-icon icon="check" />
                            Add task
                        </b-button>
                        <b-button
                            variant="link"
                            class="ml-2"
                            :disabled="busy"
                            @click="addNewTask = false"
                        >
                            Cancel
                        </b-button>
                    </div>
                </form>
            </b-list-group-item>
            <b-list-group-item
                v-else
                button
                :disabled="busy"
                @click="addNewTask = true; editTask = null"
            >
                <font-awesome-icon icon="plus-circle" class="mx-2" />
                Add task
            </b-list-group-item>
        </b-list-group>
        <div class="text-right mb-3 pr-1">
            <b-button
                variant="link"
                @click="refresh()"
            >
                <font-awesome-icon
                    icon="sync"
                    :spin="fetchRunning"
                />
            </b-button>
        </div>
    </div>
</template>

<script>
import tasksApi from '@/api/tasks'
export default {
    data() {
        return {
            items: null,
            task: {
                description: ''
            },
            addNewTask: false,
            editTask: null,
            hoveredCircle: null,
            fetchRunning: false,
            errorMessage: null,
            busy: false
            // newTaskInvalidDescription: null
        }
    },
    created () {
        this.fetchTaskList()
    },
    methods: {
        refresh() {
            this.addNewTask = false,
            this.editTask =null,
            this.fetchTaskList();
        },
        async fetchTaskList() {
            this.fetchRunning = true
            try {
                let data = await tasksApi.list()
                this.items = data.data
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.fetchRunning = false

        },
        async createTask() {
            this.busy = true
            this.errorMessage = null
            try {
                await tasksApi.store(this.task)
                this.task.description = ''
                this.fetchTaskList()
            } catch (err) {
                this.errorMessage = err
                // this.newTaskInvalidDescription = err.response.data.errors.description.join(' ');
                this.busy = false
                this.$nextTick(() => this.$refs.description.focus());
            }
            this.busy = false
        },
        async updateTask (id) {
            this.busy = true
            this.errorMessage = null
            try {
                await tasksApi.update(id, this.editTask)
                this.task.description = ''
                this.fetchTaskList()
            } catch (err) {
                this.errorMessage = err
            }
            this.busy = false
        },
        async deleteTask (id) {
            this.busy = true
            try {
                await tasksApi.delete(id)
                this.fetchTaskList()
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        },
        async doneTask(id) {
            this.busy = true
            try {
                await tasksApi.done(id)
                this.fetchTaskList()
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        }
    },
    directives: {
        focus: {
            inserted(el) {
                el.focus()
            }
        }
    }
}
</script>
