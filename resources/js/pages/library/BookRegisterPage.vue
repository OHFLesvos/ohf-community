<template>
    <register-book-form
        ref="form"
        :disabled="busy"
        @submit="registerBook"
    />
</template>

<script>
import axios from '@/plugins/axios'
import { handleAjaxError, showSnackbar } from '@/utils'
import RegisterBookForm from '@/components/library/forms/RegisterBookForm'
export default {
    components: {
        RegisterBookForm
    },
    data () {
        return {
            busy: false,
        }
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
                .catch((err) => {
                    handleAjaxError(err)
                    this.busy = false
                })
        }
    }
}
</script>
