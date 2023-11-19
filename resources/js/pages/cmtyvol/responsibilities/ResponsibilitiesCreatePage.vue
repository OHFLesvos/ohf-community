<template>
    <b-container>
        <ResponsibilityForm
            :title="$t('Add responsibility')"
            :disabled="isBusy"
            @submit="handleCreate"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import responsibilitiesApi from "@/api/cmtyvol/responsibilities";

import ResponsibilityForm from "@/components/cmtyvol/ResponsibilityForm.vue";

export default {
    title() {
        return this.$t("Add responsibility");
    },
    components: {
        ResponsibilityForm
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
                await responsibilitiesApi.store(formData)
                showSnackbar(this.$t('Responsibility added.'))
                this.$router.push({ name: 'cmtyvol.responsibilities.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'cmtyvol.responsibilities.index' })
        }
    }
}
</script>
