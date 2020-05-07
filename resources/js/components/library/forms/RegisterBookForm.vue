<template>
    <b-form
        ref="form"
        @submit.stop.prevent="handleSubmit"
    >
        <isbn-input
            v-model="newBookForm.isbn"
            ref="isbnInput"
            hide-label
        />
        <title-input
            v-model="newBookForm.title"
            hide-label
        />
        <author-input
            v-model="newBookForm.author"
            hide-label
        />
        <language-code-input
            v-model="newBookForm.language_code"
            hide-label
        />
    </b-form>
</template>

<script>
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
            newBookForm: {
                isbn: '',
                title: '',
                author: '',
                language_code: null
            }
        }
    },
    watch: {
        newBookForm: {
            deep: true,
            handler (val, oldVal) {
                console.log(val.isbn)
                console.log(oldVal.isbn)
                if (val.isbn != oldVal.isbn) {
                    var isbn = val.isbn.toUpperCase().replace(/[^+0-9X]/gi, '');
                    if (isIsbn.validate(isbn)) {
                        this.updateDataByISBN(isbn)
                    }
                }
            }
        }
    },
    methods: {
        focus () {
            this.$refs.isbnInput.focus()
        },
        handleSubmit (evt) {
            if (evt) evt.preventDefault()

            this.$emit('submit', this.newBookForm)
        },
        updateDataByISBN (isbn) {
            axios.get(this.route('api.library.books.findIsbn', {isbn: isbn}))
                .then(res => {
                    this.newBookForm.title = res.data.title
                    this.newBookForm.author = res.data.author
                    this.newBookForm.language_code = res.data.language
                })
                .catch(() => {
                    // TODO
                })
        }
    }
}
</script>
