<template>
    <autocomplete-input
        :placeholder="$t('people.search_existing_person')"
        :url="`${route('api.people.filterPersons')}?query=:keyword`"
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
