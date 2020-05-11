<template>
    <span>
        <font-awesome-icon
            v-if="busy"
            icon="spinner"
            spin
        />
        <font-awesome-icon
            v-else-if="gender == 'm'"
            icon="male"
        />
        <font-awesome-icon
            v-else-if="gender == 'f'"
            icon="female"
        />
        <template v-else-if="allowUpdate">
            <button
                class="btn btn-warning btn-sm"
                title="Male"
                :disabled="disabled"
                @click="setGender('m')"
            >
                <font-awesome-icon icon="male"/>
            </button>
            <button
                class="btn btn-warning btn-sm"
                title="Female"
                :disabled="disabled"
                @click="setGender('f')"
            >
                <font-awesome-icon icon="female"/>
            </button>
        </template>
    </span>
</template>

<script>
import showSnackbar from '@/snackbar'
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
            gender: this.person.gender
        }
    },
    methods: {
        async setGender (value) {
            this.busy = true
            try {
                let data = await peopleApi.updateGender(this.person.id, value)
                this.gender = value
                showSnackbar(data.message);
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        }
    }
}
</script>
