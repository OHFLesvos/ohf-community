<template>
    <span class="form-inline d-inline">
        <font-awesome-icon
            v-if="busy"
            icon="spinner"
            spin
        />
        <template v-else-if="dateOfBirth != null">
            {{ dateOfBirth }}  ({{ $t('people.age_n', {age: age}) }})
        </template>
        <template v-else-if="allowUpdate">
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
import { isDateString, dateOfBirthToAge, todayDate } from '@/utils'
import peopleApi from '@/api/people'
export default {
    props: {
        person: {
            required: true
        },
        allowUpdate: Boolean,
        disabled: Boolean
    },
    data () {
        return {
            busy: false,
            form: false,
            dateOfBirth: this.person.date_of_birth != null && this.person.date_of_birth.length > 0 ? this.person.date_of_birth : null,
            newDateOfBirth: null
        }
    },
    computed: {
        age () {
            return dateOfBirthToAge(this.dateOfBirth)
        },
        today () {
            return todayDate()
        }
    },
    watch: {
        form (val, oldVal) {
            if (val && !oldVal) {
                this.$nextTick(() => this.$refs.input.focus())
            }
            if (!val && oldVal) {
                this.newDateOfBirth = null
            }
        }
    },
    methods: {
        async setDateOfBirth () {
            if (this.newDateOfBirth == null || !isDateString(this.newDateOfBirth)) {
                this.$refs.input.select()
                return
            }
            this.busy = true
            try {
                let data = await peopleApi.updateDateOfBirth(this.person.id, this.newDateOfBirth)
                this.dateOfBirth = this.newDateOfBirth
                this.form = false
                showSnackbar(data.message)
                this.$emit('changed', this.age)
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
                this.busy = false // Important to set busy to false before nextTick
                this.$nextTick(() => this.$refs.input.select())
            }
            this.busy = false
        }
    }
}
</script>
