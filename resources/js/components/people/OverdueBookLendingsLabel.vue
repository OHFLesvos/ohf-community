<template>
    <div>
        <font-awesome-icon icon="book"/>
        <em class="text-danger">
            Needs to bring back book(s) to the
            <a
                v-if="url != null"
                :href="url"
            >
                {{ $t('library.library') }}
            </a>
            <template v-else>
                {{ $t('library.library') }}
            </template>
        </em>
    </div>
</template>

<script>
export default {
    props: {
        url: {
            type: String,
            required: false,
            default: null
        }
    },
}
</script>