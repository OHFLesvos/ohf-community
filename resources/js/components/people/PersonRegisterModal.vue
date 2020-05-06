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
        <simple-person-editor
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
        createPerson (person) {
            this.busy = true
            axios.post(this.route('api.people.store'), person)
                .then(res => {
                    showSnackbar(res.data.message)
                    this.$nextTick(() => {
                        this.$bvModal.hide(this.registerModalId)
                    })
                    document.location = this.route('library.lending.person', [res.data.id])
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        }
    }
}
</script>
