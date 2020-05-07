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
import { BForm } from 'bootstrap-vue'
import IsbnInput from '@/components/library/IsbnInput'
import TitleInput from '@/components/library/TitleInput'
import AuthorInput from '@/components/library/AuthorInput'
import LanguageCodeInput from '@/components/library/LanguageCodeInput'
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
    methods: {
        focus () {
            this.$refs.isbnInput.focus()
        },
        handleSubmit (evt) {
            if (evt) evt.preventDefault()

            this.$emit('submit', this.newBookForm)
        }
    }
}
</script>
