<template>
    <book-form
        ref="form"
        :disabled="busy"
        @submit="registerBook"
    />
</template>

<script>
import libraryApi from '@/api/library'
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
            libraryApi.storeBook(data)
                .then((data) => {
                    showSnackbar(data.message)
                    document.location = this.route('library.lending.book', [data.id])
                })
                .catch((err) => {
                    handleAjaxError(err)
                    this.busy = false
                })
        }
    }
}
</script>
