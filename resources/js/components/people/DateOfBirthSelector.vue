<template>
    <span class="form-inline d-inline">
        <font-awesome-icon
            v-if="busy"
            icon="spinner"
            spin
        />
        <template v-else-if="dateOfBirth != null">
            {{ dateOfBirth }}  (age {{ age }})
        </template>
        <template v-else-if="apiUrl != null">
            <template v-if="form">
                <input
                    ref="input"
                    v-model="newDateOfBirth"
                    type="text"
                    :max="today"
                    pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}'"
                    title="YYYY-MM-DD"
                    placeholder="YYYY-MM-DD"
                    class="form-control form-control-sm"
                    @keydown.enter="setDateOfBirth"
                    @keydown.esc="form = false"
                />
                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    @click="setDateOfBirth"
                >
                    <font-awesome-icon icon="check"/>
                </button>
                <button
                    type="button"
                    class="btn btn-secondary btn-sm"
                    @click="form = false"
                >
                    <font-awesome-icon icon="times"/>
                </button>
            </template>
            <button
                v-else
                class="btn btn-warning btn-sm"
                title="Set date of birth"
                :disabled="disabled"
                @click="form = true"
            >
                <font-awesome-icon icon="calendar-plus"/>
            </button>
        </template>
    </span>
</template>

<script>
import showSnackbar from '@/snackbar'
import { handleAjaxError, isDateString, dateOfBirthToAge, todayDate } from '@/utils'
export default {
    props: {
        apiUrl: {
            type: String,
            required: false,
            default: null
        },
        value: {
            type: String,
            required: false,
            default: null
        },
        disabled: Boolean
    },
    data() {
        return {
            busy: false,
            form: false,
            dateOfBirth: this.value != null && this.value.length > 0 ? this.value : null,
            newDateOfBirth: null
        }
    },
    computed: {
        age() {
            return dateOfBirthToAge(this.dateOfBirth)
        },
        today() {
            return todayDate()
        }
    },
    watch: {
        form(val, oldVal) {
            if (val && !oldVal) {
                this.$nextTick(() => {
                    this.$refs.input.focus();
                })
            }
            if (!val && oldVal) {
                this.newDateOfBirth = null
            }
        }
    },
    methods: {
        setDateOfBirth() {
            if (this.newDateOfBirth == null || !isDateString(this.newDateOfBirth)) {
                this.$refs.input.select();
                return;
            }
            this.busy = true
            axios.patch(this.apiUrl, {
                    'date_of_birth': this.newDateOfBirth
                })
                .then(response => {
                    var data = response.data
                    this.dateOfBirth = this.newDateOfBirth
                    this.form = false
                    showSnackbar(data.message);
                    this.$emit('setAge', this.age)
                })
                .catch(err => {
                    handleAjaxError(err);
                    this.busy = false
                    this.$nextTick(() => {
                        this.$refs.input.select();
                    })
                })
                .then(() => {
                    this.busy = false
                })
        },
    }
}
</script>