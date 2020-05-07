<template>
    <register-book-form
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
    </register-book-form>
    <p v-else>
        {{ $t('app.loading') }}
    </p>
</template>

<script>
import axios from '@/plugins/axios'
import { handleAjaxError, showSnackbar } from '@/utils'
import RegisterBookForm from '@/components/library/forms/RegisterBookForm'
export default {
    components: {
        RegisterBookForm
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
        axios.get(this.route('api.library.books.show', [this.bookId]))
                .then((res) => {
                    this.book = res.data.data
                    this.canDelete = res.data.meta.can_delete
                })
                .catch(handleAjaxError)
    },
    methods: {
        updateBook (data) {
            this.busy = true
            axios.put(this.route('api.library.books.update', [this.bookId]), {
                    ...data
                })
                .then((res) => {
                    showSnackbar(res.data.message)
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
                axios.delete(this.route('api.library.books.destroy', [this.bookId]))
                    .then((res) => {
                        showSnackbar(res.data.message)
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
