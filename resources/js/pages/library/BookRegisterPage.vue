<template>
    <b-container class="px-0">
        <book-form
            ref="form"
            :disabled="busy"
            @submit="registerBook"
        />
    </b-container>
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
                this.$router.push({ name: 'library.lending.book', params: { bookId: data.id }})
            } catch (err) {
                alert(err)
                this.busy = false
            }
        }
    }
}
</script>
