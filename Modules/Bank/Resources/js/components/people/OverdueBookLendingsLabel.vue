<template>
    <div>
        <font-awesome-icon icon="book"/>
        <em class="text-danger">
            Needs to bring back book(s) to the
            <a
                v-if="url != null"
                :href="url"
            >
                {{ lang['library::library.library'] }}
            </a>
            <template v-else>
                {{ lang['library::library.library'] }}
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
        },
        lang: {
            type: Object,
            required: true
        }
    },
}
</script>