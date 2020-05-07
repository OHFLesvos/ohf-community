<template>
    <b-form
        ref="form"
        @submit.stop.prevent="handleSubmit"
    >
        <isbn-input
            v-model="isbn"
            ref="isbnInput"
            hide-label
        />
        <p v-if="searching">
            {{ $t('app.searching') }}
        </p>
        <title-input
            v-model="title"
            hide-label
        />
        <author-input
            v-model="author"
            hide-label
        />
        <language-code-input
            v-model="language_code"
            hide-label
        />
    </b-form>
</template>

<script>
import HttpStatus from 'http-status-codes'
import { handleAjaxError } from '@/utils'
import axios from '@/plugins/axios'
import isIsbn from 'is-isbn'
import { BForm } from 'bootstrap-vue'
import IsbnInput from '@/components/library/input/IsbnInput'
import TitleInput from '@/components/library/input/TitleInput'
import AuthorInput from '@/components/library/input/AuthorInput'
import LanguageCodeInput from '@/components/library/input/LanguageCodeInput'
export default {
    components: {
        BForm,
        IsbnInput,
        TitleInput,
        AuthorInput,
        LanguageCodeInput
    },
    data () {
        return {
            isbn: '',
            title: '',
            author: '',
            language_code: null,
            searching: false
        }
    },
    watch: {
        isbn (val) {
            var isbn = val.toUpperCase().replace(/[^+0-9X]/gi, '');
            if (isIsbn.validate(isbn)) {
                this.updateDataByISBN(isbn)
            }
        }
    },
    methods: {
        focus () {
            this.$refs.isbnInput.focus()
        },
        handleSubmit (evt) {
            if (evt) evt.preventDefault()

            this.$emit('submit', {
                isbn: this.isbn,
                title: this.title,
                author: this.author,
                language_code: this.language_code
            })
        },
        updateDataByISBN (isbn) {
            this.searching = true
            this.title = ''
            this.author = ''
            this.language_code = null
            axios.get(this.route('api.library.books.findIsbn', {isbn: isbn}))
                .then(res => {
                    this.title = res.data.title
                    this.author = res.data.author
                    this.language_code = res.data.language
                })
                .catch((err) => {
                    if (err.response.status != HttpStatus.NOT_FOUND) {
                        handleAjaxError(err)
                    }
                })
                .finally(() => this.searching = false)
        }
    }
}
</script>
