<template>
    <b-container
        v-if="book"
        class="px-0"
    >
        <book-form
            :book="book"
            :disabled="busy"
            @submit="updateBook"
        >
            <b-button
                v-if="canDelete"
                variant="danger"
                type="button"
                :disabled="busy"
                @click="deleteBook"
            >
                <font-awesome-icon icon="trash" />
                {{ $t('app.delete') }}
            </b-button>
        </book-form>
    </b-container>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import libraryApi from '@/api/library'
import { showSnackbar } from '@/utils'
import BookForm from '@/components/library/forms/BookForm'
export default {
    components: {
        BookForm
    },
    props: {
        bookId: {
            required: true
        }
    },
    data() {
        return {
            book: null,
            canDelete: false,
            busy: false
        }
    },
    created () {
        this.loadBook()
    },
    methods: {
        async loadBook () {
            try {
                let data = await libraryApi.findBook(this.bookId)
                this.book = data.data
                this.canDelete = data.meta.can_delete
            } catch (err) {
                alert(err)
            }
        },
        async updateBook (payload) {
            this.busy = true
            try {
                let data = await libraryApi.updateBook(this.bookId, payload)
                showSnackbar(data.message)
                this.$router.push({ name: 'library.lending.book', params: { bookId: this.bookId }})
            } catch (err) {
                alert(err)
                this.busy = false
            }
        },
        async deleteBook () {
            if (confirm(this.$t('library.confirm_delete_book'))) {
                this.busy = true
                try {
                    let data = await libraryApi.deleteBook(this.bookId)
                    showSnackbar(data.message)
                    this.$router.push({ name: 'library.books.index' })
                } catch (err) {
                    alert(err)
                    this.busy = false
                }
            }
        }
    }
}
</script>
