<template>
    <b-modal
        :id="registerModalId"
        :title="$t('people.register_new_person')"
        body-class="pb-0"
        centered
        ok-only
        @show="resetModal"
        @shown="focusForm"
        @ok="handleOk"
        @hidden="resetModal"
    >
        <form
            ref="form"
            @submit.stop.prevent="handleSubmit"
        >
            <simple-person-editor
                v-model="person"
                ref="editor"
            />
        </form>
        <template v-slot:modal-ok>
            <font-awesome-icon icon="check" />
            {{ $t('app.register') }}
        </template>
    </b-modal>
</template>

<script>
import axios from '@/plugins/axios'
import { handleAjaxError, showSnackbar } from '@/utils'
import SimplePersonEditor from '@/components/people/SimplePersonEditor'
export default {
    components: {
        SimplePersonEditor
    },
    data () {
        return {
            registerModalId: 'registerPersoModal',
            person: {},
            // nameState: null,
        }
    },
    methods: {
        open () {
            this.$bvModal.show(this.registerModalId)
        },
        resetModal() {
            this.person = {}
            // this.nameState = null
        },
        focusForm () {
            this.$refs.editor.focus()
        },
        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            // this.nameState = valid
            return valid
        },
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault()
            this.handleSubmit()
        },
        handleSubmit () {
            if (!this.checkFormValidity()) {
                alert('form is not valid')
                return
            }
            axios.post(this.route('api.people.store'), this.person)
                .then(res => {
                    showSnackbar(res.data.message)
                    this.$nextTick(() => {
                        this.$bvModal.hide(this.registerModalId)
                    })
                    document.location = this.route('library.lending.person', [res.data.id])
                })
                .catch(handleAjaxError)
        }
    }
}
</script>
