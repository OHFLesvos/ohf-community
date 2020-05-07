<template>
    <div>
        <register-book-form
            ref="form"
            @submit="registerBook"
        />
    </div>
</template>

<script>
import axios from '@/plugins/axios'
import { handleAjaxError, showSnackbar } from '@/utils'
import RegisterBookForm from '@/components/library/forms/RegisterBookForm'
export default {
    components: {
        RegisterBookForm
    },
    mounted () {
        this.$refs.form.focus()
    },
    methods: {
        registerBook (newBook) {
            this.busy = true
            axios.post(this.route('api.library.books.store'), {
                    ...newBook
                })
                .then((res) => {
                    showSnackbar(res.data.message)
                    document.location = this.route('library.lending.book', [res.data.id])
                })
                .catch(handleAjaxError)
                .finally(() => this.busy = false)
        }
    }
}
</script>
