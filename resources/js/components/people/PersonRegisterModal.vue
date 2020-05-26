<template>
    <b-modal
        :id="registerModalId"
        :title="$t('people.register_new_person')"
        body-class="pb-0"
        centered
        ok-only
        :ok-disabled="busy"
        @shown="focusForm"
        @ok="handleOk"
    >
        <person-editor-form
            ref="editor"
            @submit="createPerson"
        />
        <template v-slot:modal-ok>
            <font-awesome-icon icon="check" />
            {{ $t('app.register') }}
        </template>
    </b-modal>
</template>

<script>
import peopleApi from '@/api/people'
import { showSnackbar } from '@/utils'
import PersonEditorForm from '@/components/people/PersonEditorForm'
export default {
    components: {
        PersonEditorForm
    },
    data () {
        return {
            registerModalId: 'registerPersoModal',
            busy: false
        }
    },
    methods: {
        open () {
            this.$bvModal.show(this.registerModalId)
        },
        focusForm () {
            this.$refs.editor.focus()
        },
        handleOk (bvModalEvt) {
            bvModalEvt.preventDefault()
            this.$refs.editor.submit()
        },
        async createPerson (person) {
            this.busy = true
            try {
                let data = await peopleApi.store(person)
                showSnackbar(data.message)
                this.$nextTick(() => {
                    this.$bvModal.hide(this.registerModalId)
                })
                this.$router.push({ name: 'library.lending.person', params: { personId: data.id }})
            } catch (err) {
                alert(this.$t('app.error_err', { err: err }))
            }
            this.busy = false
        }
    }
}
</script>
