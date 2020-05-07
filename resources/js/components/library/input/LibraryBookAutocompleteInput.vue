<template>
    <autocomplete-input
        :placeholder="$t('library.search_title_author_isbn')"
        :url="url"
        @select="$emit('select', $event)"
    />
</template>

<script>
import AutocompleteInput from '@/components/common/AutocompleteInput'
export default {
    props: {
        availableOnly: Boolean
    },
    components: {
        AutocompleteInput
    },
    computed: {
        url () {
            let url = `${this.route('api.library.books.filter')}?query=:keyword`
            if (this.availableOnly) {
                url += '&available=true'
            }
            return url
        }
    }
}
</script>
