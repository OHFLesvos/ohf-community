<template>
    <b-container
        v-if="category"
        class="px-0"
    >
        <category-form
            :category="category"
            :disabled="isBusy"
            @submit="handleUpdate"
            @cancel="handleCancel"
            @delete="handleDelete"
        />
        <hr>
        <p class="d-flex justify-content-between">
            <small>
                {{
                    $t("Used in {num} transactions.", {
                        num: category.num_transactions
                    })
                }}
            </small>
            <small>
                {{ $t('Last updated') }}:
                {{ category.updated_at | dateTimeFormat }}
            </small>
        </p>
    </b-container>
    <p v-else>
        {{ $t('Loading...') }}
    </p>
</template>

<script>
import { showSnackbar } from '@/utils'
import categoriesApi from '@/api/accounting/categories'
import CategoryForm from '@/components/accounting/CategoryForm'
export default {
    components: {
        CategoryForm
    },
    props: {
        id: {
            required: true
        }
    },
    data () {
        return {
            category: null,
            isBusy: false
        }
    },
    watch: {
        $route() {
            this.fetch()
        }
    },
    async created () {
        this.fetch()
    },
    methods: {
        async fetch () {
            try {
                let data = await categoriesApi.find(this.id)
                this.category = data.data
            } catch (err) {
                alert(err)
            }
        },
        async handleUpdate (formData) {
            this.isBusy = true
            try {
                await categoriesApi.update(this.id, formData)
                showSnackbar(this.$t('Category updated.'))
                this.$router.push({ name: 'accounting.categories.show', params: { id: this.id } })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        async handleDelete () {
            this.isBusy = true
            try {
                await categoriesApi.delete(this.id)
                showSnackbar(this.$t('Category deleted.'))
                this.$router.push({ name: 'accounting.categories.index' })
            } catch (err) {
                alert(err)
            }
            this.isBusy = false
        },
        handleCancel () {
            this.$router.push({ name: 'accounting.categories.show', params: { id: this.id } })
        }
    }
}
</script>
