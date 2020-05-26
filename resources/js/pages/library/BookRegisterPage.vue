<template>
    <book-form
        ref="form"
        :disabled="busy"
        @submit="registerBook"
    />
</template>

<script>
import libraryApi from '@/api/library'
import { showSnackbar } from '@/utils'
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
        async registerBook (payload) {
            this.busy = true
            try {
                let data = await libraryApi.storeBook(payload)
                showSnackbar(data.message)
                document.location = this.route('library.lending.book', [data.id])
            } catch (err) {
                alert(err)
                this.busy = false
            }
        }
    }
}
</script>
