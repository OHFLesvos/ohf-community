<template>
    <book-form
        v-if="book"
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
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import libraryApi from '@/api/library'
import { handleAjaxError, showSnackbar } from '@/utils'
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
        libraryApi.findBook(this.bookId)
                .then((data) => {
                    this.book = data.data
                    this.canDelete = data.meta.can_delete
                })
                .catch(handleAjaxError)
    },
    methods: {
        updateBook (data) {
            this.busy = true
            libraryApi.updateBook(this.bookId, data)
                .then((data) => {
                    showSnackbar(data.message)
                    document.location = this.route('library.lending.book', [this.bookId])
                })
                .catch((err) => {
                    handleAjaxError(err)
                    this.busy = false
                })
        },
        deleteBook () {
            if (confirm(this.$t('library.confirm_delete_book'))) {
                this.busy = true
                libraryApi.deleteBook(this.bookId)
                    .then((data) => {
                        showSnackbar(data.message)
                        document.location = this.route('library.books.index')
                    })
                    .catch((err) => {
                        handleAjaxError(err)
                        this.busy = false
                    })
            }
        }
    }
}
</script>
