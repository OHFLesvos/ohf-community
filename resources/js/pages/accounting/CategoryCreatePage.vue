<template>
    <b-container
        class="px-0"
    >
        <category-form
            :disabled="isBusy"
            @submit="handleRegister"
            @cancel="handleCancel"
        />
    </b-container>
</template>

<script>
import { showSnackbar } from '@/utils'
import categoriesApi from '@/api/accounting/categories'
import CategoryForm from '@/components/accounting/CategoryForm.vue'
export default {
    title() {
        return this.$t("Create category");
    },
    components: {
        CategoryForm
    },
    data () {
        return {
            isBusy: false
        }
    },
    methods: {
        async handleRegister (formData) {
            this.isBusy = true
            try {
                await categoriesApi.store(formData)
                showSnackbar(this.$t('Category added.'))
                this.$router.push({ name: 'accounting.categories.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'accounting.categories.index' })
        }
    }
}
</script>
