<template>
    <div>
        <em class="text-danger">Needs to bring back book(s) to the
            <a :href="url" v-if="canOperateLibrary">
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
        canOperateLibrary: Boolean,
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