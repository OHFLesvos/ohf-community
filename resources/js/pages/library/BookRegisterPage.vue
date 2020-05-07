<template>
    <book-form
        ref="form"
        :disabled="busy"
        @submit="registerBook"
    />
</template>

<script>
import axios from '@/plugins/axios'
import { handleAjaxError, showSnackbar } from '@/utils'
import BookForm from '@/components/library/forms/BookForm'
export default {
    components: {
        BookForm
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
        registerBook (data) {
            this.busy = true
            axios.post(this.route('api.library.books.store'), {
                    ...data
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
