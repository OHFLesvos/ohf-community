<template>
    <validation-observer
        ref="observer"
        v-slot="{ handleSubmit }"
    >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <isbn-input
                v-model="isbn"
                ref="isbnInput"
                :hide-label="compact"
            />
            <p v-if="searching">
                {{ $t('app.searching') }}
            </p>
            <title-input
                v-model="title"
                :hide-label="compact"
            />
            <author-input
                v-model="author"
                :hide-label="compact"
            />
            <language-code-input
                v-model="language_code"
                :hide-label="compact"
            />
            <p v-if="!noButtons">
                <b-button
                    variant="primary"
                    type="submit"
                >
                    <font-awesome-icon icon="check" />
                    {{ book ? $t('app.update') : $t('app.register') }}
                </b-button>
                <slot></slot>
            </p>
        </b-form>
    </validation-observer>
</template>

<script>
import HttpStatus from 'http-status-codes'
import { handleAjaxError } from '@/utils'
import axios from '@/plugins/axios'
import isIsbn from 'is-isbn'
import { BForm, BButton } from 'bootstrap-vue'
import IsbnInput from '@/components/library/input/IsbnInput'
import TitleInput from '@/components/library/input/TitleInput'
import AuthorInput from '@/components/library/input/AuthorInput'
import LanguageCodeInput from '@/components/library/input/LanguageCodeInput'
export default {
    components: {
        BForm,
        BButton,
        IsbnInput,
        TitleInput,
        AuthorInput,
        LanguageCodeInput
    },
    props: {
        compact: Boolean,
        noButtons: Boolean,
        book: {
            required: false,
        }
    },
    data () {
        return {
            isbn: this.book ? this.book.isbn : '',
            title: this.book ? this.book.title : '',
            author: this.book ? this.book.author : '',
            language_code: this.book ? this.book.language_code : null,
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
        onSubmit () {
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

                    this.$nextTick(function () {
                        this.$refs.observer.validate()
                    })
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
