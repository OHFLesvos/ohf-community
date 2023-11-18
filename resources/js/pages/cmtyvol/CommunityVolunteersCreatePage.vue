<template>
    <b-container>
        <CmtyvolForm
            :title="$t('Register Community Volunteer')"
            :disabled="isBusy"
            @submit="handleCreate"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import cmtyvolApi from '@/api/cmtyvol/cmtyvol'
import CmtyvolForm from "@/components/cmtyvol/CmtyvolForm.vue";
export default {
    title() {
        return this.$t("Register Community Volunteer");
    },
    components: {
        CmtyvolForm
    },
    data () {
        return {
            isBusy: false
        }
    },
    methods: {
        async handleCreate (formData) {
            this.isBusy = true
            try {
                let data = await cmtyvolApi.store(formData)
                showSnackbar(this.$t('Community volunteer added.'))
                this.$router.push({ name: 'cmtyvol.show', params: { id: data.id } })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'cmtyvol.index' })
        }
    }
}
</script>
