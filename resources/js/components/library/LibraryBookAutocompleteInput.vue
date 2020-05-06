<template>
    <autocomplete-input
        :placeholder="$t('library.search_title_author_isbn')"
        :url="`${route('api.library.books.filter')}?query=:keyword`"
        @select="$emit('select', $event)"
    />
</template>

<script>
import AutocompleteInput from '@/components/common/AutocompleteInput'
export default {
    components: {
        AutocompleteInput
    }
}
</script>
